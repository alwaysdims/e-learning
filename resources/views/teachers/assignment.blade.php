@extends('teachers.layouts.main',['title' => 'Assignments'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="text-center">
            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#insert-modal"
                class="btn btn-primary shadow-md mr-2">Add Task</a>
        </div>
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <form action="{{ route('teacher.assignments') }}" method="GET">
                <div class="w-56 relative text-slate-500">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control w-56 box pr-10" placeholder="Cari judul tugas...">
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
        </div>
    </div>

    <div class="intro-y col-span-12">
        <div class="overflow-x-auto">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">NO</th>
                        <th class="text-center whitespace-nowrap">TASK TITLE</th>
                        <th class="text-center whitespace-nowrap">TYPE</th>
                        <th class="text-center whitespace-nowrap">QUESTIONS</th>
                        <th class="text-center whitespace-nowrap">MAX SCORE</th>
                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $index => $task)
                        <tr class="intro-x">
                            <td class="text-center">{{ $tasks->firstItem() + $index }}</td>
                            <td class="text-center font-medium">{{ $task->title }}</td>
                            <td class="text-center">
                                <span class="badge {{ $task->type == 'essay' ? 'badge-warning' : ($task->type == 'multiple_choice' ? 'badge-success' : 'badge-primary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->type)) }}
                                </span>
                            </td>
                            <td class="text-center">{{ $task->total_questions }}</td>
                            <td class="text-center">{{ $task->max_score }}</td>
                            <td class="table-report__action">
                                <div class="flex justify-center items-center gap-3">

                                    {{-- DETAIL --}}
                                    <a class="flex items-center text-slate-600 hover:text-info"
                                       href="{{ route('assignments.show', $task->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-eye w-4 h-4 mr-1">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        Detail
                                    </a>

                                    {{-- PUBLISHED --}}
                                    <a class="flex items-center text-success hover:text-success/80"
                                       href="{{ route('teacher.assignment.publishedShow', $task->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-upload-cloud w-4 h-4 mr-1">
                                            <path d="M16 16l-4-4-4 4"></path>
                                            <path d="M12 12v9"></path>
                                            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 4 16.3"></path>
                                        </svg>
                                        Publish
                                    </a>

                                    {{-- EDIT --}}
                                    <a class="flex items-center text-primary"
                                       href="javascript:;"
                                       data-tw-toggle="modal"
                                       data-tw-target="#edit-modal-{{ $task->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-edit w-4 h-4 mr-1">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </a>

                                    {{-- DELETE --}}
                                    <a class="flex items-center text-danger"
                                       href="javascript:;"
                                       data-tw-toggle="modal"
                                       data-tw-target="#delete-confirmation-modal-{{ $task->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trash-2 w-4 h-4 mr-1">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                        Delete
                                    </a>

                                </div>
                            </td>

                        </tr>

                        <!-- Edit Modal -->
                        <div id="edit-modal-{{ $task->id }}" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="font-medium text-base mr-auto">Update Task</h2>
                                    </div>
                                    <form action="{{ route('assignments.update', $task->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                            <div class="col-span-12">
                                                <label class="form-label">Task Title</label>
                                                <input type="text" name="title" class="form-control"
                                                    value="{{ $task->title }}" required placeholder="Judul tugas">
                                            </div>
                                            <div class="col-span-12">
                                                <label class="form-label">Description (optional)</label>
                                                <textarea name="description" class="form-control" rows="3"
                                                    placeholder="Instruksi atau deskripsi tugas">{{ $task->description }}</textarea>
                                            </div>
                                            <div class="col-span-12 sm:col-span-6">
                                                <label class="form-label">Total Questions</label>
                                                <input type="number" name="total_questions" class="form-control"
                                                    value="{{ $task->total_questions }}" min="1" required>
                                            </div>
                                            <div class="col-span-12 sm:col-span-6">
                                                <label class="form-label">Type</label>
                                                <select name="type" class="form-select" required>
                                                    <option value="Essay" {{ $task->type == 'Essay' ? 'selected' : '' }}>Essay</option>
                                                    <option value="Pilihan Ganda" {{ $task->type == 'Pilihan Ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                                                    <option value="Campuran" {{ $task->type == 'Campuran' ? 'selected' : '' }}>Campuran</option>
                                                </select>
                                            </div>
                                            <div class="col-span-12">
                                                <label class="form-label">Max Score</label>
                                                <input type="number" step="0.01" name="max_score" class="form-control"
                                                    value="{{ $task->max_score }}" required placeholder="Skor maksimal">
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
                        <div id="delete-confirmation-modal-{{ $task->id }}" class="modal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body p-10 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            class="text-danger mx-auto">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                        </svg>
                                        <div class="mt-5 text-xl">Are you sure?</div>
                                        <div class="text-slate-500 mt-2">Delete task "{{ $task->title }}"?</div>
                                    </div>
                                    <div class="modal-footer text-center">
                                        <button type="button" data-tw-dismiss="modal"
                                            class="btn btn-outline-secondary w-24 mr-3">Cancel</button>
                                        <form action="{{ route('assignments.destroy', $task->id) }}"
                                            method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-24">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8">No task found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            {{ $tasks->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>

<!-- Insert Modal (Add Task) -->
<div id="insert-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add Task</h2>
            </div>
            <form action="{{ route('assignments.store') }}" method="POST">
                @csrf
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label class="form-label">Task Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Judul tugas" required>
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">Description (optional)</label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Instruksi atau deskripsi tugas"></textarea>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Total Questions</label>
                        <input type="number" name="total_questions" class="form-control" min="1" required placeholder="Jumlah soal">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Essay">Essay</option>
                            <option value="Pilihan Ganda">Pilihan Ganda</option>
                            <option value="Campuran">Campuran</option>
                        </select>
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">Max Score</label>
                        <input type="number" step="0.01" name="max_score" class="form-control" required placeholder="Skor maksimal">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal"
                        class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="submit" class="btn btn-primary w-20">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
