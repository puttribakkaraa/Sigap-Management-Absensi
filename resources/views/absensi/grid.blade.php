<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Sigap Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .table-absensi th, .table-absensi td { border: 1px solid #000; padding: 5px; text-align: center; }
        .sticky-col { position: sticky; left: 0; background: white; z-index: 10; border-right: 2px solid #000 !important; }
        .bg-sunday { background-color: #ff0000 !important; color: white; }
        /* Pastikan kolom tanggal punya lebar yang konsisten */
.table-absensi th:nth-child(n+6), 
.table-absensi td:nth-child(n+6) {
    min-width: 32px;
    max-width: 35px;
}

/* Hilangkan padding default agar konten jam tidak terpotong */
.table-absensi td {
    padding: 2px 0 !important;
}

/* Style khusus untuk font jam agar tetap terbaca meski sangat kecil */
.font-mono {
    font-family: 'Courier New', Courier, monospace;
    letter-spacing: -0.5px;
}
    </style>
</head>
<body class="p-5 bg-gray-100">
    <div class="bg-white p-6 rounded shadow-lg overflow-x-auto">
        
        <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold uppercase">ABSENSI SIGAP MANAGEMENT</h2>
                <p class="text-sm text-gray-500">Monitoring Kehadiran Karyawan</p>
            </div>
            
            <div class="flex items-center gap-2">
                <form action="/absensi" method="GET" class="flex gap-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari NPK..." 
                           class="border border-gray-300 px-3 py-2 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold">Cari</button>
                    @if(request('search'))
                        <a href="/absensi" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-bold">Reset</a>
                    @endif
                </form>
                <a href="/dashboard" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-md transition flex items-center">
                    ← Kembali ke Dashboard
                </a>
                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-200 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Karyawan
                </button>
            </div>
        </div>
        @if(request('status_filter') == 'tidak_hadir')
    <div class="mb-4 bg-red-100 border-l-4 border-red-500 p-4 rounded shadow-sm flex justify-between items-center">
        <div>
            <h3 class="text-red-800 font-bold uppercase text-sm">Mode Peringatan: Daftar Belum Absen Hari Ini</h3>
            <p class="text-red-600 text-xs">Menampilkan {{ count($employees) }} karyawan yang belum melakukan scan/input absensi.</p>
        </div>
        <a href="/absensi" class="bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-red-700 transition">
            Tampilkan Semua Data
        </a>
    </div>
@endif
        <table class="table-absensi w-full text-[10px] border-collapse shadow-sm">
            <thead>
                <tr class="bg-gray-200">
                    <th rowspan="2">No</th>
                    <th rowspan="2">NPK</th>
                    <th rowspan="2" class="sticky-col">Full Name</th>
                    <th rowspan="2">Dept</th> 
                    <th rowspan="2">Title</th>
                    <th colspan="{{ count($dateRange) }}">{{ $startOfMonth->format('F Y') }}</th>
                </tr>
                <tr>
                    {{-- PERBAIKAN: Header angka tanggal diletakkan di sini --}}
                    @foreach($dateRange as $date)
                        <th class="{{ $date->isWeekend() ? 'bg-sunday' : '' }}">
                            {{ $date->format('d') }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php $totalHadirHariIni = 0; @endphp
                @foreach($employees as $index => $emp)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $emp->npk }}</td>
                    <td class="sticky-col text-left font-bold">{{ $emp->name }}</td>
                    <td>{{ $emp->department }}</td>
                    <td>{{ $emp->title }}</td>
                    
                    @foreach($dateRange as $date)
                        @php
                            $attendance = $emp->attendances->where('date', $date->format('Y-m-d'))->first();
                            if($attendance && $date->isToday()) {
                                $totalHadirHariIni++;
                            }
                        @endphp
                        
                       <td class="{{ $date->isWeekend() ? 'bg-sunday' : '' }} p-0">
    @if($attendance)
        <div class="flex flex-col items-center justify-center py-1 leading-none">
            {{-- Baris Atas: Ikon Status --}}
            @if($attendance->status == 'Hadir')
                <span class="text-green-600 font-bold text-[11px]">✔</span>
            @elseif($attendance->status == 'Sakit')
                <span class="text-blue-600 font-bold text-[11px]">●</span>
            @elseif($attendance->status == 'Izin')
                <span class="text-yellow-500 font-bold text-[11px]">▲</span>
            @elseif($attendance->status == 'Cuti')
                <span class="text-green-500 font-bold text-[11px]">●</span>
            @endif

            {{-- Baris Bawah: Jam Masuk (Hanya jika Hadir) --}}
            @if($attendance->status == 'Hadir')
                <span class="text-[7px] font-mono text-gray-500 mt-0.5 tracking-tighter">
                    {{-- Mengambil jam dari created_at atau kolom jam anda --}}
                    {{ date('H:i', strtotime($attendance->created_at)) }}
                </span>
            @endif
        </div>
    @else
        @if(!$date->isSunday() && $date->lte(now()))
            <div class="flex items-center justify-center py-1">
                <span class="text-red-600 font-bold text-[11px]">X</span>
            </div>
        @endif
    @endif
</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex gap-4 mt-4 text-[10px] font-bold uppercase p-2 bg-gray-50 border border-gray-200 rounded">
            <span>✔ Hadir</span>
            <span class="text-blue-600">● Sakit</span>
            <span class="text-yellow-600">▲ Izin</span>
            <span class="text-green-500">● Cuti</span>
            <span class="text-red-600">X Tidak Hadir</span>
        </div>

        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="border-r border-blue-200 pr-4">
                    <p class="text-[10px] text-blue-600 font-bold uppercase tracking-wider">Karyawan Terdaftar</p>
                    <p class="text-xl font-black text-blue-900">{{ count($employees) }} <span class="text-sm font-normal">Orang</span></p>
                </div>
                <div class="border-r border-blue-200 pr-4">
                    <p class="text-[10px] text-green-600 font-bold uppercase tracking-wider">✔️ Hadir</p>
                    <p class="text-xl font-black text-green-900">
                        {{ $employees->map(fn($e) => $e->attendances->where('date', now()->toDateString())->where('status', 'Hadir'))->collapse()->count() }}
                    </p>
                </div>
                <div class="border-r border-blue-200 pr-4">
                    <p class="text-[10px] text-yellow-600 font-bold uppercase tracking-wider">▲ Izin</p>
                    <p class="text-xl font-black text-yellow-900">
                        {{ $employees->map(fn($e) => $e->attendances->where('date', now()->toDateString())->where('status', 'Izin'))->collapse()->count() }}
                    </p>
                </div>
                <div class="border-r border-blue-200 pr-4">
                    <p class="text-[10px] text-blue-500 font-bold uppercase tracking-wider">● Sakit</p>
                    <p class="text-xl font-black text-blue-800">
                        {{ $employees->map(fn($e) => $e->attendances->where('date', now()->toDateString())->where('status', 'Sakit'))->collapse()->count() }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] text-green-500 font-bold uppercase tracking-wider">● Cuti</p>
                    <p class="text-xl font-black text-green-900">
                        {{ $employees->map(fn($e) => $e->attendances->where('date', now()->toDateString())->where('status', 'Cuti'))->collapse()->count() }}
                    </p>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-blue-200 flex justify-between items-center">
                <p class="text-sm font-bold text-blue-900">
                    Total Laporan Hari Ini: 
                    <span class="bg-blue-600 text-white px-3 py-1 rounded-full ml-2">
                        {{ $totalHadirHariIni }} Orang
                    </span>
                </p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider italic">*Data otomatis diperbarui dari input mandiri</p>
            </div>
        </div>
    </div>

    <div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Tambah Karyawan Baru</h3>
                <button onclick="closeModal()" class="text-white hover:text-gray-200">&times;</button>
            </div>
            <form action="{{ route('employees.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700">NPK</label>
                    <input type="text" name="npk" required class="w-full mt-1 p-2 border border-gray-300 rounded-lg outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full mt-1 p-2 border border-gray-300 rounded-lg outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Departemen</label>
                    <select name="department" required class="w-full mt-1 p-2 border border-gray-300 rounded-lg outline-none">
                        <option value="PURCHASING">PURCHASING</option>
                        <option value="MARKETING">MARKETING</option>
                        <option value="QUALITY ASSURANCE">QUALITY ASSURANCE</option>
                        <option value="PRODUCTION I">PRODUCTION I</option>
                        <option value="PRODUCTION II">PRODUCTION II</option>
                        <option value="PROCESS ENGINEERING">PROCESS ENGINEERING</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Jabatan (Title)</label>
                    <input type="text" name="title" placeholder="Contoh: SECT.HEAD" required class="w-full mt-1 p-2 border border-gray-300 rounded-lg outline-none">
                </div>
                <div class="pt-4 flex gap-2">
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-bold shadow-lg hover:bg-blue-700">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() { document.getElementById('modalTambah').classList.remove('hidden'); }
        function closeModal() { document.getElementById('modalTambah').classList.add('hidden'); }
    </script>
</body>
</html>