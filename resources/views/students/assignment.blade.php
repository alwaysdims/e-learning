@extends('students.layouts.main',['title' => 'Tugas & Ujian'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">

    <!-- Filter & Search Bar -->
    <div class="intro-y col-span-12 mt-2">
        <form method="GET" action="{{ route('student.assignments') }}" class="box p-3 flex flex-col sm:flex-row gap-2">
            <div class="relative w-full sm:flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control w-full pr-10" placeholder="Cari judul tugas atau mata pelajaran...">
            </div>

            <select name="subject_id" class="form-select w-full sm:w-48">
                <option value="">-- Semua Mata Pelajaran --</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="btn btn-primary flex-1 sm:flex-none">
                    <i data-lucide="filter" class="w-4 h-4 mr-1"></i> Filter
                </button>

                @if(request()->hasAny(['search', 'subject_id']))
                    <a href="{{ route('student.assignments') }}" class="btn btn-outline-secondary flex-1 sm:flex-none text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Tugas -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-5">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">JUDUL TUGAS</th>
                    <th class="whitespace-nowrap">MATA PELAJARAN</th>
                    <th class="whitespace-nowrap">MULAI - DEADLINE</th>
                    <th class="text-center whitespace-nowrap">DURASI</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="whitespace-nowrap">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $taskClass)
                @php
                    $task = $taskClass->task;
                    $studentTask = $task->studentTasks->first(); // dari leftJoin di controller
                    $now = \Carbon\Carbon::now();

                    // Tentukan status
                    $status = 'Belum Dimulai';
                    $statusClass = 'badge-secondary';

                    if ($studentTask) {
                        if ($studentTask->status == 'locked') {
                            $status = 'Locked';
                            $statusClass = 'badge-danger';
                        } elseif ($studentTask->submitted_at) {
                            $status = 'Completed';
                            $statusClass = 'badge-success';
                        } elseif ($studentTask->started_at) {
                            $status = 'Sedang Dikerjakan';
                            $statusClass = 'badge-warning';
                        }
                    }

                    if ($now->gt($taskClass->deadline) && !$studentTask?->submitted_at) {
                        $status = 'Terlambat';
                        $statusClass = 'badge-danger';
                    }

                    // Badge jika hari ini
                    $isToday = $now->between($taskClass->start_time, $taskClass->deadline);
                @endphp
                <tr class="intro-x hover:bg-slate-50 dark:hover:bg-darkmode-700 transition">
                    <td class="font-medium">
                        <div class="max-w-xs truncate">{{ $task->title }}</div>
                        @if($isToday)
                            <span class="inline-block px-2 py-1 text-xs rounded-full bg-danger/10 text-danger font-medium mt-1">
                                Hari Ini
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="font-medium">{{ $task->subject->name ?? '-' }}</div>
                    </td>
                    <td class="text-sm">
                        {{ \Carbon\Carbon::parse($taskClass->start_time)->format('d M Y H:i') }}<br>
                        <span class="text-slate-500">{{ \Carbon\Carbon::parse($taskClass->deadline)->format('d M Y H:i') }}</span>
                    </td>
                    <td class="text-center">
                        {{ $taskClass->duration }} menit
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $statusClass }}">
                            {{ $status }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap">
                        <a href="{{ route('student.assignments.show', $task->id) }}"
                            class="btn btn-primary btn-sm hover:opacity-90 transition-opacity duration-200 shadow-sm">
                            <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-slate-500">
                        <i data-lucide="file-x" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
                        <div>Belum ada tugas atau ujian yang diberikan.</div>
                        <div class="text-sm mt-2">Silakan cek kembali nanti.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="intro-y col-span-12 mt-6 flex justify-left">
            {{ $assignments->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
