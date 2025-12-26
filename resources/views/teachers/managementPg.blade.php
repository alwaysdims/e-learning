@extends('teachers.layouts.main',['title' => 'Manajemen Soal Pilihan Ganda'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <!-- Header Tugas -->
    <div class="intro-y col-span-12">
        <div class="alert alert-primary show mb-4">
            <div class="flex items-center">
                <i data-lucide="book-open" class="w-6 h-6 mr-3"></i>
                <div>
                    <strong>{{ $task->title }}</strong><br>
                    <small>Mata Pelajaran: {{ $task->subject->name ?? '-' }}</small>
                    | <small>Tipe: Pilihan Ganda | Skor Maks: {{ $task->max_score }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <!-- Kiri: Kembali -->
        <div class="flex items-center">
            <a href="{{ route('assignments.show', $task->id) }}"
                class="btn btn-outline-secondary shadow-md">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Kembali
            </a>
        </div>

        <div class="hidden md:block mx-auto text-slate-500"></div>

        <!-- Kanan: Search + Tambah Soal -->
        <div class="flex items-center gap-2 w-full sm:w-auto mt-3 sm:mt-0">
            <form action="{{ route('teacher.assignment.management.pg', $task->id) }}" method="GET">
                <div class="w-56 relative text-slate-500">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control w-56 box pr-10" placeholder="Cari pertanyaan...">
                    <button type="submit" class="absolute inset-y-0 right-0 my-auto mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-search w-4 h-4">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
            </form>

            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#insert-modal"
                class="btn btn-primary shadow-md">
                Tambah Soal PG
            </a>
        </div>
    </div>

    <!-- Data List -->
    <div class="intro-y col-span-12">
        <div class="overflow-x-auto">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">NO</th>
                        <th class="whitespace-nowrap">PERTANYAAN</th>
                        <th class="text-center whitespace-nowrap">GAMBAR</th>
                        <th class="text-center whitespace-nowrap">JAWABAN BENAR</th>
                        <th class="text-center whitespace-nowrap">SKOR</th>
                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questions as $index => $question)
                    <tr class="intro-x">
                        <td class="text-center">{{ $questions->firstItem() + $index }}</td>
                        <td class="px-4">
                            <div class="max-w-md truncate">{{ $question->question }}</div>
                        </td>
                        <td class="text-center">
                            @if($question->picture)
                                <button type="button" class="text-primary" data-tw-toggle="modal"
                                    data-tw-target="#image-modal-{{ $question->id }}">
                                    <i data-lucide="image" class="w-5 h-5"></i>
                                </button>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center font-medium uppercase">{{ $question->correct_answer }}</td>
                        <td class="text-center font-medium">{{ $question->score }}</td>
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">
                                <button type="button" class="flex items-center mr-3 text-info"
                                    data-tw-toggle="modal" data-tw-target="#detail-modal-{{ $question->id }}">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Detail
                                </button>

                                <button type="button" class="flex items-center mr-3 text-primary"
                                    data-tw-toggle="modal" data-tw-target="#edit-modal-{{ $question->id }}">
                                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit
                                </button>
                                <button type="button" class="flex items-center text-danger"
                                    data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal-{{ $question->id }}">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Image Modal -->
                    @if($question->picture)
                    <div id="image-modal-{{ $question->id }}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="font-medium text-base mr-auto">Gambar Soal</h2>
                                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary">✕</button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ Storage::url($question->picture) }}" alt="Gambar Soal"
                                        class="max-w-full rounded shadow-md mx-auto">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Detail Modal -->
                    <div id="detail-modal-{{ $question->id }}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg my-6 md:my-12">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5">
                                    <div class="flex items-center">
                                        <div>
                                            <h2 class="font-medium text-lg text-slate-800 dark:text-slate-100">Detail Soal Pilihan Ganda</h2>
                                        </div>
                                    </div>
                                    <button data-tw-dismiss="modal" class="btn btn-outline-secondary py-1 px-2 ml-auto">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body p-5 mt-2 mb-2 gap-3 space-y-6">
                                    <!-- Pertanyaan Section -->
                                    <div class="p-4 rounded-lg border border-slate-200 dark:border-darkmode-600 bg-slate-50 dark:bg-darkmode-700">
                                        <div class="flex items-center mb-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                                <i data-lucide="help-circle" class="w-4 h-4 text-primary"></i>
                                            </div>
                                            <label class="font-semibold text-slate-700 dark:text-slate-300">Pertanyaan</label>
                                        </div>
                                        <div class="mt-2 p-3 bg-white dark:bg-darkmode-600 rounded border border-slate-200 dark:border-darkmode-500">
                                            <p class="text-slate-700 dark:text-slate-300 leading-relaxed">
                                                {{ $question->question }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Gambar Section -->
                                    @if($question->picture)
                                    <div class="p-4 rounded-lg border border-slate-200 dark:border-darkmode-600 bg-slate-50 dark:bg-darkmode-700">
                                        <div class="flex items-center mb-3">
                                            <div class="w-8 h-8 rounded-full bg-warning/10 flex items-center justify-center mr-3">
                                                <i data-lucide="image" class="w-4 h-4 text-warning"></i>
                                            </div>
                                            <label class="font-semibold text-slate-700 dark:text-slate-300">Gambar</label>
                                        </div>
                                        <div class="mt-2 flex justify-center">
                                            <div class="relative group">
                                                <img src="{{ Storage::url($question->picture) }}"
                                                    alt="Gambar Soal"
                                                    class="max-h-72 rounded-lg border-2 border-slate-200 dark:border-darkmode-500 shadow-sm group-hover:shadow-md transition-shadow duration-300">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300 rounded-lg"></div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Jawaban Section -->
                                    <div class="p-4 rounded-lg border border-slate-200 dark:border-darkmode-600 bg-slate-50 dark:bg-darkmode-700">
                                        <div class="flex items-center mb-3">
                                            <div class="w-8 h-8 rounded-full bg-success/10 flex items-center justify-center mr-3">
                                                <i data-lucide="list-checks" class="w-4 h-4 text-success"></i>
                                            </div>
                                            <label class="font-semibold text-slate-700 dark:text-slate-300">Pilihan Jawaban</label>
                                        </div>
                                        <div class="mt-2">
                                            <div class="space-y-3">
                                                @foreach(['a','b','c','d','e'] as $key)
                                                    @php
                                                        $answer = $question->{'answer_'.$key};
                                                    @endphp
                                                    @if($answer)
                                                    <div class="flex items-start p-3 rounded-lg border {{ $question->correct_answer == $key ? 'border-success/30 bg-success/5' : 'border-slate-200 dark:border-darkmode-500' }} transition-colors duration-200">
                                                        <div class="w-8 h-8 rounded-full {{ $question->correct_answer == $key ? 'bg-success/20 text-success' : 'bg-slate-100 dark:bg-darkmode-600 text-slate-600' }} flex items-center justify-center mr-3 mt-0.5 font-bold">
                                                            {{ strtoupper($key) }}
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-slate-700 dark:text-slate-300 {{ $question->correct_answer == $key ? 'font-medium' : '' }}">
                                                                {{ $answer }}
                                                            </p>
                                                            @if($question->correct_answer == $key)
                                                            <div class="flex items-center mt-2">
                                                                <div class="w-5 h-5 rounded-full bg-success/20 flex items-center justify-center mr-2">
                                                                    <i data-lucide="check" class="w-3 h-3 text-success"></i>
                                                                </div>
                                                                <span class="text-sm text-success font-semibold">Jawaban Benar</span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Skor Section -->
                                    <div class="p-4 rounded-lg border border-slate-200 dark:border-darkmode-600 bg-slate-50 dark:bg-darkmode-700">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-warning/10 flex items-center justify-center mr-3">
                                                    <i data-lucide="star" class="w-4 h-4 text-warning"></i>
                                                </div>
                                                <label class="font-semibold text-slate-700 dark:text-slate-300">Skor </label>
                                            </div>
                                            <div class="font-bold ml-2 text-primary">
                                                {{ $question->score }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer flex items-center justify-end p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                                    <button type="button" data-tw-dismiss="modal"
                                        class="btn btn-outline-secondary w-32 flex items-center justify-center">
                                        <i data-lucide="x" class="w-4 h-4 mr-2"></i> Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    <div id="edit-modal-{{ $question->id }}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="font-medium text-base mr-auto">Edit Soal Pilihan Ganda</h2>
                                </div>
                                <form action="{{ route('teacher.assignment.management.pg.update', ['id' => $task->id, 'question' => $question->id]) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                        <div class="col-span-12">
                                            <label class="form-label">Pertanyaan</label>
                                            <textarea name="question" class="form-control" rows="4" required>{{ $question->question }}</textarea>
                                        </div>
                                        <div class="col-span-12">
                                            <label class="form-label">Gambar (kosongkan jika tidak diganti)</label>
                                            <input type="file" name="picture" class="form-control" accept="image/*">
                                            @if($question->picture)
                                                <small class="text-slate-500 mt-1 block">Gambar saat ini: <a href="{{ Storage::url($question->picture) }}" target="_blank">Lihat</a></small>
                                            @endif
                                        </div>

                                        <!-- Pilihan Jawaban -->
                                        @php
                                            $options = ['a' => $question->answer_a, 'b' => $question->answer_b, 'c' => $question->answer_c, 'd' => $question->answer_d, 'e' => $question->answer_e];
                                        @endphp
                                        @foreach(['a','b','c','d','e'] as $key)
                                        <div class="col-span-12">
                                            <label class="form-label">Jawaban {{ strtoupper($key) }} @if($key == 'a' || $key == 'b') <span class="text-danger">*</span> @endif</label>
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <input type="radio" name="correct_answer" value="{{ $key }}"
                                                        {{ $question->correct_answer == $key ? 'checked' : '' }} required>
                                                </div>
                                                <input type="text" name="answer_{{ $key }}" class="form-control"
                                                    value="{{ $options[$key] ?? '' }}"
                                                    placeholder="Jawaban {{ strtoupper($key) }}" {{ $key == 'a' || $key == 'b' ? 'required' : '' }}>
                                            </div>
                                        </div>
                                        @endforeach

                                        <div class="col-span-12">
                                            <label class="form-label">Skor Soal</label>
                                            <input type="number" name="score" class="form-control"
                                                value="{{ $question->score }}" min="1" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                                        <button type="submit" class="btn btn-primary w-20">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div id="delete-confirmation-modal-{{ $question->id }}" class="modal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-10 text-center">
                                    <i data-lucide="alert-circle" class="w-16 h-16 text-danger mx-auto mb-4"></i>
                                    <div class="text-xl">Hapus soal?</div>
                                    <div class="text-slate-500 mt-2">Soal pilihan ganda ini akan dihapus permanen.</div>
                                </div>
                                <div class="modal-footer text-center">
                                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-3">Cancel</button>
                                    <form action="{{ route('teacher.assignment.management.pg.destroy', ['id' => $task->id, 'question' => $question->id]) }}"
                                        method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-24">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8">Belum ada soal pilihan ganda.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            {{ $questions->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>

<!-- Insert Modal (Tambah Soal Pilihan Ganda) -->
<div id="insert-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Tambah Soal Pilihan Ganda</h2>
            </div>
            <form action="{{ route('teacher.assignment.management.pg.store', $task->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label class="form-label">Pertanyaan</label>
                        <textarea name="question" class="form-control" rows="4" placeholder="Tuliskan pertanyaan..." required></textarea>
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">Gambar Pendukung (opsional)</label>
                        <input type="file" name="picture" class="form-control" accept="image/*">
                    </div>

                    <!-- Pilihan Jawaban A-E -->
                    @foreach(['a','b','c','d','e'] as $key)
                    <div class="col-span-12">
                        <label class="form-label">Jawaban {{ strtoupper($key) }} @if($key == 'a' || $key == 'b') <span class="text-danger">*</span> @endif</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input type="radio" name="correct_answer" value="{{ $key }}" required>
                            </div>
                            <input type="text" name="answer_{{ $key }}" class="form-control"
                                placeholder="Jawaban {{ strtoupper($key) }}" {{ $key == 'a' || $key == 'b' ? 'required' : '' }}>
                        </div>
                    </div>
                    @endforeach

                    <div class="col-span-12">
                        <label class="form-label">Skor Soal</label>
                        <input type="number" name="score" class="form-control" min="1" placeholder="Contoh: 5" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="submit" class="btn btn-primary w-20">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
