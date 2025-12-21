<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function(){
    Route::get('/', function () {
        return view('auth.login');
    });
    Route::get('/login',[App\Http\Controllers\Auth\AuthController::class,'index'])->name('auth.loginForm');
    Route::post('/login',[App\Http\Controllers\Auth\AuthController::class,'login'])->name('auth.login');
});

Route::middleware('auth')->group(function(){
    Route::post('/logout',[App\Http\Controllers\Auth\AuthController::class,'logout'])->name('auth.logout');
});

Route::middleware(['auth','role:student'])->prefix('student')->group(function(){
    Route::get('/dashboard',[App\Http\Controllers\Student\DashboardController::class,'index'])->name('student.dashboard');
});
Route::middleware(['auth','role:teacher'])->prefix('teacher')->group(function(){
    Route::get('/dashboard',[App\Http\Controllers\Teacher\DashboardController::class,'index'])->name('teacher.dashboard');
});
Route::middleware(['auth','role:admin'])->prefix('admin')->group(function(){
    Route::get('/classes',[App\Http\Controllers\Admin\ClassController::class,'index'])->name('admin.class');
});


