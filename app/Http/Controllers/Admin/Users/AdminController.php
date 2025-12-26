<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::where('role', 'admin')
            ->with('admin') // eager load relasi admin
            ->select('users.*');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $admins = $query->latest()->paginate(10)->withQueryString();

        return view('admins.user-admin', compact('admins', 'search'));
    }


    public function store(Request $request)
    {
        // 🔒 Batasi admin maksimal 2
        $adminCount = User::where('role', 'admin')->count();

        if ($adminCount >= 2) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jumlah admin sudah mencapai batas maksimal (2 admin).');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'address'  => 'nullable|string|max:500',
            'no_telp'  => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        Admin::create([
            'user_id'  => $user->id,
            'address'  => $request->address,
            'no_telp'  => $request->no_telp,
        ]);

        return redirect()->route('admin.user.admin.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }
    public function update(Request $request, $id)
    {
        $user = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8|confirmed',
            'address'  => 'nullable|string|max:500',
            'no_telp'  => 'nullable|string|max:20',
        ]);

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        $user->admin()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'address' => $request->address,
                'no_telp' => $request->no_telp,
            ]
        );

        return redirect()->route('admin.user.admin.index')
            ->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::where('role', 'admin')->findOrFail($id);
        $user->admin()->delete();
        $user->delete();

        return redirect()->route('admin.user.admin.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
    public function create()
    {
        //
    }
    public function show(string $id)
    {
        //
    }
    public function edit(string $id)
    {
        //
    }
}
