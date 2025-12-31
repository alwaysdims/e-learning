<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            abort(403, 'Data siswa tidak ditemukan.');
        }

        $classId = $student->class_id;

        // Tanggal hari ini dalam format Indonesia
        $todayDate = Carbon::now()->translatedFormat('l, d F Y');
        $todayDayName = Carbon::now()->translatedFormat('l'); // Senin, Selasa, dst

        // Jadwal hari ini berdasarkan kelas siswa
        $todaySchedules = Schedule::where('class_id', $classId)
            ->where('day', $todayDayName)
            ->with(['subject', 'teacher.user'])
            ->orderBy('start_date')
            ->get();

        $todayScheduleCount = $todaySchedules->count();

        // Pengumuman terbaru (maksimal 5)
        $announcements = Announcement::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Hitung pengumuman baru (dalam 24 jam terakhir)
        $newAnnouncements = $announcements->where('created_at', '>=', Carbon::now()->subDay())->count();

        return view('students.dashboard', compact(
            'student',
            'todaySchedules',
            'todayScheduleCount',
            'announcements',
            'newAnnouncements',
            'todayDate'
        ));
    }
}
