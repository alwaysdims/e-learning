<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
    Route::get('/login', [App\Http\Controllers\Auth\AuthController::class, 'index'])->name('auth.loginForm');
    Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('auth.logout');
});

Route::middleware(['role:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');
});

Route::middleware(['role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('teacher.dashboard');

    // assignments
    Route::get('/assignments', [App\Http\Controllers\Teacher\AssignmentController::class, 'index'])->name('teacher.assignments');
    Route::post('/assignments', [\App\Http\Controllers\Teacher\AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'show'])->name('assignments.show');
    Route::put('/assignments/{assignment}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'update'])->name('assignments.update');     // update data
    Route::delete('/assignments/{assignment}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'destroy'])->name('assignments.destroy');

    // assignment published
    Route::get('/assignments/published/{id}', [App\Http\Controllers\Teacher\AssignmentController::class, 'publishedShow'])->name('teacher.assignment.publishedShow');
    Route::post('/assignments/published/{id}', [App\Http\Controllers\Teacher\AssignmentController::class, 'publishedStore'])->name('teacher.assignment.publishedStore');
    Route::put('/assignments/published/update/{id}', [App\Http\Controllers\Teacher\AssignmentController::class, 'publishedUpdate'])->name('teacher.assignment.publishedUpdate');
    Route::delete('/assignments/published/destroy/{id}', [App\Http\Controllers\Teacher\AssignmentController::class, 'publishedDestroy'])->name('teacher.assignment.publishedDestroy');

    // assignment management Pilihan Ganda
    Route::get('/assignments/management/{id}/pilihan-ganda', [App\Http\Controllers\Teacher\AssignmentController::class, 'managementPG'])->name('teacher.assignment.management.pg');
    Route::post('/assignments/management/{id}/pilihan-ganda', [\App\Http\Controllers\Teacher\AssignmentController::class, 'storePG'])->name('teacher.assignment.management.pg.store');
    Route::put('/assignments/management/{id}/pilihan-ganda/{question}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'updatePG'])->name('teacher.assignment.management.pg.update');
    Route::delete('/assignments/management/{id}/pilihan-ganda/{question}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'destroyPG'])->name('teacher.assignment.management.pg.destroy');

    // assignment management Essay
    Route::get('/assignments/management/{id}/essay', [App\Http\Controllers\Teacher\AssignmentController::class, 'managementEssay'])->name('teacher.assignment.management.essay');
    Route::post('/assignments/management/{id}/essay', [\App\Http\Controllers\Teacher\AssignmentController::class, 'storeEssay'])->name('teacher.assignment.management.essay.store');
    Route::get('/assignments/management/{id}/essay/{questionId}',[App\Http\Controllers\Teacher\AssignmentController::class, 'showEssay'])->name('teacher.assignment.management.essay.show');
    Route::put('/assignments/management/{id}/essay/{questionId}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'updateEssay'])->name('teacher.assignment.management.essay.update');
    Route::delete('/assignments/management/{id}/essay/{questionId}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'destroyEssay'])->name('teacher.assignment.management.essay.destroy');

    // assignment monitor
    Route::get('/assignments/management/{id}/monitor', [App\Http\Controllers\Teacher\AssignmentController::class, 'monitorStudent'])->name('teacher.assignment.monitor');

    // schedules
    Route::get('/schedules', [App\Http\Controllers\Teacher\ScheduleController::class, 'index'])->name('teacher.schedules');

    // materials
    Route::get('/materials', [\App\Http\Controllers\Teacher\MaterialController::class, 'index'])->name('teacher.materials.index');
    Route::post('/materials', [\App\Http\Controllers\Teacher\MaterialController::class, 'store'])->name('teacher.materials.store');
    Route::put('/materials/{id}', [\App\Http\Controllers\Teacher\MaterialController::class, 'update'])->name('teacher.materials.update');

    // material published
    Route::delete('/materials/{id}', [\App\Http\Controllers\Teacher\MaterialController::class, 'destroy'])->name('teacher.materials.destroy');
    Route::get('/materials/published/{id}', [\App\Http\Controllers\Teacher\MaterialController::class, 'published'])->name('teacher.materials.published');
    Route::post('/materials/published/{id}', [\App\Http\Controllers\Teacher\MaterialController::class, 'published_store'])->name('teacher.materials.published_store');
    Route::put('/materials/published/update/{id}', [\App\Http\Controllers\Teacher\MaterialController::class, 'published_update'])->name('teacher.materials.published_update');
    Route::delete('/materials/published/destroy/{id}', [\App\Http\Controllers\Teacher\MaterialController::class, 'published_destroy'])->name('teacher.materials.published_destroy');
});

Route::middleware(['role:admin'])->prefix('admin')->group(function () {

    Route::resource('classes', App\Http\Controllers\Admin\ClassController::class)
        ->names('admin.classes');

    Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class)
        ->names('admin.subjects');

    Route::resource('achievements', App\Http\Controllers\Admin\AchievementController::class)
        ->names('admin.achievements');

    Route::resource('majors', App\Http\Controllers\Admin\MajorController::class)
        ->names('admin.majors');

    Route::resource('schedules', App\Http\Controllers\Admin\ScheduleController::class)
        ->names('admin.schedules');

    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class)
        ->names('admin.announcements');

    Route::resource('user/admin', App\Http\Controllers\Admin\Users\AdminController::class)
        ->names('admin.user.admin');

    Route::resource('user/teacher', App\Http\Controllers\Admin\Users\TeacherController::class)
        ->names('admin.user.teacher');

    Route::resource('user/student', App\Http\Controllers\Admin\Users\StudentController::class)
        ->names('admin.user.student');
});
