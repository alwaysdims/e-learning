<?php
namespace App\Http\Controllers\Teacher;

use Carbon\Carbon;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user()->teacher; // asumsi user -> teacher relasi

        $query = Schedule::with(['subject', 'classRoom'])
            ->where('teacher_id', $teacher->id);

        // 🔍 SEARCH (mapel / kelas)
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('subject', function ($s) use ($request) {
                    $s->where('name', 'ilike', '%' . $request->search . '%');
                })->orWhereHas('classRoom', function ($c) use ($request) {
                    $c->where('name', 'ilike', '%' . $request->search . '%');
                });
            });
        }

        // 🎓 FILTER KELAS
        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // 📅 FILTER HARI INI
        if ($request->today) {
            $today = Carbon::now()->translatedFormat('l'); // Senin, Selasa, dst
            $query->where('day', $today);
        }

        $schedules = $query
            ->orderBy('day')
            ->orderBy('start_date')
            ->paginate(5)
            ->withQueryString();

        // dropdown kelas yang diampu
        $classes = Schedule::where('teacher_id', $teacher->id)
            ->with('classRoom')
            ->get()
            ->pluck('classRoom.name', 'class_id')
            ->unique();

        return view('teachers.schedule', compact(
            'schedules',
            'classes'
        ));
    }
}
