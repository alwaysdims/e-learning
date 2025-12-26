<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1️⃣ Validasi
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // 2️⃣ Cek login
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // 3️⃣ Redirect sesuai role
            if ($user->role === 'admin') {
                return redirect()->route('admin.classes.index')->with('success', 'Anda berhasil login!');
            }

            if ($user->role === 'teacher') {
                return redirect()->route('teacher.dashboard')->with('success', 'Anda berhasil login!');
            }

            if ($user->role === 'student') {
                return redirect()->route('student.dashboard')->with('success', 'Anda berhasil login!');
            }

            // fallback (jaga-jaga)
            Auth::logout();
            return redirect()->route('auth.loginForm')
                ->with('error', 'Role tidak dikenali');
        }

        // 4️⃣ Jika gagal login
        return back()->with('error', 'Email atau password salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.loginForm')
            ->with('success', 'Berhasil logout');
    }
}
