@extends('admins.layouts.main',['title' => 'Students'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="text-center">
            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#insert-modal"
                class="btn btn-primary shadow-md mr-2">Add Student</a>
        </div>
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <form action="{{ route('admin.user.student.index') }}" method="GET">
                <div class="w-56 relative text-slate-500">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-56 box pr-10"
                        placeholder="Search...">
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

    <!-- BEGIN: Data List -->
    <div class="intro-y col-span-12 ">

        <div class="overflow-x-auto ">
            <table class="table table-report -mt-2 min-w-[1200px] whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="text-center whitespace-nowrap">NO</th>
                        <th class="text-center whitespace-nowrap">USERNAME</th>
                        <th class="text-center whitespace-nowrap">NAME</th>
                        <th class="text-center whitespace-nowrap">EMAIL</th>
                        <th class="text-center whitespace-nowrap">NIS</th>
                        <th class="text-center whitespace-nowrap">CLASS</th>
                        <th class="text-center whitespace-nowrap">MAJOR</th>
                        <th class="text-center whitespace-nowrap">ADDRESS</th>
                        <th class="text-center whitespace-nowrap">BIRTHDAY</th>
                        <th class="text-center whitespace-nowrap">NO TELEPHONE</th>
                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $student)
                    <tr class="intro-x">
                        <td class="text-center">{{ $students->firstItem() + $index }}</td>
                        <td class="text-center">{{ $student->username }}</td>
                        <td class="text-center">{{ $student->name }}</td>
                        <td class="text-center">{{ $student->email }}</td>
                        <td class="text-center">{{ $student->student?->nis ?? '-' }}</td>
                        <td class="text-center">{{ $student->student?->classRoom?->name ?? '-' }}</td>
                        <td class="text-center">{{ $student->student?->major?->name ?? '-' }}</td>
                        <td class="text-center">{{ $student->student?->address ?? '-' }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($student->student?->birthday)->format('d, M Y') ?? '-' }}</td>
                        <td class="text-center">{{ $student->student?->no_telp ?? '-' }}</td>
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3 text-primary" href="javascript:;"
                                    data-tw-toggle="modal" data-tw-target="#edit-modal-{{ $student->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-edit w-4 h-4 mr-1">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal"
                                    data-tw-target="#delete-confirmation-modal-{{ $student->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-trash-2 w-4 h-4 mr-1">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path
                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                        </path>
                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                    </svg>
                                    Delete
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div id="edit-modal-{{ $student->id }}" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="font-medium text-base mr-auto">Update Student</h2>
                                </div>
                                <form action="{{ route('admin.user.student.update', $student->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $student->name }}" required>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control"
                                                value="{{ $student->username }}" required>
                                        </div>
                                        <div class="col-span-12">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ $student->email }}" required>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">NIS</label>
                                            <input type="text" name="nis" class="form-control"
                                                value="{{ $student->student?->nis }}" required>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Academic Year</label>
                                            <input type="text" name="academic_year" class="form-control"
                                                value="{{ $student->student?->academic_year }}" required>
                                        </div>

                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Birthday</label>
                                            <input type="date" name="birthday" class="form-control"
                                                value="{{ $student->student?->birthday }}">
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Profile Photo</label>
                                            <input type="file" name="profile" class="form-control" accept="image/*">
                                            @if($student->student?->profile)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $student->student->profile) }}"
                                                         alt="Profile" class="w-20 h-20 object-cover rounded-full">
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Class dengan TomSelect -->
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Class</label>
                                            <select name="class_id" class="tom-select w-full"
                                                data-placeholder="Select Class"  required >
                                                @foreach(\App\Models\ClassRoom::orderBy('name')->get() as $class)
                                                <option value="{{ $class->id }}"
                                                    {{ $student->student?->class_id == $class->id ? 'selected' : '' }}>
                                                    {{ $class->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Major dengan TomSelect -->
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Major</label>
                                            <select name="major_id" class="tom-select w-full"
                                                data-placeholder="Select Major" required>
                                                @foreach(\App\Models\Major::orderBy('name')->get() as $major)
                                                <option value="{{ $major->id }}"
                                                    {{ $student->student?->major_id == $major->id ? 'selected' : '' }}>
                                                    {{ $major->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Password (kosongkan jika tidak diganti)</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Password Confirmation</label>
                                            <input type="password" name="password_confirmation" class="form-control">
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">Address</label>
                                            <input type="text" name="address" class="form-control"
                                                value="{{ $student->student?->address }}">
                                        </div>
                                        <div class="col-span-12 sm:col-span-6">
                                            <label class="form-label">No Telp</label>
                                            <input type="text" name="no_telp" class="form-control"
                                                value="{{ $student->student?->no_telp }}">
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

                    <!-- Delete Modal -->
                    <div id="delete-confirmation-modal-{{ $student->id }}" class="modal" tabindex="-1">
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
                                    <div class="text-slate-500 mt-2">Delete student "{{ $student->name }}"?</div>
                                </div>
                                <div class="modal-footer text-center">
                                    <button type="button" data-tw-dismiss="modal"
                                        class="btn btn-outline-secondary w-24 mr-3">Cancel</button>
                                    <form action="{{ route('admin.user.student.destroy', $student->id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-24">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-8">No student found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            {{ $students->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>

<!-- Insert Modal (Add Student) -->
<div id="insert-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Add Student</h2>
            </div>
            <form action="{{ route('admin.user.student.store') }}" method="POST">
                @csrf
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="col-span-12">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" class="form-control" required>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Academic Year</label>
                        <input type="text" name="academic_year" placeholder="e.g. 2024/2025" class="form-control"
                            required>
                    </div>

                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Birthday</label>
                        <input type="date" name="birthday" class="form-control">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" name="profile" class="form-control" accept="image/*">
                    </div>

                    <!-- Class dengan TomSelect -->
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Class</label>
                        <select name="class_id" class="tom-select w-full" data-placeholder="Select Class" required>
                            <option value="">-- Select Class --</option>
                            @foreach(\App\Models\ClassRoom::orderBy('name')->get() as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Major dengan TomSelect -->
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Major</label>
                        <select name="major_id" class="tom-select w-full" data-placeholder="Select Major" required>
                            <option value="">-- Select Major --</option>
                            @foreach(\App\Models\Major::orderBy('name')->get() as $major)
                            <option value="{{ $major->id }}">{{ $major->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Password Confirmation</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">No Telp</label>
                        <input type="text" name="no_telp" class="form-control">
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
