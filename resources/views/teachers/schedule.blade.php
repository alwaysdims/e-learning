@extends('teachers.layouts.main', ['title' => 'Jadwal Mengajar'])

@section('content')
<div class="grid grid-cols-12 gap-6 mt-5">

    <!-- Filter & Search Bar -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-2">
        <form method="GET" class="flex flex-wrap gap-2 w-full sm:w-auto">

            {{-- Search --}}
            <input type="text" name="search"
                value="{{ request('search') }}"
                class="form-control w-56 box"
                placeholder="Cari mapel / kelas">

            {{-- Filter Kelas --}}
            <select name="class_id" class="form-select w-56 box">
                <option value="">Semua Kelas</option>
                @foreach($classes as $id => $name)
                    <option value="{{ $id }}" {{ request('class_id') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>

            {{-- Hari ini --}}
            <label class="flex items-center gap-2">
                <input type="checkbox" name="today" class="form-check-input" value="1"
                    {{ request('today') ? 'checked' : '' }}>
                Hari ini
            </label>

            <button class="btn btn-primary">Filter</button>
        </form>
    </div>


    <!-- Table Jadwal -->
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-5">
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th>MATA PELAJARAN</th>
                    <th>KELAS</th>
                    <th class="text-center">HARI</th>
                    <th class="text-center">WAKTU</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                <tr class="intro-x">
                    <td>
                        <span class="font-medium text-primary">
                            {{ $schedule->subject->name }}
                        </span>
                    </td>
                    <td class="text-slate-500">
                        {{ $schedule->classRoom->name }}
                    </td>
                    <td class="text-center">
                        <span class="px-3 py-1 rounded-full bg-pending/10 text-pending font-medium">
                            {{ $schedule->day }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center">
                            <i data-lucide="clock" class="w-4 h-4 mr-2 text-slate-500"></i>
                            {{ $schedule->start_date }} - {{ $schedule->end_date }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-8 text-slate-500">
                        Tidak ada jadwal
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="intro-y col-span-12 mt-5">
            {{ $schedules->links() }}
        </div>

    </div>

</div>
@endsection
