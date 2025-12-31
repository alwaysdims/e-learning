@extends('teachers.layouts.main',['title' => 'Monitor Siswa - ' . $task->title])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Header Tugas -->
    <div class="intro-y col-span-12">
        <div class="alert alert-primary show mb-4">
            <div class="flex items-center">
                <i data-lucide="book-open" class="w-6 h-6 mr-3"></i>
                <div class="flex-1">
                    <strong>{{ $task->title }}</strong><br>
                    <small>Mata Pelajaran: {{ $task->subject->name ?? '-' }}</small>
                    | <small>Total Soal: {{ $task->total_questions }} | Skor Max: {{ $task->max_score }}</small>
                </div>
                <a href="{{ route('assignments.show', $task->id) }}"
                    class="btn btn-secondary bg-white/20 border-transparent text-white hover:bg-white/30  ml-auto">
                    <i data-lucide="corner-up-left" class="w-4 h-4 mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="intro-y col-span-12 mt-2">
        <form method="GET" class="box p-3 flex flex-col sm:flex-row items-center gap-3">

            <div class="relative w-full sm:flex-1">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control w-full pr-10"
                    placeholder="Cari nama siswa atau NIS...">
            </div>

            <select name="class_id" class="form-select w-full sm:w-32">
                <option value="">Kelas</option>
                @foreach($classes as $id => $name)
                <option value="{{ $id }}" {{ request('class_id') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
                @endforeach
            </select>

            <select name="status" class="form-select w-full sm:w-40">
                <option value="">Status</option>
                @foreach($statuses as $status)
                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                </option>
                @endforeach
            </select>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filter
                </button>

                @if(request()->hasAny(['search', 'class_id', 'status']))
                <a href="{{ route('teacher.assignment.monitor', $task->id) }}" class="btn btn-outline-secondary px-3">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
                @endif
            </div>

        </form>
    </div>
    <!-- Table Monitor -->
    <div class="intro-y col-span-12 mt-5">

        <div class="overflow-x-auto">

            <table class="table table-report -mt-2 whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">NO</th>
                        <th class="whitespace-nowrap">SISWA</th>
                        <th class="whitespace-nowrap">KELAS</th>
                        <th class="text-center whitespace-nowrap">MULAI</th>
                        <th class="text-center whitespace-nowrap">DEADLINE</th>
                        <th class="text-center whitespace-nowrap">SUBMIT</th>
                        <th class="text-center whitespace-nowrap">STATUS</th>
                        <th class="text-center whitespace-nowrap">SKOR</th>
                        <th class="text-center whitespace-nowrap">PELANGGARAN</th>
                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentTasks as $index => $st)
                    <tr class="intro-x">
                        <td class="text-center">{{ $studentTasks->firstItem() + $index }}</td>
                        <td>
                            <div class="font-medium">{{ $st->student->user->name ?? '-' }}</div>
                        </td>
                        <td class="text-center">{{ $st->classRoom->name ?? '-' }}</td>
                        <td class="text-center text-xs">
                            {{ $st->started_at ? \Carbon\Carbon::parse($st->started_at)->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="text-center text-xs">
                            {{ $st->due_at ? \Carbon\Carbon::parse($st->due_at)->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="text-center text-xs">
                            {{ $st->submitted_at ? \Carbon\Carbon::parse($st->submitted_at)->format('d M Y H:i') : '-' }}
                        </td>
                        <td class="text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $st->status == 'in_progress' ? 'bg-primary/10 text-primary' :
                               ($st->status == 'completed' ? 'bg-success/10 text-success' :
                               ($st->status == 'timed_out' ? 'bg-warning/10 text-warning' :
                               'bg-danger/10 text-danger')) }}">
                                {{ ucfirst(str_replace('_', ' ', $st->status)) }}
                            </span>
                        </td>
                        <td class="text-center font-medium">{{ $st->total_score ?? '-' }}</td>
                        <td class="text-center">{{ $st->violation_count }}</td>
                        <td class="text-center">
                            @if($st->status == 'locked')
                            <button type="button" class="btn btn-warning btn-sm" data-tw-toggle="modal"
                                data-tw-target="#reset-lock-modal-{{ $st->id }}">
                                <i data-lucide="unlock" class="w-4 h-4 mr-1"></i> Buka Lock
                            </button>
                            @else
                            <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Reset Lock -->
                    <div id="reset-lock-modal-{{ $st->id }}" class="modal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="font-medium text-base mr-auto">Buka Lock Tugas</h2>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin ingin membuka lock untuk siswa:</p>
                                    <p class="font-bold mt-2">{{ $st->student->user->name }}</p>
                                    <p class="text-slate-500 mt-3">
                                        Status akan diubah dari <span class="text-danger font-medium">Locked</span>
                                        menjadi <span class="text-primary font-medium">In Progress</span>.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" data-tw-dismiss="modal"
                                        class="btn btn-outline-secondary w-24 mr-3">
                                        Batal
                                    </button>
                                    <form action="{{ route('teacher.assignment.monitor.resetLock', $task->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="student_task_id" value="{{ $st->id }}">
                                        <button type="submit" class="btn btn-warning w-32">
                                            <i data-lucide="unlock" class="w-4 h-4 mr-1"></i> Buka Lock
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-12 text-slate-500">
                            <i data-lucide="users" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
                            <div>Belum ada siswa yang mengerjakan tugas ini.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <!-- Pagination -->
        <div class="intro-y col-span-12 mt-6 flex justify-left">
            {{ $studentTasks->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
