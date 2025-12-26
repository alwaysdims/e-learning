<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Major;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = ClassRoom::with(['major', 'homeRoomTeacher.user']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('grade_level', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%")
                    ->orWhereHas('major', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('homeRoomTeacher.user', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $classRooms = $query->latest()->paginate(10)->withQueryString();

        return view('admins.kelas', compact('classRooms', 'search'));
    }

    public function store(Request $request)
    {
        // cek guru sudah jadi wali kelas atau belum
        if ($request->home_room_teacher) {
            $exists = ClassRoom::where('home_room_teacher', $request->home_room_teacher)->exists();

            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Guru tersebut sudah menjadi wali kelas lain.');
            }
        }

        $request->validate([
            'name'            => 'required|string|max:255|unique:classes,name',
            'grade_level'     => 'required|in:10,11,12',
            'academic_year'   => 'required|string|max:9',
            'major_id'        => 'required|exists:majors,id',
            'home_room_teacher' => 'nullable|exists:teachers,id',
        ]);

        ClassRoom::create($request->all());

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        $class = ClassRoom::findOrFail($id);

        // cek guru sudah jadi wali kelas lain (kecuali kelas ini)
        if ($request->home_room_teacher) {
            $exists = ClassRoom::where('home_room_teacher', $request->home_room_teacher)
                ->where('id', '!=', $class->id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Guru tersebut sudah menjadi wali kelas lain.');
            }
        }

        $request->validate([
            'name'            => ['required', 'string', 'max:255', Rule::unique('classes')->ignore($class->id)],
            'grade_level'     => 'required|in:10,11,12',
            'academic_year'   => 'required|string|max:9',
            'major_id'        => 'required|exists:majors,id',
            'home_room_teacher' => 'nullable|exists:teachers,id',
        ]);

        $class->update($request->all());

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $class = ClassRoom::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
