@extends('teachers.layouts.main',['title' => 'Publish Tugas'])

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
                    | <small>Tipe: {{ ucfirst(str_replace('_', ' ', $task->type)) }}</small>
                    | <small>Soal: {{ $task->total_questions }}, Skor Max: {{ $task->max_score }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <!-- Kiri: Kembali -->
        <div class="flex items-center">
            <a href="{{ route('teacher.assignments') }}"
                class="btn btn-outline-secondary shadow-md">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                Kembali
            </a>
        </div>

        <div class="hidden md:block mx-auto text-slate-500"></div>

        <!-- Kanan: Search + Publish -->
        <div class="flex items-center gap-2 w-full sm:w-auto mt-3 sm:mt-0">
            <form action="{{ route('teacher.assignment.publishedShow', $task->id) }}" method="GET">
                <div class="w-56 relative text-slate-500">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control w-56 box pr-10" placeholder="Cari nama kelas...">
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
                Publish ke Kelas
            </a>
        </div>
    </div>

    <!-- Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2 whitespace-nowrap">
            <thead>
                <tr>
                    <th class="text-center whitespace-nowrap">NO</th>
                    <th class="text-center whitespace-nowrap">KELAS</th>
                    <th class="text-center whitespace-nowrap">MULAI</th>
                    <th class="text-center whitespace-nowrap">DEADLINE</th>
                    <th class="text-center whitespace-nowrap">DURASI (MENIT)</th>
                    <th class="text-center whitespace-nowrap">DIPUBLISH</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($publishedClasses as $index => $publish)
                <tr class="intro-x">
                    <td class="text-center">{{ $publishedClasses->firstItem() + $index }}</td>
                    <td class="text-center font-medium">{{ $publish->classRoom->name }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($publish->start_time)->format('d M Y H:i') }}
                    </td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($publish->deadline)->format('d M Y H:i') }}
                    </td>
                    <td class="text-center">{{ $publish->duration }} menit</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($publish->published_at)->format('d M Y H:i') }}
                    </td>
                    <td class="table-report__action">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3 text-primary" href="javascript:;"
                                data-tw-toggle="modal" data-tw-target="#edit-modal-{{ $publish->id }}">
                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit
                            </a>
                            <a class="flex items-center text-danger" href="javascript:;"
                                data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal-{{ $publish->id }}">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Unpublish
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div id="edit-modal-{{ $publish->id }}" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="font-medium text-base mr-auto">Edit Publish Tugas</h2>
                            </div>
                            <form action="{{ route('teacher.assignment.publishedUpdate', $publish->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                    <div class="col-span-12">
                                        <label class="form-label">Kelas</label>
                                        <input type="text" class="form-control" value="{{ $publish->classRoom->name }}" disabled>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <label class="form-label">Waktu Mulai</label>
                                        <input type="datetime-local" name="start_time" class="form-control"
                                            value="{{ \Carbon\Carbon::parse($publish->start_time)->format('Y-m-d\TH:i') }}" required>
                                    </div>
                                    <div class="col-span-12 sm:col-span-6">
                                        <label class="form-label">Deadline</label>
                                        <input type="datetime-local" name="deadline" class="form-control"
                                            value="{{ \Carbon\Carbon::parse($publish->deadline)->format('Y-m-d\TH:i') }}" required>
                                    </div>
                                    <div class="col-span-12">
                                        <label class="form-label">Durasi Pengerjaan (menit)</label>
                                        <input type="number" name="duration" class="form-control"
                                            value="{{ $publish->duration }}" min="1" required>
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
                <div id="delete-confirmation-modal-{{ $publish->id }}" class="modal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-10 text-center">
                                <i data-lucide="alert-circle" class="w-16 h-16 text-danger mx-auto mb-4"></i>
                                <div class="text-xl">Hapus publish?</div>
                                <div class="text-slate-500 mt-2">
                                    Tugas "{{ $task->title }}" akan dihapus dari kelas "{{ $publish->classRoom->name }}"
                                </div>
                            </div>
                            <div class="modal-footer text-center">
                                <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-3">Cancel</button>
                                <form action="{{ route('teacher.assignment.publishedDestroy', $publish->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-24">Unpublish</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8">Tugas belum dipublish ke kelas manapun.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            {{ $publishedClasses->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>

<!-- Insert Modal (Publish ke Kelas Baru) -->
<div id="insert-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Publish Tugas ke Kelas</h2>
            </div>
            <form action="{{ route('teacher.assignment.publishedStore', $task->id) }}" method="POST">
                @csrf
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label class="form-label">Pilih Kelas yang Anda Ampu</label>
                        <select name="class_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($teacherClasses as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @if($teacherClasses->isEmpty())
                            <div class="text-warning mt-2 text-sm">
                                Anda belum memiliki jadwal mengajar. Hubungi admin.
                            </div>
                        @endif
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Waktu Mulai</label>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Deadline</label>
                        <input type="datetime-local" name="deadline" class="form-control" required>
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">Durasi Pengerjaan (menit)</label>
                        <input type="number" name="duration" class="form-control" min="1" required placeholder="Contoh: 60">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="submit" class="btn btn-primary w-20">Publish</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
