<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Sigap Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .table-absensi th, .table-absensi td { border: 1px solid #000; padding: 5px; text-align: center; }
        .sticky-col-left { position: sticky; left: 0; background: white; z-index: 20; border-right: 2px solid #000 !important; }
        .sticky-col-right { position: sticky; right: 0; background: #f8fafc; z-index: 20; border-left: 2px solid #000 !important; }
        .bg-sunday { background-color: #ff0000 !important; color: white; }
        .table-absensi th:nth-child(n+6), .table-absensi td:nth-child(n+6) { min-width: 35px; max-width: 38px; }
        .font-mono { font-family: 'Courier New', Courier, monospace; letter-spacing: -0.5px; }
        .btn-icon { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 6px; transition: all 0.2s; cursor: pointer; }
        .btn-edit-icon { color: #eab308; }
        .btn-edit-icon:hover { background-color: #fef9c3; color: #a16207; }
        .btn-delete-icon { color: #ef4444; }
        .btn-delete-icon:hover { background-color: #fee2e2; color: #b91c1c; }
        nav[role="navigation"] svg { width: 20px; height: 20px; display: inline; }
    </style>
</head>
<body class="p-5 bg-gray-100">
    <div class="bg-white p-6 rounded shadow-lg">
        
        <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold uppercase">ABSENSI SIGAP MANAGEMENT</h2>
                <p class="text-sm text-gray-500">Monitoring Kehadiran Karyawan</p>
            </div>
            
            <div class="flex items-center gap-2">
                <form action="{{ route('absensi.index') }}" method="GET" id="perPageForm" class="flex flex-wrap md:flex-nowrap gap-1">
                    <select name="month" onchange="this.form.submit()" class="border border-gray-300 px-2 py-2 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ (request('month') ?? date('n')) == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>

                    <select name="year" onchange="this.form.submit()" class="border border-gray-300 px-2 py-2 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @php $currentYear = date('Y'); @endphp
                        @for ($y = $currentYear - 2; $y <= $currentYear + 1; $y++)
                            <option value="{{ $y }}" {{ (request('year') ?? $currentYear) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>

                    <select name="per_page" onchange="this.form.submit()" class="border border-gray-300 px-2 py-2 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 baris</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 baris</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 baris</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 baris</option>
                    </select>

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NPK/Nama..." class="border border-gray-300 px-3 py-2 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold">Cari</button>
                    
         <input type="hidden" name="start_summary" value="">
    <input type="hidden" name="end_summary" value="">
                </form>

                <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>

                <button onclick="openModal()" class="bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow-md flex items-center gap-2 text-sm transition hover:bg-blue-700">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>

        <div class="overflow-x-auto border rounded-lg">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">{{ session('success') }}</div>
            @endif

            <table class="table-absensi w-full text-[10px] border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th rowspan="2">No</th>
                        <th rowspan="2">NPK</th>
                        <th rowspan="2" class="sticky-col-left">Full Name</th>
                        <th rowspan="2">Dept</th> 
                        <th rowspan="2">Title</th>
                        <th colspan="{{ count($dateRange) }}">{{ $startOfMonth->format('F Y') }}</th>
                        <th rowspan="2" class="sticky-col-right bg-gray-200">Aksi</th>
                    </tr>
                    <tr class="bg-gray-200">
                        @foreach($dateRange as $date)
                            <th class="{{ $date->isWeekend() ? 'bg-sunday' : '' }}">{{ $date->format('d') }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $index => $emp)
                    <tr class="hover:bg-gray-50">
                        <td>{{ $employees->firstItem() + $index }}</td>
                        <td class="font-mono">{{ $emp->npk }}</td>
                        <td class="sticky-col-left text-left font-bold">{{ $emp->name }}</td>
                        <td>{{ $emp->department }}</td>
                        <td>{{ $emp->title }}</td>
                        
                        @foreach($dateRange as $date)
                            @php
                                $attendance = $emp->attendances->where('date', $date->format('Y-m-d'))->first();
                            @endphp
                            <td class="{{ $date->isWeekend() ? 'bg-sunday' : '' }} p-0">
                                @if($attendance)
                                    <div class="flex flex-col items-center justify-center py-1">
                                        <span class="font-bold">
                                            {{ $attendance->status == 'Hadir' ? '✔' : ($attendance->status == 'Sakit' ? '●' : ($attendance->status == 'Izin' ? '▲' : '■')) }}
                                        </span>
                                        @if($attendance->status == 'Hadir')
                                            <span class="text-[7px] text-gray-500 font-mono">
                                                {{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    @if(!$date->isSunday() && $date->lte(now()))
                                        <span class="text-red-600 font-bold">X</span>
                                    @endif
                                @endif
                            </td>
                        @endforeach

                        <td class="sticky-col-right">
                            <div class="flex items-center justify-center gap-1 bg-[#f8fafc] py-1">
                                <button onclick="openEditModal({{ json_encode($emp) }})" class="btn-icon btn-edit-icon"><i class="fas fa-pen-to-square text-xs"></i></button>
                                <form action="/absensi/{{ $emp->id }}" method="POST" onsubmit="return confirm('Hapus data?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-delete-icon"><i class="fas fa-trash-can text-xs"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $employees->links() }}
        </div>

        <div class="mt-8 border border-gray-300 rounded-lg overflow-hidden max-w-4xl bg-white shadow-sm">
            <div class="bg-gray-800 text-white px-4 py-3 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-xs font-bold uppercase tracking-wider">
                    Ringkasan Kehadiran ({{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})
                </div>
                
                <form action="{{ route('absensi.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <input type="hidden" name="month" value="{{ request('month') }}">
                    <input type="hidden" name="year" value="{{ request('year') }}">
                    
                    <div class="flex items-center gap-1">
                        <input type="date" name="start_summary" value="{{ $startDate }}" 
                               class="bg-gray-700 text-white border border-gray-600 rounded px-2 py-1 text-[10px] focus:outline-none">
                        <span class="text-[10px]">s/d</span>
                        <input type="date" name="end_summary" value="{{ $endDate }}" 
                               class="bg-gray-700 text-white border border-gray-600 rounded px-2 py-1 text-[10px] focus:outline-none">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-[10px] font-bold transition">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <table class="w-full text-xs">
                <thead class="bg-gray-100 border-b border-gray-300">
                    <tr class="text-center uppercase font-semibold">
                        <th class="px-4 py-2 border-r border-gray-300 text-emerald-700">✔ Hadir</th>
                        <th class="px-4 py-2 border-r border-gray-300 text-blue-600">● Sakit</th>
                        <th class="px-4 py-2 border-r border-gray-300 text-orange-600">▲ Izin</th>
                        <th class="px-4 py-2 border-r border-gray-300 text-purple-600">■ Cuti</th>
                        <th class="px-4 py-2 text-red-600">X Alpa</th>
                    </tr>
                </thead>
                <tbody class="text-center font-bold text-xl">
                    <tr>
                        <td class="px-4 py-4 border-r border-gray-200 bg-emerald-50/50 text-emerald-700">{{ $stats['hadir'] }}</td>
                        <td class="px-4 py-4 border-r border-gray-200 bg-blue-50/50 text-blue-700">{{ $stats['sakit'] }}</td>
                        <td class="px-4 py-4 border-r border-gray-200 bg-orange-50/50 text-orange-700">{{ $stats['izin'] }}</td>
                        <td class="px-4 py-4 border-r border-gray-200 bg-purple-50/50 text-purple-700">{{ $stats['cuti'] }}</td>
                        <td class="px-4 py-4 bg-red-50/50 text-red-600">{{ $stats['alpa'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Tambah Karyawan Baru</h3>
                <button onclick="closeModal()" class="text-white text-2xl">&times;</button>
            </div>
            <form action="{{ route('employees.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div><label class="block text-sm font-semibold">NPK</label><input type="text" name="npk" required class="w-full mt-1 p-2 border rounded-lg"></div>
                <div><label class="block text-sm font-semibold">Nama Lengkap</label><input type="text" name="name" required class="w-full mt-1 p-2 border rounded-lg"></div>
                <div><label class="block text-sm font-semibold">Departemen</label>
                    <select name="department" required class="w-full mt-1 p-2 border rounded-lg">
                        <option value="PURCHASING">PURCHASING</option>
                        <option value="MARKETING">MARKETING</option>
                        <option value="PRODUCTION">PRODUCTION</option>
                        <option value="QUALITY ASSURANCE">QUALITY ASSURANCE</option>
                        <option value="PROCESS ENGINEERING">PROCESS ENGINEERING</option>
                    </select>
                </div>
                <div><label class="block text-sm font-semibold">Jabatan</label><input type="text" name="title" required class="w-full mt-1 p-2 border rounded-lg"></div>
                <div class="pt-4 flex gap-2">
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 py-2 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-bold shadow-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-yellow-500 p-4 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Edit Data Karyawan</h3>
                <button onclick="closeEditModal()" class="text-white text-2xl">&times;</button>
            </div>
            <form id="formEdit" method="POST" class="p-6 space-y-4">
                @csrf @method('PUT')
                <div><label class="block text-sm font-semibold text-gray-500">NPK (Read Only)</label>
                    <input type="text" id="edit_npk" readonly class="w-full mt-1 p-2 border rounded-lg bg-gray-100">
                </div>
                <div><label class="block text-sm font-semibold">Nama</label><input type="text" name="name" id="edit_name" required class="w-full mt-1 p-2 border rounded-lg"></div>
                <div><label class="block text-sm font-semibold">Departemen</label><input type="text" name="department" id="edit_dept" required class="w-full mt-1 p-2 border rounded-lg"></div>
                <div><label class="block text-sm font-semibold">Jabatan</label><input type="text" name="title" id="edit_title" required class="w-full mt-1 p-2 border rounded-lg"></div>
                <div class="pt-4 flex gap-2">
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-100 py-2 rounded-lg font-bold">Batal</button>
                    <button type="submit" class="flex-1 bg-yellow-500 text-white py-2 rounded-lg font-bold shadow-lg">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() { document.getElementById('modalTambah').classList.remove('hidden'); }
        function closeModal() { document.getElementById('modalTambah').classList.add('hidden'); }
        function openEditModal(emp) {
            document.getElementById('formEdit').action = `/absensi/${emp.id}`;
            document.getElementById('edit_npk').value = emp.npk;
            document.getElementById('edit_name').value = emp.name;
            document.getElementById('edit_dept').value = emp.department;
            document.getElementById('edit_title').value = emp.title;
            document.getElementById('modalEdit').classList.remove('hidden');
        }
        function closeEditModal() { document.getElementById('modalEdit').classList.add('hidden'); }
    </script>
</body>
</html>