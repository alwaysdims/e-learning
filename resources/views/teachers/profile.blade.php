@extends('teachers.layouts.main', ['title' => 'Profile - ' . Auth::user()->name])

@section('content')
<form action="{{ route('teacher.profileStore') }}" method="POST">
    @csrf
    <div class="p-5 box mt-3">
        <div class="grid grid-cols-12 gap-x-5">
            <div class="col-span-12 xl:col-span-6">
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="mt-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                </div>
                <div class="mt-3">
                    <label class="form-label">NIP</label>
                    <input type="text" class="form-control" value="{{ $teacher->nip ?? '-' }}" disabled>
                </div>
                <div class="mt-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <input type="text" class="form-control" value="{{ $teacher->subject->name ?? '-' }}" disabled>
                </div>
            </div>
            <div class="col-span-12 xl:col-span-6">
                <div class="mt-3 xl:mt-0">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="no_telp" class="form-control" value="{{ $teacher->no_telp }}"
                        placeholder="Contoh: 08123456789">
                </div>
                <div class="mt-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="3"
                        placeholder="Alamat lengkap">{{ $teacher->address }}</textarea>
                </div>
                <div class="mt-3">
                    <label class="form-label">Password Baru <span class="text-slate-500 text-xs">(kosongkan jika tidak
                            ingin ganti)</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                </div>
                <div class="mt-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Ketik ulang password">
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="btn btn-primary w-32">Simpan Perubahan</button>
        </div>
    </div>
</form> <!-- JANGAN LUPA BUKA FORM DI ATAS! -->
@endsection
