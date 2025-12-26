<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::where('role', 'teacher')
                     ->with(['teacher.subject'])
                     ->select('users.*');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function ($sq) use ($search) {
                      $sq->where('nip', 'like', "%{$search}%");
                  });
            });
        }

        $teachers = $query->latest()->paginate(10)->withQueryString();

        return view('admins.user-teacher', compact('teachers', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'nip'      => 'required|string|unique:teachers,nip',
            'subject_id' => 'required|exists:subjects,id',
            'address'  => 'nullable|string|max:500',
            'no_telp'  => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'teacher',
        ]);

        $teacher = Teacher::create([
            'user_id'    => $user->id,
            'nip'        => $request->nip,
            'subject_id' => $request->subject_id,
            'address'    => $request->address,
            'no_telp'    => $request->no_telp,
        ]);

        return redirect()->route('admin.user.teacher.index')
                         ->with('success', 'Teacher berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'teacher')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8|confirmed',
            'nip'      => ['required', Rule::unique('teachers')->ignore($user->teacher?->id)],
            'subject_id' => 'required|exists:subjects,id',
            'address'  => 'nullable|string|max:500',
            'no_telp'  => 'nullable|string|max:20',
        ]);

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        $user->teacher()->update([
            'nip'        => $request->nip,
            'subject_id' => $request->subject_id,
            'address'    => $request->address,
            'no_telp'    => $request->no_telp,
        ]);

        // $user->teacher->classes()->sync($request->filled('class_ids') ? $request->class_ids : []);

        return redirect()->route('admin.user.teacher.index')
                         ->with('success', 'Teacher berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::where('role', 'teacher')->findOrFail($id);
        $user->teacher->classes()->detach();
        $user->teacher()->delete();
        $user->delete();

        return redirect()->route('admin.user.teacher.index')
                         ->with('success', 'Teacher berhasil dihapus.');
    }
}
