@extends('teachers.layouts.main',['title' => 'Pengumuman'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">

    <!-- Filter & Search Bar -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
        <form method="GET" action="{{ route('teacher.announcements') }}" class="flex flex-wrap gap-2 w-full sm:w-auto">
            {{-- Search --}}
            <input type="text" name="search"
                value="{{ request('search') }}"
                class="form-control w-full sm:w-64 box"
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
                <a href="{{ route('teacher.announcements') }}" class="btn btn-outline-secondary">
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
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $announcement)
                <tr class="intro-x hover:bg-slate-50 dark:hover:bg-darkmode-700 transition">
                    <td class="font-medium">
                        <div class="max-w-xs truncate">{{ $announcement->title }}</div>
                        @if($announcement->created_at->isToday())
                            <span class="inline-block px-2 py-1 text-xs rounded-full bg-danger/10 text-danger font-medium mt-1">
                                Baru
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-slate-200 mr-2">
                                <div class="w-full h-full bg-primary/20 flex items-center justify-center text-xs font-bold text-primary">
                                    {{ Str::upper(Str::limit($announcement->user->name, 2, '')) }}
                                </div>
                            </div>
                            <div>
                                <div class="font-medium">{{ $announcement->user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $announcement->user->role }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center text-sm">
                        {{ $announcement->created_at->format('d M Y') }}<br>
                        <span class="text-slate-500">{{ $announcement->created_at->format('H:i') }}</span>
                    </td>
                    <td class="max-w-md">
                        <div class="text-slate-600 text-sm line-clamp-3">
                            {{ $announcement->description ?? '-' }}
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
            {{ $announcements->links() }}
        </div>
    </div>
</div>
@endsection
