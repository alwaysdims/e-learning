<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::where('role', 'student')
                     ->with(['student.classRoom', 'student.major'])
                     ->select('users.*');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('nis', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->latest()->paginate(10)->withQueryString();

        return view('admins.user-student', compact('students', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'username'        => 'required|string|max:255|unique:users,username',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:8|confirmed',
            'nis'             => 'required|string|unique:students,nis',
            'academic_year'   => 'required|string|max:9',
            'class_id'        => 'required|exists:classes,id',
            'major_id'        => 'required|exists:majors,id',
            'address'         => 'nullable|string|max:500',
            'no_telp'         => 'nullable|string|max:20',
            'birthday'        => 'nullable|date',
            'profile'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
        ]);

        $profilePath = null;
        if ($request->hasFile('profile')) {
            $profilePath = $request->file('profile')->store('profiles', 'public');
        }

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'student',
        ]);

        Student::create([
            'user_id'       => $user->id,
            'nis'           => $request->nis,
            'academic_year' => $request->academic_year,
            'class_id'      => $request->class_id,
            'major_id'      => $request->major_id,
            'address'       => $request->address,
            'no_telp'       => $request->no_telp,
            'birthday'      => $request->birthday,
            'profile'       => $profilePath,
        ]);

        return redirect()->route('admin.user.student.index')
                         ->with('success', 'Student berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'student')->findOrFail($id);

        $request->validate([
            'name'            => 'required|string|max:255',
            'username'        => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email'           => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password'        => 'nullable|min:8|confirmed',
            'nis'             => ['required', Rule::unique('students')->ignore($user->student?->id)],
            'academic_year'   => 'required|string|max:9',
            'class_id'        => 'required|exists:classes,id',
            'major_id'        => 'required|exists:majors,id',
            'address'         => 'nullable|string|max:500',
            'no_telp'         => 'nullable|string|max:20',
            'birthday'        => 'nullable|date',
            'profile'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        $updateData = [
            'nis'           => $request->nis,
            'academic_year' => $request->academic_year,
            'class_id'      => $request->class_id,
            'major_id'      => $request->major_id,
            'address'       => $request->address,
            'no_telp'       => $request->no_telp,
            'birthday'      => $request->birthday,
        ];

        if ($request->hasFile('profile')) {
            // Hapus foto lama jika ada
            if ($user->student?->profile) {
                Storage::disk('public')->delete($user->student->profile);
            }
            $updateData['profile'] = $request->file('profile')->store('profiles', 'public');
        }

        $user->student()->update($updateData);

        return redirect()->route('admin.user.student.index')
                         ->with('success', 'Student berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::where('role', 'student')->findOrFail($id);

        // Hapus foto profil jika ada
        if ($user->student?->profile) {
            Storage::disk('public')->delete($user->student->profile);
        }

        $user->student()->delete();
        $user->delete();

        return redirect()->route('admin.user.student.index')
                         ->with('success', 'Student berhasil dihapus.');
    }
}
