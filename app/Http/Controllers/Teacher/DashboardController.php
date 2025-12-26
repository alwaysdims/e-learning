<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        $teacherId = $teacher->id;

        // Hari ini dalam bahasa Indonesia
        $todayDayName = Carbon::now()->translatedFormat('l'); // Senin, Selasa, dst
        $todayDate = Carbon::now()->translatedFormat('l, d F Y');

        // Jadwal mengajar hari ini
        $todaySchedules = Schedule::where('teacher_id', $teacherId)
            ->where('day', $todayDayName)
            ->with(['subject', 'classRoom'])
            ->orderBy('start_date')
            ->get();

        // Total jadwal hari ini
        $todayScheduleCount = $todaySchedules->count();

        // Total kelas unik yang diajar (dari semua jadwal)
        $totalClasses = Schedule::where('teacher_id', $teacherId)
            ->distinct('class_id')
            ->count('class_id');

        // Total mata pelajaran unik
        $totalSubjects = Schedule::where('teacher_id', $teacherId)
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->count();

        // Pengumuman terbaru (maksimal 5)
        $announcements = Announcement::with('user')
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->latest()
            ->take(5)
            ->get();

        // Hitung pengumuman baru (misal dalam 24 jam terakhir)
        $newAnnouncements = $announcements->where('created_at', '>=', Carbon::now()->subDay())->count();

        return view('teachers.dashboard', compact(
            'teacher',
            'todaySchedules',
            'todayScheduleCount',
            'totalClasses',
            'totalSubjects',
            'announcements',
            'newAnnouncements',
            'todayDate'
        ));
    }
}
