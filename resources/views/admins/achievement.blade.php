@extends('admins.layouts.main',['title' => 'Achievements'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="text-center">
            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#insert-modal"
                class="btn btn-primary shadow-md mr-2">Add Achievement</a>
        </div>
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <form action="{{ route('admin.achievements.index') }}" method="GET">
                <div class="w-56 relative text-slate-500">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-56 box pr-10"
                        placeholder="Cari judul, tipe, atau deskripsi...">
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
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">NO</th>
                        <th class="text-center whitespace-nowrap">ICON</th>
                        <th class="text-center whitespace-nowrap">TITLE</th>
                        <th class="text-center whitespace-nowrap">TYPE</th>
                        <th class="text-center whitespace-nowrap">TARGET VALUE</th>
                        <th class="text-center whitespace-nowrap">DESCRIPTION</th>
                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($achievements as $index => $achievement)
                        <tr class="intro-x">
                            <td class="text-center">{{ $achievements->firstItem() + $index }}</td>
                            <td class="text-center">
                                @if($achievement->icon)
                                    <img src="{{ asset('storage/' . $achievement->icon) }}" alt="Icon" class="w-12 h-12 object-cover rounded">
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center font-medium ">{{ $achievement->title }}</td>
                            <td class="text-center">{{ $achievement->type }}</td>
                            <td class="text-center">{{ $achievement->target_value }}</td>
                            <td class="text-center max-w-xs">{{ Str::limit($achievement->description, 80) }}</td>
                            <td class="table-report__action">
                                <div class="flex justify-center items-center">
                                    <a class="flex items-center mr-3 text-primary" href="javascript:;"
                                        data-tw-toggle="modal" data-tw-target="#edit-modal-{{ $achievement->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-edit w-4 h-4 mr-1">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg> Edit
                                    </a>
                                    <a class="flex items-center text-danger" href="javascript:;"
                                        data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal-{{ $achievement->id }}">
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

                        <!-- Edit Modal -->
                        <div id="edit-modal-{{ $achievement->id }}" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="font-medium text-base mr-auto">Update Achievement</h2>
                                    </div>
                                    <form action="{{ route('admin.achievements.update', $achievement->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                            <div class="col-span-12">
                                                <label class="form-label">Title</label>
                                                <input type="text" name="title" class="form-control" value="{{ $achievement->title }}"
                                                    placeholder="Contoh: Juara 1 Lomba Matematika" required>
                                            </div>
                                            <div class="col-span-12 sm:col-span-6">
                                                <label class="form-label">Type</label>
                                                <select name="type" class="tom-select w-full" data-placeholder="Select Type">
                                                    <option value="">-- None --</option>

                                                    <option value="Task completed"
                                                        {{ old('type', $achievement->type ?? '') == 'Task completed' ? 'selected' : '' }}>
                                                        Task Completed
                                                    </option>

                                                    <option value="Forum posts"
                                                        {{ old('type', $achievement->type ?? '') == 'Forum posts' ? 'selected' : '' }}>
                                                        Forum Posts
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-span-12 sm:col-span-6">
                                                <label class="form-label">Target Value</label>
                                                <input type="number" name="target_value" class="form-control" value="{{ $achievement->target_value }}"
                                                    placeholder="Contoh: 1 untuk juara 1" min="0" required>
                                            </div>
                                            <div class="col-span-12">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan prestasi ini secara singkat..." required>{{ $achievement->description }}</textarea>
                                            </div>
                                            <div class="col-span-12">
                                                <label class="form-label">Icon (ganti jika diperlukan)</label>
                                                <input type="file" name="icon" class="form-control" accept="image/*">
                                                @if($achievement->icon)
                                                    <div class="mt-3">
                                                        <img src="{{ asset('storage/' . $achievement->icon) }}" alt="Current Icon" class="w-20 h-20 object-cover rounded">
                                                        <p class="text-xs text-slate-500 mt-1">Icon saat ini</p>
                                                    </div>
                                                @endif
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

                        <!-- Delete Modal -->
                        <div id="delete-confirmation-modal-{{ $achievement->id }}" class="modal" tabindex="-1">
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
                                        <div class="text-slate-500 mt-2">Delete achievement "{{ $achievement->title }}"?</div>
                                    </div>
                                    <div class="modal-footer text-center">
                                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-3">Cancel</button>
                                        <form action="{{ route('admin.achievements.destroy', $achievement->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-24">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8">No achievement found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            {{ $achievements->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>

<!-- Insert Modal (Add Achievement) -->
<div id="insert-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add Achievement</h2>
            </div>
            <form action="{{ route('admin.achievements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Si paling gercep" required>
                    </div>

                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Type</label>
                        <select name="type" class="tom-select w-full" data-placeholder="Select Teacher (Optional)">
                            <option value="">-- None --</option>
                            <option value="Task completed">Task Complated</option>
                            <option value="Forum posts">Forum posts</option>
                        </select>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Target Value</label>
                        <input type="number" name="target_value" class="form-control" placeholder="Contoh: 1 untuk juara 1" min="0" required>
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan prestasi ini secara singkat..." required></textarea>
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">Icon</label>
                        <input type="file" name="icon" class="form-control" accept="image/*" required>
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
