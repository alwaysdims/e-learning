@extends('students.layouts.main',['title' => 'Jadwal Pelajaran'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">

    <div class="intro-y col-span-12 mt-2">
        <form method="GET" action="{{ route('student.schedules') }}" class="box p-3 flex flex-col sm:flex-row gap-2">
            {{-- Search - Dibuat flex-1 agar memenuhi ruang kosong --}}
            <div class="relative w-full sm:flex-1">
                <input type="text" name="search"
                    value="{{ request('search') }}"
                    class="form-control w-full pr-10"
                    placeholder="Cari mata pelajaran atau guru...">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-500">

                </div>
            </div>

            {{-- Filter Hari --}}
            <select name="day" class="form-select w-full sm:w-48">
                <option value="">-- Semua Hari --</option>
                @foreach($days as $day)
                    <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>
                        {{ $day }}
                    </option>
                @endforeach
            </select>

            {{-- Container Tombol agar tetap sejajar di mobile jika cukup ruang --}}
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="btn btn-primary flex-1 sm:flex-none">
                    <i data-lucide="filter" class="w-4 h-4 mr-1"></i> Filter
                </button>

                @if(request()->hasAny(['search', 'day']))
                    <a href="{{ route('student.schedules') }}" class="btn btn-outline-secondary flex-1 sm:flex-none text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Jadwal -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-5">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">HARI</th>
                    <th class="whitespace-nowrap">MATA PELAJARAN</th>
                    <th class="whitespace-nowrap">GURU</th>
                    <th class="text-center whitespace-nowrap">WAKTU</th>
                    <th class="whitespace-nowrap">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                <tr class="intro-x hover:bg-slate-50 dark:hover:bg-darkmode-700 transition">
                    <td class="font-medium">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $schedule->day == 'Senin' ? 'bg-primary/10 text-primary' :
                               ($schedule->day == 'Selasa' ? 'bg-success/10 text-success' :
                               ($schedule->day == 'Rabu' ? 'bg-warning/10 text-warning' :
                               ($schedule->day == 'Kamis' ? 'bg-info/10 text-info' :
                               ($schedule->day == 'Jumat' ? 'bg-pending/10 text-pending' :
                               ($schedule->day == 'Sabtu' ? 'bg-dark/10 text-dark' : 'bg-danger/10 text-danger'))))) }}">
                            {{ $schedule->day }}
                        </span>
                    </td>
                    <td>
                        <div class="font-medium">{{ $schedule->subject->name ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-slate-200 mr-2">
                                <div class="w-full h-full bg-primary/20 flex items-center justify-center text-xs font-bold text-primary">
                                    {{ Str::upper(Str::limit($schedule->teacher->user->name ?? 'Guru', 2, '')) }}
                                </div>
                            </div>
                            <div class="font-medium">
                                {{ $schedule->teacher->user->name ?? '-' }}
                            </div>
                        </div>
                    </td>
                    <td class="text-center text-sm">
                        {{ \Carbon\Carbon::parse($schedule->start_date)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($schedule->end_date)->format('H:i') }}
                    </td>
                    <td class="whitespace-nowrap">
                        <button type="button" data-tw-toggle="modal"
                            data-tw-target="#detailmodal-{{ $schedule->id }}"
                            class="btn btn-primary btn-sm hover:opacity-90 transition-opacity duration-200 shadow-sm">
                            <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Detail
                        </button>

                        <!-- Modal Detail -->
                        <div id="detailmodal-{{ $schedule->id }}" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content rounded-xl shadow-2xl border border-slate-100">

                                    <!-- Modal Body -->
                                    <div class="modal-body p-6 space-y-6">
                                        <!-- Card Informasi Utama -->
                                        <div class="bg-gradient-to-br from-slate-50 to-white p-5 rounded-xl border border-slate-200 shadow-sm">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <!-- Hari -->
                                                <div class="space-y-3">
                                                    <div class="flex items-center gap-2 text-slate-500">
                                                        <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                                                            <i data-lucide="calendar" class="w-4 h-4 text-primary"></i>
                                                        </div>
                                                        <span class="font-medium">Hari</span>
                                                    </div>
                                                    <div class="text-2xl font-bold text-slate-800 pl-10">{{ $schedule->day }}</div>
                                                </div>

                                                <!-- Waktu -->
                                                <div class="space-y-3">
                                                    <div class="flex items-center gap-2 text-slate-500">
                                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                                            <i data-lucide="clock" class="w-4 h-4 text-emerald-600"></i>
                                                        </div>
                                                        <span class="font-medium">Waktu</span>
                                                    </div>
                                                    <div class="text-2xl font-bold text-slate-800 pl-10">
                                                        <span class="text-emerald-600">{{ \Carbon\Carbon::parse($schedule->start_date)->format('H:i') }}</span>
                                                        <span class="text-slate-400 mx-2">-</span>
                                                        <span class="text-rose-600">{{ \Carbon\Carbon::parse($schedule->end_date)->format('H:i') }}</span>
                                                    </div>
                                                    <div class="text-sm text-slate-500 pl-10">
                                                        Durasi: {{ \Carbon\Carbon::parse($schedule->start_date)->diff(\Carbon\Carbon::parse($schedule->end_date))->format('%h jam %i menit') }}
                                                    </div>
                                                </div>

                                                <!-- Mata Pelajaran -->
                                                <div class="space-y-3">
                                                    <div class="flex items-center gap-2 text-slate-500">
                                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                            <i data-lucide="book-open" class="w-4 h-4 text-blue-600"></i>
                                                        </div>
                                                        <span class="font-medium">Mata Pelajaran</span>
                                                    </div>
                                                    <div class="text-xl font-bold text-slate-800 pl-10">{{ $schedule->subject->name ?? '-' }}</div>
                                                </div>

                                                <!-- Guru Pengajar -->
                                                <div class="space-y-3">
                                                    <div class="flex items-center gap-2 text-slate-500">
                                                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                                            <i data-lucide="user-check" class="w-4 h-4 text-amber-600"></i>
                                                        </div>
                                                        <span class="font-medium">Guru Pengajar</span>
                                                    </div>
                                                    <div class="text-xl font-bold text-slate-800 pl-10">{{ $schedule->teacher->user->name ?? '-' }}</div>
                                                    <div class="text-sm text-slate-500 pl-10">NIP: {{ $schedule->teacher->nip ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="modal-footer bg-gradient-to-r from-slate-50 to-slate-100 p-6 rounded-b-xl border-t border-slate-200">
                                        <div class="flex justify-between items-center w-full">

                                            <div class="flex gap-3">
                                                <button type="button" data-tw-dismiss="modal"
                                                    class="btn btn-outline-slate hover:bg-slate-100 px-5 py-2.5 rounded-xl justify-end font-medium transition-all duration-200 border border-slate-300 shadow-sm">
                                                    <i data-lucide="x" class="w-4 h-4 mr-2"></i> Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-slate-500">
                        <i data-lucide="calendar-x2" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
                        <div>Belum ada jadwal pelajaran.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="intro-y col-span-12 mt-6 flex justify-left">
            {{ $schedules->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
