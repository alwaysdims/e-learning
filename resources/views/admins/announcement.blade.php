@extends('admins.layouts.main',['title' => 'Announcements'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="text-center">
            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#insert-modal"
                class="btn btn-primary shadow-md mr-2">Add Announcement</a>
        </div>
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <form action="{{ route('admin.announcements.index') }}" method="GET">
                <div class="w-56 relative text-slate-500">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-56 box pr-10"
                        placeholder="Search title or content...">
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
        </div>
    </div>

    <div class="intro-y col-span-12">
        <div class="overflow-x-auto">
            <table class="table table-report -mt-2 whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">NO</th>
                        <th class="text-center whitespace-nowrap">TITLE</th>
                        <th class="text-center whitespace-nowrap">POSTED BY</th>
                        <th class="text-center whitespace-nowrap">DATE</th>
                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $index => $announcement)
                        <tr class="intro-x">
                            <td class="text-center">{{ $announcements->firstItem() + $index }}</td>
                            <td class="text-left px-4">{{ Str::limit($announcement->title, 50) }}</td>
                            <td class="text-center">{{ $announcement->user?->name ?? 'Unknown' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($announcement->created_at)->format(' D, d M Y | h:i A ') }}</td>
                            <td class="table-report__action">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3 text-success" href="javascript:;"
                                        data-tw-toggle="modal" data-tw-target="#detail-modal-{{ $announcement->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-eye w-4 h-4 mr-1">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        Detail
                                    </a>

                                    <a class="flex items-center mr-3 text-primary" href="javascript:;"
                                        data-tw-toggle="modal" data-tw-target="#edit-modal-{{ $announcement->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-edit w-4 h-4 mr-1">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg> Edit
                                    </a>
                                    <a class="flex items-center text-danger" href="javascript:;"
                                        data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal-{{ $announcement->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-trash-2 w-4 h-4 mr-1">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <!-- Detail Modal -->
                        <div id="detail-modal-{{ $announcement->id }}" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="font-medium text-base mr-auto">Detail Announcement</h2>
                                    </div>

                                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                        <div class="col-span-6">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control"
                                                value="{{ $announcement->title }}" readonly>
                                        </div>

                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Created At</label>
                                            <input type="text" class="form-control"
                                                value="{{ \Carbon\Carbon::parse($announcement->created_at)->format('d M Y h:i A') }}" readonly>
                                        </div>
                                        <div class="col-span-12">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" rows="6" readonly>{{ $announcement->description }}</textarea>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" data-tw-dismiss="modal"
                                            class="btn btn-outline-secondary w-20">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Edit Modal -->
                        <div id="edit-modal-{{ $announcement->id }}" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="font-medium text-base mr-auto">Update Announcement</h2>
                                    </div>
                                    <form action="{{ route('admin.announcements.update', $announcement->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                            <div class="col-span-12">
                                                <label class="form-label">Title</label>
                                                <input type="text" name="title" class="form-control"
                                                    value="{{ $announcement->title }}" placeholder="Masukkan judul pengumuman" required>
                                            </div>
                                            <div class="col-span-12">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="6" placeholder="Tulis isi pengumuman di sini..." required>{{ $announcement->description }}</textarea>
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
                        <div id="delete-confirmation-modal-{{ $announcement->id }}" class="modal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body p-10 text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" class="text-danger mx-auto">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                        </svg>
                                        <div class="mt-5 text-xl">Are you sure?</div>
                                        <div class="text-slate-500 mt-2">Delete announcement "{{ $announcement->title }}"?</div>
                                    </div>
                                    <div class="modal-footer text-center">
                                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-3">Cancel</button>
                                        <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-24">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8">No announcement found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            {{ $announcements->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>

<!-- Insert Modal (Add Announcement) -->
<div id="insert-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add Announcement</h2>
            </div>
            <form action="{{ route('admin.announcements.store') }}" method="POST">
                @csrf
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul pengumuman" required>
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="8" placeholder="Tulis isi pengumuman secara lengkap di sini..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
                    <button type="submit" class="btn btn-primary w-20">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('[data-tw-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function () {
            setTimeout(() => {
                document.querySelectorAll('#insert-modal .tom-select, .modal .tom-select').forEach(el => {
                    if (!el.tomselect) {
                        new TomSelect(el);
                    }
                });
            }, 300);
        });
    });
</script>
@endsection
