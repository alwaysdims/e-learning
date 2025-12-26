<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Schedule::with(['classRoom.major', 'subject', 'teacher.user']);

        if ($search) {
            $query->whereHas('classRoom', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('subject', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('teacher.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('day', 'like', "%{$search}%");
        }

        $schedules = $query->latest()->paginate(10)->withQueryString();

        return view('admins.schedule', compact('schedules', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id'    => 'required|exists:classes,id',
            'subject_id'  => 'required|exists:subjects,id',
            'teacher_id'  => 'required|exists:teachers,id',
            'day'         => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        Schedule::create([
            'class_id'   => $request->class_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'day'        => $request->day,
            'start_date' => $request->start_time,
            'end_date'   => $request->end_time,
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $request->validate([
            'class_id'    => 'required|exists:classes,id',
            'subject_id'  => 'required|exists:subjects,id',
            'teacher_id'  => 'required|exists:teachers,id',
            'day'         => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule->update([
            'class_id'   => $request->class_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'day'        => $request->day,
            'start_date' => $request->start_time,
            'end_date'   => $request->end_time,
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }
}
