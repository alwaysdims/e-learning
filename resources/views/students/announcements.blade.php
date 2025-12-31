@extends('students.layouts.main',['title' => 'Announcements'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">

    <!-- Filter & Search Bar -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
        <form method="GET" action="{{ route('student.announcements') }}" class="flex flex-wrap gap-2 w-full sm:w-auto">
            {{-- Search --}}
            <input type="text" name="search" value="{{ request('search') }}" class="form-control w-full sm:w-64 box"
                placeholder="Cari judul atau isi pengumuman...">

            {{-- Hari ini --}}
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="today" value="1" class="form-check-input"
                    {{ request('today') ? 'checked' : '' }}>
                <span class="text-slate-700">Hari ini</span>
            </label>

            <button type="submit" class="btn btn-primary">
                <i data-lucide="filter" class="w-4 h-4 mr-1"></i> Filter
            </button>

            @if(request()->hasAny(['search', 'today']))
            <a href="{{ route('student.announcements') }}" class="btn btn-outline-secondary">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Table Pengumuman -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-5">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">JUDUL</th>
                    <th class="whitespace-nowrap">DIBUAT OLEH</th>
                    <th class="text-center whitespace-nowrap">TANGGAL</th>
                    <th class="whitespace-nowrap">ISI PENGUMUMAN</th>
                    <th class="whitespace-nowrap">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $announcement)
                <tr class="intro-x hover:bg-slate-50 dark:hover:bg-darkmode-700 transition">
                    <td class="font-medium">
                        <div class="max-w-xs truncate">{{ $announcement->title }}</div>
                        @if($announcement->created_at->isToday())
                        <span
                            class="inline-block px-2 py-1 text-xs rounded-full bg-danger/10 text-danger font-medium mt-1">
                            Baru
                        </span>
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-slate-200 mr-2">
                                <div
                                    class="w-full h-full bg-primary/20 flex items-center justify-center text-xs font-bold text-primary">
                                    {{ Str::upper(Str::limit($announcement->user->name ?? 'Admin', 2, '')) }}
                                </div>
                            </div>
                            <div>
                                <div class="font-medium">{{ $announcement->user->name ?? 'Admin' }}</div>
                                <div class="text-xs text-slate-500">
                                    {{ ucfirst($announcement->user->role ?? 'admin') }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center text-sm">
                        {{ $announcement->created_at->format('d M Y') }}<br>
                        <span class="text-slate-500">{{ $announcement->created_at->format('H:i') }}</span>
                    </td>
                    <td class="max-w-md">
                        <div class="text-slate-600 text-sm line-clamp-3">
                            {{ \Illuminate\Support\Str::limit($announcement->description ?? '-', 50) }}
                        </div>
                    </td>
                    <td class="whitespace-nowrap">
                        <button type="button" data-tw-toggle="modal"
                            data-tw-target="#detailmodal-{{ $announcement->id }}"
                            class="btn btn-primary btn-sm hover:opacity-90 transition-opacity duration-200">
                            <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Detail
                        </button>

                        <!-- Modal Detail -->
                        <div id="detailmodal-{{ $announcement->id }}" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content rounded-lg shadow-xl">

                                    <!-- Modal Header -->
                                    <div class="modal-header bg-gradient-to-r from-primary to-primary-dark text-white p-5 rounded-t-lg">
                                        <h2 class="font-semibold text-xl flex items-center gap-2">
                                            <i data-lucide="megaphone" class="w-6 h-6"></i>
                                            Detail Pengumuman
                                        </h2>
                                        <button data-tw-dismiss="modal" class="text-white hover:bg-white/10 p-1 rounded transition-colors">
                                            <i data-lucide="x" class="w-5 h-5"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="modal-body p-6">

                                        <!-- Header dengan judul -->
                                        <div class="mb-6">
                                            <div class="flex items-start justify-between mb-3">
                                                <h3 class="text-2xl font-bold text-slate-800 leading-tight">
                                                    {{ $announcement->title }}
                                                </h3>
                                            </div>

                                            <!-- Informasi meta -->
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-slate-600 bg-slate-50 p-3 rounded-lg">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                                                        <i data-lucide="user" class="w-4 h-4 text-primary"></i>
                                                    </div>
                                                    <span class="font-medium">{{ $announcement->user->name ?? 'Admin' }}</span>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 bg-emerald-50 rounded-full flex items-center justify-center">
                                                        <i data-lucide="calendar" class="w-4 h-4 text-emerald-600"></i>
                                                    </div>
                                                    <span>{{ $announcement->created_at->format('d F Y') }}</span>
                                                    <span class="text-slate-400">•</span>
                                                    <span>{{ $announcement->created_at->format('H:i') }} WIB</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Garis pemisah -->
                                        <div class="border-t border-slate-200 my-4"></div>

                                        <!-- Konten pengumuman -->
                                        <div class="mb-6">
                                            <div class="flex items-center gap-2 mb-3 text-slate-700">
                                                <i data-lucide="align-left" class="w-5 h-5 text-primary"></i>
                                                <h4 class="font-semibold text-lg">Isi Pengumuman</h4>
                                            </div>
                                            <div class="bg-slate-50 p-5 rounded-lg border border-slate-200">
                                                <div class="text-slate-700 leading-relaxed whitespace-pre-line text-base">
                                                    {{ $announcement->description ?? '-' }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Info tambahan -->
                                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                                            <div class="flex items-center gap-2 text-blue-700 mb-2">
                                                <i data-lucide="info" class="w-5 h-5"></i>
                                                <span class="font-medium">Informasi</span>
                                            </div>
                                            <p class="text-blue-600 text-sm">
                                                Pengumuman ini diterbitkan oleh {{ $announcement->user->role ?? 'administrator' }}
                                                dan dapat dilihat oleh semua pengguna yang berhak.
                                            </p>
                                        </div>

                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="modal-footer bg-slate-50 p-5 rounded-b-lg border-t border-slate-200">
                                        <div class="flex justify-end gap-3">
                                            <button type="button" data-tw-dismiss="modal"
                                                class="btn btn-outline-secondary hover:bg-slate-100 px-5 py-2.5 rounded-lg font-medium transition-colors duration-200">
                                                <i data-lucide="x" class="w-4 h-4 mr-2"></i> Tutup
                                            </button>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-12 text-slate-500">
                        <i data-lucide="bell-off" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
                        <div>Belum ada pengumuman.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="intro-y col-span-12 mt-6 flex justify-left">
            {{ $announcements->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
