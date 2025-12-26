@extends('teachers.layouts.main',['title' => 'Manajemen Soal Essay'])

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
                    | <small>Tipe: Essay | Skor Maks: {{ $task->max_score }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <!-- Kiri: Kembali -->
        <div class="flex items-center">
            <a href="{{ route('assignments.show', $task->id) }}" class="btn btn-outline-secondary shadow-md">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Kembali
            </a>
        </div>

        <div class="hidden md:block mx-auto text-slate-500"></div>

        <!-- Kanan: Search + Tambah Soal -->
        <div class="flex items-center gap-2 w-full sm:w-auto mt-3 sm:mt-0">
            <form action="{{ route('teacher.assignment.management.essay', $task->id) }}" method="GET">
                <div class="w-56 relative text-slate-500">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-56 box pr-10"
                        placeholder="Cari pertanyaan...">
                    <button type="submit" class="absolute inset-y-0 right-0 my-auto mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-search w-4 h-4">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
            </form>

            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#insert-modal"
                class="btn btn-primary shadow-md">
                Tambah Soal Essay
            </a>
        </div>
    </div>

    <!-- Data List -->
    <div class="intro-y col-span-12 ">
        <div class="overflow-x-auto">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">NO</th>
                        <th class="whitespace-nowrap">PERTANYAAN</th>
                        <th class="text-center whitespace-nowrap">GAMBAR</th>
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

                        <td class="text-center font-medium">{{ $question->score }}</td>
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">
                                <button type="button" class="flex items-center mr-3 text-primary" href="javascript:;"
                                    data-tw-toggle="modal" data-tw-target="#edit-modal-{{ $question->id }}">
                                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit
                                </button>
                                <button type="button" class="flex items-center text-danger" href="javascript:;"
                                    data-tw-toggle="modal"
                                    data-tw-target="#delete-confirmation-modal-{{ $question->id }}">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    {{-- image modal --}}
                    @if($question->picture)
                    <div id="image-modal-{{ $question->id }}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="font-medium text-base mr-auto">Gambar Soal</h2>
                                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary">
                                        ✕
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ Storage::url($question->picture) }}" alt="Gambar Soal"
                                        class="max-w-full rounded shadow-md mx-auto">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif


                    <!-- Edit Modal -->
                    <div id="edit-modal-{{ $question->id }}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="font-medium text-base mr-auto">Edit Soal Essay</h2>
                                </div>
                                <form
                                    action="{{ route('teacher.assignment.management.essay.update', ['id' => $task->id, 'questionId' => $question->id]) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                        <div class="col-span-12">
                                            <label class="form-label">Pertanyaan</label>
                                            <textarea name="question" class="form-control" rows="4"
                                                required>{{ $question->question }}</textarea>
                                        </div>
                                        <div class="col-span-12">
                                            <label class="form-label">Gambar (kosongkan jika tidak diganti)</label>
                                            <input type="file" name="picture" class="form-control" accept="image/*">
                                            @if($question->picture)
                                            <small class="text-slate-500 mt-1 block">Gambar saat ini: <a
                                                    href="{{ Storage::url($question->picture) }}"
                                                    target="_blank">Lihat</a></small>
                                            @endif
                                        </div>
                                        <div class="col-span-12">
                                            <label class="form-label">Skor</label>
                                            <input type="number" name="score" class="form-control"
                                                value="{{ $question->score }}" min="1" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" data-tw-dismiss="modal"
                                            class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
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
                                    <div class="text-slate-500 mt-2">
                                        Soal essay ini akan dihapus permanen.
                                    </div>
                                </div>
                                <div class="modal-footer text-center">
                                    <button type="button" data-tw-dismiss="modal"
                                        class="btn btn-outline-secondary w-24 mr-3">Cancel</button>
                                    <form
                                        action="{{ route('teacher.assignment.management.essay.destroy', ['id' => $task->id, 'questionId' => $question->id]) }}"
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
                        <td colspan="5" class="text-center py-8">Belum ada soal essay.</td>
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

<!-- Insert Modal (Tambah Soal Essay) -->
<div id="insert-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Tambah Soal Essay</h2>
            </div>
            <form action="{{ route('teacher.assignment.management.essay.store', $task->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label class="form-label">Pertanyaan</label>
                        <textarea name="question" class="form-control" rows="4"
                            placeholder="Tuliskan pertanyaan essay..." required></textarea>
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">Gambar Pendukung (opsional)</label>
                        <input type="file" name="picture" class="form-control" accept="image/*">
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">Skor Soal</label>
                        <input type="number" name="score" class="form-control" min="1" placeholder="Contoh: 10"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal"
                        class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="submit" class="btn btn-primary w-20">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
