<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Task;
use App\Models\Schedule;
use App\Models\ClassRoom;
use App\Models\TaskClass;
use App\Models\StudentTask;
use Illuminate\Http\Request;
use App\Models\AssignmentQuestion;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = Auth::user()->teacher->id;

        $query = Task::where('teacher_id', $teacherId)
            ->with('subject');

        // Search berdasarkan title atau nama subject
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('subject', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $tasks = $query->latest()->paginate(10);

        return view('teachers.assignment', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'total_questions' => 'required|integer|min:1',
            'type'           => 'required',
            'max_score'      => 'required|numeric|min:0',
        ]);

        Task::create([
            'teacher_id'       => Auth::user()->teacher->id,
            'subject_id'       => Auth::user()->teacher->subject_id, // otomatis dari teacher
            'title'            => $request->title,
            'description'      => $request->description,
            'total_questions'  => $request->total_questions,
            'type'             => $request->type,
            'max_score'        => $request->max_score,
        ]);

        return redirect()->route('teacher.assignments')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }
    public function show($id)
    {
        $teacherId = Auth::user()->teacher->id;

        // Ambil tugas milik guru yang login
        $task = Task::where('teacher_id', $teacherId)
            ->with(['subject', 'taskClasses.classRoom'])
            ->findOrFail($id);

        // Hitung jumlah soal pilihan ganda dan essay (asumsi ada kolom 'type' di AssignmentQuestion)
        $multipleChoiceCount = $task->questions()
            ->where('question_type', 'Pilihan Ganda')
            ->count();

        $essayCount = $task->questions()
            ->where('question_type', 'Essay')
            ->count();

        // Total siswa aktif (misal dari student_tasks yang sudah mulai mengerjakan)
        $activeStudents = $task->studentTasks()
            ->whereNotNull('started_at')
            ->distinct('student_id')
            ->count();

        // Durasi rata-rata dari task_classes (jika ada lebih dari satu kelas, ambil yang pertama atau rata-rata)
        $duration = $task->taskClasses->avg('duration') ?? 0;

        // Ambil start_time dan deadline dari task_class pertama (atau bisa diambil yang terbaru)
        $firstPublish = $task->taskClasses->first();

        return view('teachers.assignmentShow', compact(
            'task',
            'multipleChoiceCount',
            'essayCount',
            'activeStudents',
            'duration',
            'firstPublish'
        ));
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('teacher_id', Auth::user()->teacher->id)->findOrFail($id);

        $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'total_questions' => 'required|integer|min:1',
            'type'           => 'required',
            'max_score'      => 'required|numeric|min:0',
        ]);

        $task->update([
            'title'            => $request->title,
            'description'      => $request->description,
            'total_questions'  => $request->total_questions,
            'type'             => $request->type,
            'max_score'        => $request->max_score,
        ]);

        return redirect()->route('teacher.assignments')
            ->with('success', 'Tugas berhasil diupdate.');
    }

    public function destroy($id)
    {
        $task = Task::where('teacher_id', Auth::user()->teacher->id)->findOrFail($id);
        $task->delete();

        return redirect()->route('teacher.assignments')
            ->with('success', 'Tugas berhasil dihapus.');
    }


    // Tampilkan halaman publish tugas ke kelas
    public function publishedShow(Request $request, $id)
    {
        $task = Task::where('teacher_id', Auth::user()->teacher->id)
            ->with('subject')
            ->findOrFail($id);

        $teacherId = Auth::user()->teacher->id;

        // Ambil kelas yang diampu guru berdasarkan Schedule
        $teacherClasses = Schedule::where('teacher_id', $teacherId)
            ->with('classRoom')
            ->get()
            ->pluck('classRoom.name', 'class_id')
            ->unique();

        // Daftar kelas yang sudah dipublish tugas ini
        $query = TaskClass::where('task_id', $id)
            ->with('classRoom');

        if ($request->filled('search')) {
            $query->whereHas('classRoom', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $publishedClasses = $query->latest('published_at')->paginate(10);

        return view('teachers.assignmentPublished', compact(
            'task',
            'publishedClasses',
            'teacherClasses'
        ));
    }

    // Publish tugas ke kelas baru
    public function publishedStore(Request $request, $id)
    {
        $task = Task::where('teacher_id', Auth::user()->teacher->id)->findOrFail($id);

        $request->validate([
            'class_id'    => 'required|exists:classes,id',
            'start_time'  => 'required|date',
            'deadline'    => 'required|date|after:start_time',
            'duration'    => 'required|integer|min:1', // dalam menit
        ]);

        $teacherId = Auth::user()->teacher->id;

        // Validasi: guru harus mengajar di kelas ini
        $isTeaching = Schedule::where('teacher_id', $teacherId)
            ->where('class_id', $request->class_id)
            ->exists();

        if (!$isTeaching) {
            return redirect()->back()->with('error', 'Anda tidak mengajar di kelas tersebut.');
        }

        // Cek duplikat
        $exists = TaskClass::where('task_id', $id)
            ->where('class_id', $request->class_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Tugas ini sudah dipublish ke kelas tersebut.');
        }

        TaskClass::create([
            'task_id'      => $id,
            'class_id'     => $request->class_id,
            'published_at' => now(),
            'start_time'   => $request->start_time,
            'deadline'     => $request->deadline,
            'duration'     => $request->duration,
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil dipublish ke kelas.');
    }

    // Update detail publish (deadline, duration, dll)
    public function publishedUpdate(Request $request, $taskClassId)
    {
        $taskClass = TaskClass::whereHas('task', function ($q) {
            $q->where('teacher_id', Auth::user()->teacher->id);
        })->findOrFail($taskClassId);

        $request->validate([
            'start_time' => 'required|date',
            'deadline'   => 'required|date|after:start_time',
            'duration'   => 'required|integer|min:1',
        ]);

        $taskClass->update([
            'start_time' => $request->start_time,
            'deadline'   => $request->deadline,
            'duration'   => $request->duration,
        ]);

        return redirect()->back()->with('success', 'Detail publish berhasil diupdate.');
    }

    // Hapus publish tugas dari kelas
    public function publishedDestroy($taskClassId)
    {
        $taskClass = TaskClass::whereHas('task', function ($q) {
            $q->where('teacher_id', Auth::user()->teacher->id);
        })->findOrFail($taskClassId);

        $taskClass->delete();

        return redirect()->back()->with('success', 'Publish tugas dari kelas berhasil dihapus.');
    }

    // Manajemen Soal Pilihan Ganda
    public function managementPG(Request $request, $id)
    {
        $teacherId = Auth::user()->teacher->id;

        $task = Task::where('teacher_id', $teacherId)->findOrFail($id);

        if (!in_array($task->type, ['Pilihan Ganda', 'Campuran'])) {
            return response()->view('errors.403', [], 403);
        }

        $query = AssignmentQuestion::where('task_id', $id)
            ->where('question_type', 'Pilihan Ganda');

        if ($request->filled('search')) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(10);

        return view('teachers.managementPg', compact('task', 'questions'));
    }

    // Store Soal Pilihan Ganda
    public function storePG(Request $request, $id)
    {
        $teacherId = Auth::user()->teacher->id;

        $task = Task::where('teacher_id', $teacherId)->findOrFail($id);

        $request->validate([
            'question'       => 'required|string',
            'picture'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'answer_a'       => 'required|string',
            'answer_b'       => 'required|string',
            'answer_c'       => 'nullable|string',
            'answer_d'       => 'nullable|string',
            'answer_e'       => 'nullable|string',
            'correct_answer' => 'required|in:a,b,c,d,e',
            'score'          => 'required|numeric|min:1',
        ]);

        $data = [
            'task_id'         => $id,
            'question'        => $request->question,
            'question_type'   => 'Pilihan Ganda',
            'answer_a'        => $request->answer_a,
            'answer_b'        => $request->answer_b,
            'answer_c'        => $request->answer_c,
            'answer_d'        => $request->answer_d,
            'answer_e'        => $request->answer_e,
            'correct_answer'  => $request->correct_answer,
            'score'           => $request->score,
        ];

        if ($request->hasFile('picture')) {
            $data['picture'] = $request->file('picture')->store('questions', 'public');
        }

        AssignmentQuestion::create($data);

        return redirect()->back()->with('success', 'Soal pilihan ganda berhasil ditambahkan.');
    }

    // Update Soal Pilihan Ganda
    public function updatePG(Request $request, $id, $questionId)
    {
        $teacherId = Auth::user()->teacher->id;

        $task = Task::where('teacher_id', $teacherId)->findOrFail($id);

        $totalSoalSaatIni = AssignmentQuestion::where('task_id', $id)->count();

        if (($totalSoalSaatIni + 1) > $task->total_questions) {
            return redirect()->back()->with('error', 'Total soal tidak boleh melebihi ' . $task->total_questions . ' soal.');
        }

        $question = AssignmentQuestion::where('task_id', $id)
            ->findOrFail($questionId);

        $request->validate([
            'question'       => 'required|string',
            'picture'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'answer_a'       => 'required|string',
            'answer_b'       => 'required|string',
            'answer_c'       => 'nullable|string',
            'answer_d'       => 'nullable|string',
            'answer_e'       => 'nullable|string',
            'correct_answer' => 'required|in:a,b,c,d,e',
            'score'          => 'required|numeric|min:1',
        ]);

        $data = [
            'question'       => $request->question,
            'answer_a'       => $request->answer_a,
            'answer_b'       => $request->answer_b,
            'answer_c'       => $request->answer_c,
            'answer_d'       => $request->answer_d,
            'answer_e'       => $request->answer_e,
            'correct_answer' => $request->correct_answer,
            'score'          => $request->score,
        ];

        if ($request->hasFile('picture')) {
            if ($question->picture) {
                Storage::disk('public')->delete($question->picture);
            }
            $data['picture'] = $request->file('picture')->store('questions', 'public');
        }

        $question->update($data);

        return redirect()->back()->with('success', 'Soal pilihan ganda berhasil diupdate.');
    }

    // Hapus Soal Pilihan Ganda
    public function destroyPG($id, $questionId)
    {
        $teacherId = Auth::user()->teacher->id;

        $task = Task::where('teacher_id', $teacherId)->findOrFail($id);

        $question = AssignmentQuestion::where('task_id', $id)
            ->findOrFail($questionId);

        if ($question->picture) {
            Storage::disk('public')->delete($question->picture);
        }

        $question->delete();

        return redirect()->back()->with('success', 'Soal pilihan ganda berhasil dihapus.');
    }
    // Manajemen Soal Essay
    public function managementEssay(Request $request, $id)
    {
        $teacherId = Auth::user()->teacher->id;

        $task = Task::where('teacher_id', $teacherId)->findOrFail($id);

        // PEMBATAS AKSES
        if (!in_array($task->type, ['Essay', 'Campuran'])) {
            return response()->view('errors.403', [], 403);
        }

        $query = AssignmentQuestion::where('task_id', $id)
            ->where('question_type', 'Essay');

        if ($request->filled('search')) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(10);

        return view('teachers.managementEssay', compact('task', 'questions'));
    }

    // Tambah Soal Essay
    public function storeEssay(Request $request, $id)
    {
        $teacherId = Auth::user()->teacher->id;

        $task = Task::where('teacher_id', $teacherId)->findOrFail($id);

        $totalSoalSaatIni = AssignmentQuestion::where('task_id', $id)->count();

        if (($totalSoalSaatIni + 1) > $task->total_questions) {
            return redirect()->back()->with('error', 'Total soal tidak boleh melebihi ' . $task->total_questions . ' soal.');
        }

        $request->validate([
            'question' => 'required|string',
            'picture'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'score'    => 'required|numeric|min:1',
        ]);

        $data = [
            'task_id'        => $id,
            'question'       => $request->question,
            'question_type'  => 'Essay',
            'score'          => $request->score,
        ];

        if ($request->hasFile('picture')) {
            $data['picture'] = $request->file('picture')->store('questions', 'public');
        }

        AssignmentQuestion::create($data);

        return redirect()->back()->with('success', 'Soal essay berhasil ditambahkan.');
    }
    public function showEssay($id, $questionId)
    {
        $teacherId = Auth::user()->teacher->id;

        $task = Task::where('teacher_id', $teacherId)->findOrFail($id);

        $question = AssignmentQuestion::where('task_id', $id)
            ->where('question_type', 'essay')
            ->findOrFail($questionId);

        return view('teachers.assignment.essay-detail', compact('task', 'question'));
    }


    // Update Soal Essay
    public function updateEssay(Request $request, $id, $questionId)
    {

        // var_dump($request->all());
        // die();
        $teacherId = Auth::user()->teacher->id;

        $task = Task::where('teacher_id', $teacherId)->findOrFail($id);

        $question = AssignmentQuestion::where('task_id', $id)->findOrFail($questionId);


        $request->validate([
            'question' => 'required|string',
            'picture'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'score'    => 'required|numeric|min:1',
        ]);

        $data = [
            'question' => $request->question,
            'score'    => $request->score,
        ];

        if ($request->hasFile('picture')) {
            // Hapus gambar lama jika ada
            if ($question->picture) {
                Storage::disk('public')->delete($question->picture);
            }
            $data['picture'] = $request->file('picture')->store('questions', 'public');
        }

        $question->update($data);

        return redirect()->back()->with('success', 'Soal essay berhasil diupdate.');
    }

    // Hapus Soal Essay
    public function destroyEssay($id, $questionId)
    {
        $teacherId = Auth::user()->teacher->id;

        $task = Task::where('teacher_id', $teacherId)->findOrFail($id);

        $question = AssignmentQuestion::where('task_id', $id)->findOrFail($questionId);


        if ($question->picture) {
            Storage::disk('public')->delete($question->picture);
        }

        $question->delete();

        return redirect()->back()->with('success', 'Soal essay berhasil dihapus.');
    }
    // Monitor Siswa untuk tugas tertentu
    public function monitorStudent(Request $request, $id)
    {
        $teacherId = Auth::user()->teacher->id;

        $task = Task::where('teacher_id', $teacherId)->findOrFail($id);

        // Ambil kelas yang dipublish tugas ini
        $publishedClasses = TaskClass::where('task_id', $id)
            ->pluck('class_id')
            ->toArray();

        $query = StudentTask::where('task_id', $id)
            ->whereIn('class_id', $publishedClasses)
            ->with(['student.user', 'classRoom']);

        // Search nama siswa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter kelas
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $studentTasks = $query->latest('started_at')->paginate(10)->withQueryString();

        // Dropdown kelas yang dipublish tugas ini
        $classes = ClassRoom::whereIn('id', $publishedClasses)
            ->pluck('name', 'id');

        $statuses = ['in_progress', 'completed', 'timed_out', 'locked'];

        return view('teachers.monitorStudent', compact(
            'task',
            'studentTasks',
            'classes',
            'statuses'
        ));
    }

    // Reset Lock (ubah status locked → in_progress)
    public function monitorStudentResetLock(Request $request, $id)
    {
        $teacherId = Auth::user()->teacher->id;

        $task = Task::where('teacher_id', $teacherId)->findOrFail($id);

        $request->validate([
            'student_task_id' => 'required|exists:student_tasks,id,task_id,' . $id,
        ]);

        $studentTask = StudentTask::where('task_id', $id)
            ->findOrFail($request->student_task_id);

        if ($studentTask->status !== 'locked') {
            return redirect()->back()->with('error', 'Status siswa bukan locked.');
        }

        $studentTask->update([
            'status' => 'in_progress',
            'violation_count' => 0, // optional: reset pelanggaran
        ]);

        return redirect()->back()->with('success', 'Lock berhasil dibuka. Siswa dapat melanjutkan tugas.');
    }
}
