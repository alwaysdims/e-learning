<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Subject;
use App\Models\Schedule;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use App\Models\ClassMaterial;
use App\Models\LearningMaterial;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = Auth::user()->teacher->id;

        $query = LearningMaterial::where('teacher_id', $teacherId)
            ->with('subject');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('subject', function ($sq) use ($request) {
                      $sq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $materials = $query->latest()->paginate(10);

        return view('teachers.material', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:20480', // max 20MB
        ]);

        $path = $request->file('content')->store('materials', 'public');

        LearningMaterial::create([
            'teacher_id' => Auth::user()->teacher->id,
            'subject_id' => Auth::user()->teacher->subject_id,
            'title'      => $request->title,
            'content'    => $path,
        ]);

        return redirect()->route('teacher.materials.index')
            ->with('success', 'Material berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $material = LearningMaterial::where('teacher_id', Auth::user()->teacher->id)
            ->findOrFail($id);

        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,txt|max:20480',
        ]);

        $data = [
            'subject_id' => Auth::user()->teacher->subject_id,
            'title'      => $request->title,
        ];

        if ($request->hasFile('content')) {
            // Hapus file lama
            if ($material->content && Storage::disk('public')->exists($material->content)) {
                Storage::disk('public')->delete($material->content);
            }
            $data['content'] = $request->file('content')->store('materials', 'public');
        }

        $material->update($data);

        return redirect()->route('teacher.materials.index')
            ->with('success', 'Material berhasil diupdate.');
    }

    public function destroy($id)
    {
        $material = LearningMaterial::where('teacher_id', Auth::user()->teacher->id)
            ->findOrFail($id);

        if ($material->content && Storage::disk('public')->exists($material->content)) {
            Storage::disk('public')->delete($material->content);
        }

        $material->delete();

        return redirect()->route('teacher.materials.index')
            ->with('success', 'Material berhasil dihapus.');
    }

    public function published(Request $request, $id)
    {
        $teacherId = Auth::user()->teacher->id;

        $material = LearningMaterial::where('teacher_id', $teacherId)
            ->with('subject')
            ->findOrFail($id);

        // Ambil kelas yang DIAJAR oleh teacher ini berdasarkan Schedule
        $teacherClasses = Schedule::where('teacher_id', $teacherId)
            ->with('classRoom')
            ->get()
            ->pluck('classRoom.name', 'class_id')
            ->unique(); // Hindari duplikat jika guru mengajar lebih dari 1 mata pelajaran di kelas sama

        // Query class_materials untuk materi ini
        $query = ClassMaterial::where('learning_material_id', $id)
            ->with('classRoom');

        if ($request->filled('search')) {
            $query->whereHas('classRoom', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $publishedClasses = $query->latest('published_at')->paginate(10);

        return view('teachers.materialPublished', compact('material', 'publishedClasses', 'teacherClasses'));
    }

    public function published_store(Request $request, $id)
    {
        $teacherId = Auth::user()->teacher->id;

        $material = LearningMaterial::where('teacher_id', $teacherId)->findOrFail($id);

        $request->validate([
            'class_id'     => 'required|exists:classes,id',
            'description'  => 'nullable|string',
        ]);

        // Validasi tambahan: pastikan kelas ini memang diajar oleh guru ini (berdasarkan Schedule)
        $isTeaching = Schedule::where('teacher_id', $teacherId)
            ->where('class_id', $request->class_id)
            ->exists();

        if (!$isTeaching) {
            return redirect()->back()->with('error', 'Anda tidak mengajar di kelas tersebut.');
        }

        // Cek apakah sudah pernah dipublish ke kelas ini
        $exists = ClassMaterial::where('learning_material_id', $id)
            ->where('class_id', $request->class_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Materi ini sudah dipublish ke kelas tersebut.');
        }

        ClassMaterial::create([
            'learning_material_id' => $id,
            'class_id'             => $request->class_id,
            'description'          => $request->description,
            'published_at'         => now(),
        ]);

        return redirect()->back()->with('success', 'Materi berhasil dipublish ke kelas.');
    }

    // Method update publish (edit deskripsi)
    public function published_update(Request $request, $classMaterialId)
    {
        $classMaterial = ClassMaterial::whereHas('learningMaterial', function ($q) {
            $q->where('teacher_id', Auth::user()->teacher->id);
        })->findOrFail($classMaterialId);

        $request->validate([
            'description' => 'nullable|string',
        ]);

        $classMaterial->update([
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Deskripsi berhasil diupdate.');
    }

    // Method hapus publish dari kelas
    public function published_destroy($classMaterialId)
    {
        $classMaterial = ClassMaterial::whereHas('learningMaterial', function ($q) {
            $q->where('teacher_id', Auth::user()->teacher->id);
        })->findOrFail($classMaterialId);

        $classMaterial->delete();

        return redirect()->back()->with('success', 'Publish materi dari kelas berhasil dihapus.');
    }
}
