<?php

namespace App\Http\Controllers\Teacher;

use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\Announcement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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

    // Tampilkan halaman profile
    public function profile()
    {
        $user = Auth::user();
        $teacher = $user->teacher; // Pastikan relasi teacher sudah ada

        return view('teachers.profile', compact('user', 'teacher'));
    }

    // Update profile
    public function profileStore(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        $validated = $request->validate([
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'address'  => 'nullable|string|max:255',
            'no_telp'  => 'nullable|string|max:20',
        ]);

        // Update user
        $user->update([
            'email'    => $validated['email'],
            'password' => $request->filled('password')
                ? Hash::make($validated['password'])
                : $user->password,
        ]);

        // Update teacher
        $teacher->update([
            'address' => $validated['address'] ?? null,
            'no_telp' => $validated['no_telp'] ?? null,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
