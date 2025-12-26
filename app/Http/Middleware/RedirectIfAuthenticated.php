<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // 🔴 SUDAH LOGIN → BLOK LOGIN PAGE (URL TETAP)
        if (Auth::check()) {
            return response()->view('errors.403', [], 403); // blank page
        }

        return $next($request);
    }
}
