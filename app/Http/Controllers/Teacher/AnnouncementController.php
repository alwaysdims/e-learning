<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('user')
            ->latest('created_at');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter "Hari ini"
        if ($request->has('today') && $request->today == '1') {
            $query->whereDate('created_at', Carbon::today());
        }

        $announcements = $query->paginate(10)->withQueryString();

        return view('teachers.announcement', compact('announcements'));
    }
}
