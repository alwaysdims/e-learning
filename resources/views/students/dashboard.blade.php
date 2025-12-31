@extends('students.layouts.main',['title' => 'Dashboard'])

@section('content')
<div class="intro-y box mt-6 overflow-hidden border border-slate-200/60 dark:border-darkmode-400 shadow-sm">
    <div class="flex flex-col lg:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
        <!-- Profile Section -->
        <div class="flex flex-1 items-center justify-center lg:justify-start">
            <div class="relative inline-block">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-4 border-white dark:border-darkmode-700 shadow-lg bg-slate-200 flex items-center justify-center">
                    @if($student->profile ?? false)
                        <img alt="Profile Photo" class="w-full h-full object-cover" src="{{ asset('storage/' . $student->profile) }}">
                    @else
                        <div class="w-full h-full bg-primary/20 flex items-center justify-center">
                            <span class="text-xl sm:text-2xl font-bold text-primary">
                                {{ Str::upper(Str::limit($student->user->name ?? 'S', 2, '')) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="ml-5">
                <h2 class="text-xl lg:text-2xl font-bold text-slate-800 dark:text-slate-100">
                    {{ $student->user->name ?? 'Nama Siswa' }}
                </h2>
                <div class="mt-2 flex flex-wrap gap-2">
                    <div class="px-3 py-1.5 rounded-full bg-primary/10 text-primary text-sm font-semibold inline-flex items-center">
                        <i data-lucide="id-card" class="w-3.5 h-3.5 mr-1.5"></i>
                        NIS: {{ $student->nis ?? '-' }}
                    </div>
                    <div class="px-3 py-1.5 rounded-full bg-success/10 text-success text-sm font-semibold inline-flex items-center">
                        <i data-lucide="school" class="w-3.5 h-3.5 mr-1.5"></i>
                        {{ $student->classRoom->name ?? '-' }}
                    </div>
                    @if($student->major)
                        <div class="px-3 py-1.5 rounded-full bg-warning/10 text-warning text-sm font-semibold inline-flex items-center">
                            <i data-lucide="graduation-cap" class="w-3.5 h-3.5 mr-2"></i>
                            {{ $student->major->name ?? '-' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="flex flex-1 w-full lg:w-auto mt-6 lg:mt-0 lg:ml-6 pt-6 lg:pt-0 border-t lg:border-t-0 lg:border-l border-slate-200/60 dark:border-darkmode-400 lg:pl-6">
            <div class="grid grid-cols-2 gap-6 w-full">
                <!-- Pengumuman -->
                <div class="text-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-700 transition-colors duration-300">
                    <div class="text-2xl font-bold text-primary">{{ $announcements->count() }}</div>
                    <div class="text-xs uppercase tracking-wider text-slate-500 font-semibold mt-1">Pengumuman</div>
                    <div class="mt-2 text-[10px] text-slate-400">
                        <i data-lucide="alert-circle" class="w-3 h-3 inline mr-1"></i>
                        {{ $newAnnouncements }} baru
                    </div>
                </div>

                <!-- Jadwal Hari Ini -->
                <div class="text-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-darkmode-700 transition-colors duration-300">
                    <div class="text-2xl font-bold text-success">{{ $todayScheduleCount }}</div>
                    <div class="text-xs uppercase tracking-wider text-slate-500 font-semibold mt-1">Jadwal Hari Ini</div>
                    <div class="mt-2 text-[10px] text-slate-400">
                        <i data-lucide="clock" class="w-3 h-3 inline mr-1"></i>
                        {{ $todayDate }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-8">
        <div class="box border border-slate-200/60 dark:border-darkmode-400 shadow-sm">
            <!-- Header -->
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mr-3">
                        <i data-lucide="calendar-days" class="w-5 h-5 text-primary"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Jadwal Pelajaran Hari Ini</h2>
                        <div class="text-sm text-slate-500">{{ $todayDate }}</div>
                    </div>
                </div>
                <div class="ml-auto px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-medium">
                    Total: {{ $todayScheduleCount }} Jadwal
                </div>
            </div>

            <!-- Jadwal List -->
            <div class="p-5">
                @if($todaySchedules->count() > 0)
                    <div class="space-y-5">
                        @foreach($todaySchedules as $schedule)
                        @php
                            $now = \Carbon\Carbon::now();

                            $start = \Carbon\Carbon::parse($schedule->start_date);
                            $end = \Carbon\Carbon::parse($schedule->end_date);

                            $isNow = $now->between($start, $end);
                            $isFinished = $now->gt($end);
                        @endphp

                            <div class="flex items-center p-4 rounded-xl border
                                {{ $isNow ? 'border-success/30 bg-success/5 hover:bg-success/10' : 'border-slate-200 dark:border-darkmode-600 hover:border-primary/30 hover:bg-primary/5' }}
                                transition-all duration-300 group cursor-pointer">
                                <div class="w-16 h-16 rounded-lg bg-white dark:bg-darkmode-600 flex flex-col items-center justify-center
                                    border {{ $isNow ? 'border-success/20' : 'border-slate-200 dark:border-darkmode-500' }} mr-4 shadow-sm">
                                    <span class="text-sm font-bold {{ $isNow ? 'text-success' : 'text-slate-700 dark:text-slate-300' }}">
                                        {{ \Carbon\Carbon::parse($schedule->start_date)->format('H:i') }}
                                    </span>
                                    <span class="text-xs text-slate-500 mt-1">
                                        {{ \Carbon\Carbon::parse($schedule->end_date)->format('H:i') }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="font-bold text-lg text-slate-500 dark:text-slate-100">
                                            {{ $schedule->subject->name }}
                                        </div>
                                        <div class="ml-3 px-3 py-1 rounded-full
                                        {{ $isNow
                                            ? 'bg-success/20 text-success'
                                            : ($isFinished
                                                ? 'bg-slate-200 dark:bg-darkmode-700 text-slate-500'
                                                : 'bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-400')
                                        }}
                                        text-xs font-semibold">

                                        <i data-lucide="{{ $isNow ? 'play-circle' : ($isFinished ? 'check-circle' : 'clock') }}"
                                            class="w-3 h-3 mr-1 inline"></i>

                                        {{ $isNow ? 'Berlangsung' : ($isFinished ? 'Selesai' : 'Selanjutnya') }}
                                    </div>

                                    </div>
                                    <div class="flex items-center mt-2 text-sm text-slate-600 dark:text-slate-400">
                                        <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                                        {{ $schedule->teacher->user->name ?? 'Guru' }}
                                    </div>
                                </div>
                                <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400 group-hover:text-primary transition-colors"></i>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 text-slate-500">
                        <i data-lucide="calendar-x2" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
                        <div class="text-lg">Tidak ada jadwal pelajaran hari ini</div>
                        <div class="text-sm mt-2">Nikmati hari libur Anda!</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pengumuman -->
    <div class="intro-y col-span-12 lg:col-span-4">
        <div class="box h-full border border-slate-200/60 dark:border-darkmode-400 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400 bg-warning/5">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-warning/10 flex items-center justify-center mr-3">
                        <i data-lucide="megaphone" class="w-5 h-5 text-warning"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-slate-800 dark:text-slate-100">Pengumuman</h2>
                        <div class="text-sm text-slate-500">Update terbaru</div>
                    </div>
                </div>
                <div class="ml-auto">
                    <div class="px-3 py-1 rounded-full bg-warning/20 text-warning text-sm font-medium">
                        {{ $newAnnouncements }} Baru
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="p-5 relative">
                @if($announcements->count() > 0)
                    <div class="absolute left-7 top-8 bottom-8 w-0.5 bg-slate-200/60 dark:bg-darkmode-400/60"></div>

                    @foreach($announcements as $announcement)
                        @php
                            $isNew = $announcement->created_at->gt(\Carbon\Carbon::now()->subDay());
                        @endphp
                        <div class="relative flex items-start mb-6 group cursor-pointer">
                            <div class="w-5 h-5 rounded-full {{ $isNew ? 'bg-warning' : 'bg-slate-300' }} border-3 border-white dark:border-darkmode-700 z-10 mt-0.5 shadow-sm group-hover:scale-110 transition-transform"></div>
                            <div class="ml-5 flex-1 p-3 rounded-lg bg-white dark:bg-darkmode-600 border border-slate-200 dark:border-darkmode-500 group-hover:border-{{ $isNew ? 'warning' : 'primary' }}/30 transition-colors">
                                <div class="font-bold text-slate-800 dark:text-slate-100">{{ $announcement->title }}</div>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">
                                    {{ $announcement->description }}
                                </p>
                                <div class="flex items-center mt-3 pt-3 border-t border-slate-100 dark:border-darkmode-400">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-500 mr-2"></i>
                                    <span class="text-xs text-slate-500">{{ $announcement->created_at->diffForHumans() }}</span>
                                    @if($isNew)
                                        <span class="ml-auto px-2 py-0.5 rounded-full bg-warning/10 text-warning text-xs font-semibold">Baru</span>
                                    @else
                                        <span class="ml-auto px-2 py-0.5 rounded-full bg-slate-100 dark:bg-darkmode-700 text-slate-600 dark:text-slate-400 text-xs">Umum</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-12 text-slate-500">
                        <i data-lucide="bell-off" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
                        <div class="text-lg">Belum ada pengumuman</div>
                    </div>
                @endif

                <!-- Footer Button -->
                <a href="{{ route('student.announcements') }}" class="btn btn-outline-primary w-full mt-6 flex items-center justify-center">
                    <i data-lucide="list" class="w-4 h-4 mr-2"></i>
                    Lihat Semua Pengumuman
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
