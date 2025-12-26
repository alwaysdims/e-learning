<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MajorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Major::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code_major', 'like', "%{$search}%");
            });
        }

        $majors = $query->latest()->paginate(10)->withQueryString();

        return view('admins.jurusan', compact('majors', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code_major' => 'required|string|max:20|unique:majors,code_major',
            'name'       => 'required|string|max:255|unique:majors,name',
        ]);

        Major::create($request->all());

        return redirect()->route('admin.majors.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $major = Major::findOrFail($id);

        $request->validate([
            'code_major' => ['required', 'string', 'max:20', Rule::unique('majors')->ignore($major->id)],
            'name'       => ['required', 'string', 'max:255', Rule::unique('majors')->ignore($major->id)],
        ]);

        $major->update($request->all());

        return redirect()->route('admin.majors.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $major = Major::findOrFail($id);
        $major->delete();

        return redirect()->route('admin.majors.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
