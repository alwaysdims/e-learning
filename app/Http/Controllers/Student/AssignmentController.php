<?php

namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\Subject;
use App\Models\TaskClass;
use App\Models\StudentTask;
use Illuminate\Http\Request;
use App\Models\StudentAnswer;
use App\Models\AssignmentQuestion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student || !$student->class_id) {
            return view('students.assignment', [
                'assignments' => collect(),
                'subjects' => collect()
            ]);
        }

        $classId = $student->class_id;
        $studentId = $student->id;

        $query = TaskClass::where('class_id', $classId)
            ->with([
                'task.subject',
                'task.teacher.user',
                'task.studentTasks' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                }
            ]);

        // Urutan tugas
        $now = Carbon::now();
        $query->orderByRaw("
        CASE
            WHEN ? BETWEEN start_time AND deadline THEN 1
            WHEN deadline >= ? THEN 2
            ELSE 3
        END
    ", [$now, $now])
            ->orderBy('deadline', 'asc');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('task', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas(
                        'subject',
                        fn($sq) =>
                        $sq->where('name', 'like', "%{$search}%")
                    );
            });
        }

        // Filter subject
        if ($request->filled('subject_id')) {
            $query->whereHas(
                'task',
                fn($q) =>
                $q->where('subject_id', $request->subject_id)
            );
        }

        $assignments = $query->paginate(10)->withQueryString();

        // Dropdown subject
        $subjects = TaskClass::where('class_id', $classId)
            ->with('task.subject')
            ->get()
            ->pluck('task.subject')
            ->unique('id')
            ->sortBy('name');

        return view('students.assignment', compact('assignments', 'subjects'));
    }


    public function show($id)
    {
        $student = Auth::user()->student;

        if (!$student || !$student->class_id) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini.');
        }

        $classId = $student->class_id;
        $studentId = $student->id;

        // Cari tugas yang dipublish ke kelas siswa
        $taskClass = TaskClass::where('task_id', $id)
            ->where('class_id', $classId)
            ->firstOrFail();

        $task = $taskClass->task->load('subject');

        // Hitung jumlah soal per tipe
        $essayCount = $task->questions()
            ->where('question_type', 'Essay')
            ->count();

        $pgCount = $task->questions()
            ->where('question_type', 'Pilihan Ganda')
            ->count();

        // Durasi dari taskClass
        $duration = $taskClass->duration;

        // Status siswa terhadap tugas ini
        $studentTask = StudentTask::where('task_id', $id)
            ->where('student_id', $studentId)
            ->first();

        $now = Carbon::now();

        // Tentukan status & apakah boleh dikerjakan
        $canStart = false;
        $statusText = 'Belum Dimulai';
        $statusClass = 'badge-secondary';

        if ($studentTask) {
            if ($studentTask->status == 'locked') {
                $statusText = 'Locked (Pindah Tab)';
                $statusClass = 'badge-danger';
            } elseif ($studentTask->submitted_at) {
                $statusText = 'Sudah Selesai';
                $statusClass = 'badge-success';
            } elseif ($studentTask->started_at) {
                $statusText = 'Sedang Dikerjakan';
                $statusClass = 'badge-warning';
            }
        } elseif ($now->lt($taskClass->start_time)) {
            $statusText = 'Belum Dimulai';
            $statusClass = 'badge-secondary';
        } elseif ($now->gt($taskClass->deadline)) {
            $statusText = 'Waktu Habis';
            $statusClass = 'badge-danger';
        } else {
            $statusText = 'Siap Dikerjakan';
            $statusClass = 'badge-success';
            $canStart = true;
        }

        return view('students.assignmentShow', compact(
            'task',
            'taskClass',
            'studentTask',
            'essayCount',
            'pgCount',
            'duration',
            'statusText',
            'statusClass',
            'canStart'
        ));
    }

    public function start($id)
    {
        $task = Task::with('questions')->findOrFail($id);

        $studentTask = StudentTask::firstOrCreate([
            'task_id' => $id,
            'student_id' => Auth::user()->student->id,
            'class_id' => Auth::user()->student->class_id,
        ], [
            'started_at' => now(),
            'due_at' => now()->addMinutes(60), // ambil dari durasi di task_classes
            'status' => 'in_progress'
        ]);

        return view('students.kerjakan', compact('task', 'studentTask'));
    }


    public function submit(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'student_task_id' => 'required|exists:student_tasks,id',
            'answers' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $studentTaskId = $request->student_task_id;
            $studentTask = StudentTask::findOrFail($studentTaskId);
            $totalScore = 0;

            // 2. Loop semua jawaban yang dikirim dari form
            foreach ($request->answers as $questionId => $userAnswer) {
                $question = AssignmentQuestion::find($questionId);

                $isCorrect = null;
                $scoreObtained = 0;

                // 3. Logika Koreksi Otomatis (Hanya untuk Pilihan Ganda)
                if ($question->question_type === 'Pilihan Ganda') {
                    $isCorrect = (strtolower($userAnswer) === strtolower($question->correct_answer));
                    $scoreObtained = $isCorrect ? $question->score : 0;
                    $totalScore += $scoreObtained;
                }

                // 4. Simpan atau Update ke tabel student_answers
                StudentAnswer::updateOrCreate(
                    [
                        'student_task_id' => $studentTaskId,
                        'assignment_question_id' => $questionId,
                    ],
                    [
                        'answer' => $userAnswer,
                        'is_correct' => $isCorrect,
                        'score_obtained' => $scoreObtained,
                    ]
                );
            }

            // 5. Update Status Tugas Siswa menjadi 'completed'
            $studentTask->update([
                'submitted_at' => now(),
                'total_score' => $totalScore,
                'status' => 'completed'
            ]);

            DB::commit();

            return redirect()->route('student.dashboard') // Arahkan ke halaman tugas selesai
                ->with('success', 'Tugas berhasil dikumpulkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
