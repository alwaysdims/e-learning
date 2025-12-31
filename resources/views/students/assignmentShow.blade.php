@extends('students.layouts.main',['title' => 'Detail Tugas - ' . $task->title])

@section('content')
<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto text-slate-800 dark:text-slate-100">
        Detail Tugas / Ujian
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('student.assignments') }}" class="btn btn-outline-secondary shadow-sm mr-2 flex items-center">
            <i data-lucide="chevron-left" class="w-4 h-4 mr-2"></i> Kembali ke Daftar Tugas
        </a>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Informasi Tugas -->
    <div class="intro-y col-span-12 lg:col-span-8">
        <div class="box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Informasi Tugas</h2>
                <div class="flex items-center mt-3 sm:mt-0">
                    <div class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold">
                        {{ $task->subject->name ?? '-' }}
                    </div>
                    <div class="ml-3">
                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                </div>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <div class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-2">
                            Judul Tugas
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-darkmode-700 rounded-lg border border-slate-200 dark:border-darkmode-600">
                            <div class="flex items-center">
                                <i data-lucide="file-text" class="w-4 h-4 text-primary mr-2"></i>
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $task->title }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-2">
                            Jenis Tugas
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-darkmode-700 rounded-lg border border-slate-200 dark:border-darkmode-600">
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
                        <div class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-2">
                            Jumlah Soal
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-darkmode-700 rounded-lg border border-slate-200 dark:border-darkmode-600">
                            <div class="flex flex-wrap gap-3">
                                @if($pgCount > 0)
                                    <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium">
                                        {{ $pgCount }} Pilihan Ganda
                                    </span>
                                @endif
                                @if($essayCount > 0)
                                    <span class="px-3 py-1 rounded-full bg-success/10 text-success text-xs font-medium">
                                        {{ $essayCount }} Essay
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-2">
                            Durasi Pengerjaan
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-darkmode-700 rounded-lg border border-slate-200 dark:border-darkmode-600">
                            <div class="flex items-center">
                                <i data-lucide="clock" class="w-4 h-4 text-warning mr-2"></i>
                                <span class="font-bold text-primary text-lg">{{ $duration }} Menit</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:col-span-2">
                        <div class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-2">
                            Deskripsi Tugas
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-darkmode-700 rounded-lg border border-slate-200 dark:border-darkmode-600 min-h-[80px]">
                            <p class="text-slate-600 dark:text-slate-300">
                                {{ $task->description ?? 'Tidak ada deskripsi tambahan.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="mt-8">
                    <div class="font-semibold text-slate-700 dark:text-slate-300 uppercase text-[11px] tracking-wider mb-4">
                        Timeline Tugas
                    </div>

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
                                {{ \Carbon\Carbon::parse($taskClass->start_time)->format('d M Y, H:i') }} WIB
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
                                {{ \Carbon\Carbon::parse($taskClass->deadline)->format('d M Y, H:i') }} WIB
                            </div>
                        </div>
                    </div>

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

    <!-- CTA Kerjakan Tugas -->
    <div class="intro-y col-span-12 lg:col-span-4">
        <div class="box flex flex-col">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">Aksi Tugas</h2>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-center items-center text-center">
                @if($canStart)
                    <a href="{{ route('student.assignment.start', $task->id) }}"
                        class="btn btn-success w-full max-w-xs shadow-lg hover:shadow-xl transition-all duration-300 py-4 text-md text-white font-semibold">
                        <i data-lucide="play-circle" class="w-8 h-8 mr-3"></i>
                        Kerjakan Tugas Sekarang
                    </a>
                    <p class="text-slate-500 text-sm mt-4">
                        Pastikan Anda siap sebelum memulai. Tidak dapat kembali setelah dimulai.
                    </p>
                @else
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-slate-100 dark:bg-darkmode-700 flex items-center justify-center">
                            <i data-lucide="lock" class="w-12 h-12 text-slate-400"></i>
                        </div>
                        <p class="text-lg font-medium text-slate-700 dark:text-slate-300">
                            {{ $statusText }}
                        </p>
                        <p class="text-sm text-slate-500 mt-2">
                            @if(\Carbon\Carbon::now()->lt($taskClass->start_time))
                                Tugas belum dimulai
                            @elseif(\Carbon\Carbon::now()->gt($taskClass->deadline))
                                Waktu pengerjaan telah habis
                            @elseif($studentTask?->submitted_at)
                                Anda sudah menyelesaikan tugas ini
                            @else
                                Tugas terkunci atau sedang dikerjakan
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
