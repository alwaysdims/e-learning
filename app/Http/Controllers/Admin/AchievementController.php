<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Achievement::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $achievements = $query->latest()->paginate(10)->withQueryString();

        return view('admins.achievement', compact('achievements', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'type'          => 'required|string|max:100',
            'target_value'  => 'required|numeric|min:0',
            'icon'          => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $iconPath = $request->file('icon')->store('achievements/icons', 'public');

        Achievement::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'type'          => $request->type,
            'target_value'  => $request->target_value,
            'icon'          => $iconPath,
        ]);

        return redirect()->route('admin.achievements.index')
                         ->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);

        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'type'          => 'required|string|max:100',
            'target_value'  => 'required|numeric|min:0',
            'icon'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $updateData = [
            'title'         => $request->title,
            'description'   => $request->description,
            'type'          => $request->type,
            'target_value'  => $request->target_value,
        ];

        if ($request->hasFile('icon')) {
            // Hapus icon lama
            if ($achievement->icon) {
                Storage::disk('public')->delete($achievement->icon);
            }
            $updateData['icon'] = $request->file('icon')->store('achievements/icons', 'public');
        }

        $achievement->update($updateData);

        return redirect()->route('admin.achievements.index')
                         ->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);

        // Hapus icon jika ada
        if ($achievement->icon) {
            Storage::disk('public')->delete($achievement->icon);
        }

        $achievement->delete();

        return redirect()->route('admin.achievements.index')
                         ->with('success', 'Prestasi berhasil dihapus.');
    }
}
