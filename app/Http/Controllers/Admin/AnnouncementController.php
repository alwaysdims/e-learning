<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Announcement::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $announcements = $query->latest()->paginate(10)->withQueryString();

        return view('admins.announcement', compact('announcements', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Announcement::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.announcements.index')
                         ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $announcement->update([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.announcements.index')
                         ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
                         ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
