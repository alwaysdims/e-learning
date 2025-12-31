<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;

        if (!$student || !$student->class_id) {
            $schedules = collect();
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            return view('students.schedules', compact('schedules', 'days'));
        }

        $classId = $student->class_id;

        $query = Schedule::where('class_id', $classId)
            ->with(['subject', 'teacher.user'])
            ->orderByRaw("
                CASE day
                    WHEN 'Monday' THEN 1
                    WHEN 'Tuesday' THEN 2
                    WHEN 'Wednesday' THEN 3
                    WHEN 'Thursday' THEN 4
                    WHEN 'Friday' THEN 5
                    ELSE 99
                END
            ")
            ->orderBy('start_date');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('subject', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                })->orWhereHas('teacher.user', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                });
            });
        }

        // Filter hari (langsung pakai bahasa Inggris)
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        $schedules = $query->paginate(5)->withQueryString();

        // Dropdown filter pakai bahasa Inggris
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        return view('students.schedules', compact('schedules', 'days'));
    }
}
