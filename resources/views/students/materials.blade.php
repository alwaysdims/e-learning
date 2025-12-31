@extends('students.layouts.main',['title' => 'Materi Pelajaran'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">

    <div class="intro-y col-span-12 mt-2">
        <form method="GET" action="{{ route('student.materials') }}" class="box p-3 flex flex-col sm:flex-row items-center gap-3">

            {{-- Search - Menggunakan flex-1 agar memenuhi ruang sisa --}}
            <div class="relative w-full sm:flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control w-full pr-10" placeholder="Cari judul materi atau mata pelajaran...">

            </div>

            {{-- Filter Mata Pelajaran - Lebar proporsional --}}
            <select name="subject_id" class="form-select w-full sm:w-56">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>

            {{-- Group Tombol --}}
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="btn btn-primary w-full sm:w-auto">
                    <i data-lucide="filter" class="w-4 h-4 mr-2"></i> Filter
                </button>

                @if(request()->hasAny(['search', 'subject_id']))
                    <a href="{{ route('student.materials') }}"
                       class="btn btn-outline-secondary w-full sm:w-auto text-center px-4"
                       title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-4 h-4 sm:mr-0 mr-2"></i>
                        <span class="sm:hidden">Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Materi -->
    <div class="intro-y col-span-12 mt-5">

        <div class="overflow-x-auto">

        <table class="table table-report -mt-2  whitespace-nowrap">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">JUDUL MATERI</th>
                    <th class="whitespace-nowrap">MATA PELAJARAN</th>
                    <th class="whitespace-nowrap">GURU</th>
                    <th class="text-center whitespace-nowrap">DIPUBLISH</th>
                    <th class="whitespace-nowrap">FILE</th>
                    <th class="whitespace-nowrap">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $classMaterial)
                @php
                    $material = $classMaterial->learningMaterial;
                @endphp
                <tr class="intro-x hover:bg-slate-50 dark:hover:bg-darkmode-700 transition">
                    <td class="font-medium">
                        <div class="max-w-xs truncate">{{ $material->title }}</div>
                        @if(\Carbon\Carbon::parse($classMaterial->published_at)->isToday())
                            <span class="inline-block px-2 py-1 text-xs rounded-full bg-danger/10 text-danger font-medium mt-1">
                                Baru
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="font-medium">{{ $material->subject->name ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-slate-200 mr-2">
                                <div class="w-full h-full bg-primary/20 flex items-center justify-center text-xs font-bold text-primary">
                                    {{ Str::upper(Str::limit($material->teacher->user->name ?? 'Guru', 2, '')) }}
                                </div>
                            </div>
                            <div>
                                <div class="font-medium">{{ $material->teacher->user->name ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center text-sm">
                        {{ \Carbon\Carbon::parse($classMaterial->published_at)->format('d M Y') }}<br>
                        <span class="text-slate-500">{{ \Carbon\Carbon::parse($classMaterial->published_at)->format('H:i') }}</span>
                    </td>
                    <td class="max-w-md">
                        @if($material->content)
                            <div class="text-primary font-medium">
                                {{ basename($material->content) }}
                            </div>
                        @else
                            <span class="text-slate-500">-</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap">
                        @if($material->content)
                            <div class="flex gap-2 justify-center">
                                
                                <a href="{{ asset('storage/' . $material->content) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-success btn-sm text-white hover:opacity-90 transition">
                                     <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Lihat
                                 </a>

                                <!-- Download File -->
                                <a href="{{ Storage::url($material->content) }}" download
                                    class="btn btn-primary btn-sm hover:opacity-90">
                                    <i data-lucide="download" class="w-4 h-4 mr-1"></i> Download
                                </a>
                            </div>
                        @else
                            <span class="text-slate-500">Tidak ada file</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-slate-500">
                        <i data-lucide="book-open" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
                        <div>Belum ada materi yang dipublish untuk kelas Anda.</div>
                        <div class="text-sm mt-2">Hubungi guru jika ada masalah.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="intro-y col-span-12 mt-6 flex justify-left">
            {{ $materials->appends(request()->query())->links() }}
        </div>

    </div>
    </div>
</div>
@endsection
