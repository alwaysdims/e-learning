<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassMaterial;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student || !$student->class_id) {
            $materials = collect();
            $subjects = collect();
            return view('students.materials', compact('materials', 'subjects'));
        }

        $classId = $student->class_id;

        $query = ClassMaterial::where('class_id', $classId)
            ->with(['learningMaterial.subject', 'learningMaterial.teacher.user'])
            ->latest('published_at');

        // Search: judul materi atau nama mata pelajaran
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('learningMaterial', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('subject', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter mata pelajaran
        if ($request->filled('subject_id')) {
            $query->whereHas('learningMaterial', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        $materials = $query->paginate(5)->withQueryString();

        // Dropdown filter mata pelajaran yang ada di materi kelas ini
        $subjects = ClassMaterial::where('class_id', $classId)
            ->with('learningMaterial.subject')
            ->get()
            ->pluck('learningMaterial.subject')
            ->unique('id')
            ->sortBy('name');

        return view('students.materials', compact('materials', 'subjects'));
    }
}
