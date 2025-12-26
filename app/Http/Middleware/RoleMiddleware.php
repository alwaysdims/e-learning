<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 🔴 BELUM LOGIN → lempar ke login
        if (!Auth::check()) {
            return response()->view('errors.404', [], 404);
        }

        // 🔴 SUDAH LOGIN TAPI ROLE SALAH → BLANK PAGE (403 tanpa redirect)
        if (!in_array(Auth::user()->role, $roles)) {
            return response()->view('errors.403', [], 403);
        }

        return $next($request);
    }
}
