@extends('teachers.layouts.main',['title' => 'Assignment details'])

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto text-slate-800 dark:text-slate-100">
        Detail Soal Ujian
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('teacher.assignments') }}" class="btn btn-outline-secondary shadow-sm mr-2 flex items-center">
            <i data-lucide="chevron-left" class="w-4 h-4 mr-2"></i> Kembali ke Daftar Ujian
        </a>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Informasi Tugas -->
    <div class="intro-y col-span-12 lg:col-span-8">
        <div class="box">
            <div
                class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Informasi Tugas</h2>
                <div class="flex items-center mt-3 sm:mt-0">
                    <div class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold">
                        {{ $task->subject->name ?? 'Mata Pelajaran Tidak Diketahui' }}
                    </div>
                    <div
                        class="ml-3 w-10 h-6 flex items-center justify-center bg-success/20 text-success rounded-full text-xs font-semibold">
                        Aktif
                    </div>
                </div>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <div
                            class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-2">
                            Judul Tugas</div>
                        <div
                            class="p-3 bg-slate-50 dark:bg-darkmode-700 rounded-lg border border-slate-200 dark:border-darkmode-600">
                            <div class="flex items-center">
                                <i data-lucide="file-text" class="w-4 h-4 text-primary mr-2"></i>
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $task->title }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div
                            class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-2">
                            Jenis Ujian</div>
                        <div
                            class="p-3 bg-slate-50 dark:bg-darkmode-700 rounded-lg border border-slate-200 dark:border-darkmode-600">
                            <div class="flex items-center">
                                <i data-lucide="layers" class="w-4 h-4 text-success mr-2"></i>
                                <span class="font-medium text-slate-800 dark:text-slate-200">
                                    {{ ucfirst(str_replace('_', ' ', $task->type)) }}
                                    @if($task->type == 'mixed')
                                    (Pilihan Ganda & Essay)
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div
                            class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-2">
                            Kelas Ditugaskan</div>
                        <div
                            class="p-3 bg-slate-50 dark:bg-darkmode-700 rounded-lg border border-slate-200 dark:border-darkmode-600">
                            <div class="flex flex-wrap gap-2">
                                @forelse($task->taskClasses as $tc)
                                <div class="flex items-center px-3 py-1 rounded-full bg-primary/10 text-primary">
                                    <i data-lucide="users" class="w-3 h-3 mr-1"></i>
                                    <span class="text-xs font-medium">{{ $tc->classRoom->name }}</span>
                                </div>
                                @empty
                                <span class="text-slate-500 text-xs">Belum dipublish ke kelas manapun</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div
                            class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-2">
                            Durasi Pengerjaan</div>
                        <div
                            class="p-3 bg-slate-50 dark:bg-darkmode-700 rounded-lg border border-slate-200 dark:border-darkmode-600">
                            <div class="flex items-center">
                                <i data-lucide="clock" class="w-4 h-4 text-warning mr-2"></i>
                                <span class="font-bold text-primary text-lg">
                                    {{ $duration }} Menit
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:col-span-2">
                        <div
                            class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-2">
                            Deskripsi Tugas</div>
                        <div
                            class="p-3 bg-slate-50 dark:bg-darkmode-700 rounded-lg border border-slate-200 dark:border-darkmode-600 min-h-[80px]">
                            <p class="text-slate-600 dark:text-slate-300">
                                {{ $task->description ?? 'Tidak ada deskripsi.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="mt-8">
                    <div
                        class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-4">
                        Timeline Ujian</div>

                    @if($firstPublish)
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                                <i data-lucide="calendar" class="w-4 h-4 text-white"></i>
                            </div>
                            <div class="absolute top-8 left-4 h-8 w-px bg-slate-200 dark:bg-darkmode-600"></div>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium text-slate-800 dark:text-slate-200">Dimulai</div>
                            <div class="text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($firstPublish->start_time)->format('d M Y, H:i') }} WIB
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center mt-4">
                        <div class="relative">
                            <div class="w-8 h-8 rounded-full bg-warning flex items-center justify-center">
                                <i data-lucide="clock" class="w-4 h-4 text-white"></i>
                            </div>
                            <div class="absolute top-8 left-4 h-8 w-px bg-slate-200 dark:bg-darkmode-600"></div>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium text-slate-800 dark:text-slate-200">Batas Waktu</div>
                            <div class="text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($firstPublish->deadline)->format('d M Y, H:i') }} WIB
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center text-slate-500 py-8">
                        Belum dipublish ke kelas manapun.
                    </div>
                    @endif

                    <div class="flex items-center mt-4">
                        <div class="w-8 h-8 rounded-full bg-success flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-4 h-4 text-white"></i>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium text-slate-800 dark:text-slate-200">Pengumpulan</div>
                            <div class="text-sm text-slate-500">Sistem otomatis</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aksi & Statistik -->
    <div class="intro-y col-span-12 lg:col-span-4">
        <!-- Panel Aksi -->
        <div class="box mb-6">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Manajemen Soal</h2>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    {{-- Jika type = Pilihan Ganda --}}
                    @if($task->type === 'Pilihan Ganda')
                    <a href="{{ route('teacher.assignment.management.pg', $task->id) }}"
                        class="flex items-center p-3 bg-primary/5 hover:bg-primary/10 rounded-lg transition duration-200">
                        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center mr-3">
                            <i data-lucide="list-checks" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-slate-800 dark:text-slate-200">
                                Manajemen Pilihan Ganda
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $multipleChoiceCount }} soal tersedia
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                    </a>

                    {{-- Jika type = Essay --}}
                    @elseif($task->type === 'Essay')
                    <a href="{{ route('teacher.assignment.management.essay', $task->id) }}"
                        class="flex items-center p-3 bg-success/5 hover:bg-success/10 rounded-lg transition duration-200">
                        <div class="w-10 h-10 rounded-full bg-success/20 flex items-center justify-center mr-3">
                            <i data-lucide="file-text" class="w-5 h-5 text-success"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-slate-800 dark:text-slate-200">
                                Manajemen Essay
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $essayCount }} soal tersedia
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                    </a>

                    {{-- Jika type = Campuran --}}
                    @elseif($task->type === 'Campuran')
                    {{-- Pilihan Ganda --}}
                    <a href="{{ route('teacher.assignment.management.pg', $task->id) }}"
                        class="flex items-center p-3 bg-primary/5 hover:bg-primary/10 rounded-lg transition duration-200 mb-3">
                        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center mr-3">
                            <i data-lucide="list-checks" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-slate-800 dark:text-slate-200">
                                Manajemen Pilihan Ganda
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $multipleChoiceCount }} soal tersedia
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                    </a>

                    {{-- Essay --}}
                    <a href="{{ route('teacher.assignment.management.essay', $task->id) }}"
                        class="flex items-center p-3 bg-success/5 hover:bg-success/10 rounded-lg transition duration-200">
                        <div class="w-10 h-10 rounded-full bg-success/20 flex items-center justify-center mr-3">
                            <i data-lucide="file-text" class="w-5 h-5 text-success"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-slate-800 dark:text-slate-200">
                                Manajemen Essay
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $essayCount }} soal tersedia
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                    </a>
                    @endif


                    <a href="{{ route('teacher.assignment.monitor', $task->id) }}"
                        class="flex items-center p-3 bg-warning/5 hover:bg-warning/10 rounded-lg transition duration-200">
                        <div class="w-10 h-10 rounded-full bg-warning/20 flex items-center justify-center mr-3">
                            <i data-lucide="eye" class="w-5 h-5 text-warning"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-slate-800 dark:text-slate-200">Pantau Siswa</div>
                            <div class="text-xs text-slate-500">{{ $activeStudents }} siswa aktif</div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
