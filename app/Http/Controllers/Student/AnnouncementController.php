<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('user')
            ->latest(); // Urutkan dari terbaru

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter Hari Ini
        if ($request->has('today') && $request->today == 1) {
            $query->whereDate('created_at', Carbon::today());
        }

        $announcements = $query->paginate(5)
            ->withQueryString(); // Agar filter & search tetap saat pindah halaman

        return view('students.announcements', compact('announcements'));
    }
}
