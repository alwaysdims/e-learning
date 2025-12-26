@extends('teachers.layouts.main',['title' => 'Materials'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="text-center">
            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#insert-modal"
                class="btn btn-primary shadow-md mr-2">Add Material</a>
        </div>
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <form action="{{ route('teacher.materials.index') }}" method="GET">
                <div class="w-56 relative text-slate-500">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control w-56 box pr-10" placeholder="Cari judul atau mata pelajaran...">
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

    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="text-center whitespace-nowrap">NO</th>
                    <th class="text-center whitespace-nowrap">TITLE</th>
                    <th class="text-center whitespace-nowrap">SUBJECT</th>
                    <th class="text-center whitespace-nowrap">FILE</th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $index => $material)
                    <tr class="intro-x">
                        <td class="text-center">{{ $materials->firstItem() + $index }}</td>
                        <td class="text-center">{{ $material->title }}</td>
                        <td class="text-center">{{ $material->subject->name ?? '-' }}</td>
                        <td class="text-center">
                            @if($material->content)
                                <a href="{{ Storage::url($material->content) }}" target="_blank" class="text-primary">
                                    {{ basename($material->content) }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">
                                {{-- EDIT --}}
                                <a class="flex items-center mr-3 text-primary" href="javascript:;"
                                    data-tw-toggle="modal" data-tw-target="#edit-modal-{{ $material->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-edit w-4 h-4 mr-1">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Edit
                                </a>

                                {{-- PUBLISH --}}
                                <a class="flex items-center mr-3 text-success"
                                    href="{{ route('teacher.materials.published', $material->id) }}"
                                    onclick="return confirm('Publish materi ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-upload-cloud w-4 h-4 mr-1">
                                        <path d="M16 16l-4-4-4 4"></path>
                                        <path d="M12 12v9"></path>
                                        <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path>
                                    </svg>
                                    Publish
                                </a>

                                {{-- DELETE --}}
                                <a class="flex items-center text-danger" href="javascript:;"
                                    data-tw-toggle="modal"
                                    data-tw-target="#delete-confirmation-modal-{{ $material->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
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
                    <div id="edit-modal-{{ $material->id }}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="font-medium text-base mr-auto">Update Material</h2>
                                </div>
                                <form action="{{ route('teacher.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $material->title }}" required>
                                        </div>
                                        <div class="col-span-12">
                                            <label class="form-label">File (kosongkan jika tidak ingin ganti)</label>
                                            <input type="file" name="content" class="form-control"
                                                accept=".pdf,.doc,.docx,.ppt,.pptx,.txt">
                                            @if($material->content)
                                                <small class="text-slate-500 mt-1 block">Current file: {{ basename($material->content) }}</small>
                                            @endif
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
                    <div id="delete-confirmation-modal-{{ $material->id }}" class="modal" tabindex="-1">
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
                                    <div class="text-slate-500 mt-2">Delete material "{{ $material->title }}"?</div>
                                </div>
                                <div class="modal-footer text-center">
                                    <button type="button" data-tw-dismiss="modal"
                                        class="btn btn-outline-secondary w-24 mr-3">Cancel</button>
                                    <form action="{{ route('teacher.materials.destroy', $material->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-24">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8">No material found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            {{ $materials->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>

<!-- Insert Modal (Add Material) -->
<div id="insert-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add Material</h2>
            </div>
            <form action="{{ route('teacher.materials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required placeholder="Judul materi">
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">File Materi (PDF, DOCX, PPTX, TXT)</label>
                        <input type="file" name="content" class="form-control" required
                            accept=".pdf,.doc,.docx,.ppt,.pptx,.txt">
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
