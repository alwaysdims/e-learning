<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Subject::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $subjects = $query->latest()->paginate(10)->withQueryString();

        return view('admins.mapel', compact('subjects', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
        ]);

        Subject::create($request->all());

        return redirect()->route('admin.subjects.index')
                         ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('subjects')->ignore($subject->id)],
        ]);

        $subject->update($request->all());

        return redirect()->route('admin.subjects.index')
                         ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.subjects.index')
                         ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
