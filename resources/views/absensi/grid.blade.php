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
            </div>
        </div>
        
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
                <tr class="bg-gray-200">
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
                        
                        <td class="{{ $date->isWeekend() ? 'bg-sunday' : '' }}">
                            @if($attendance)
                                @if($attendance->status == 'Hadir')
                                    <span class="text-green-600 font-bold text-sm">✔</span>
                                @elseif($attendance->status == 'Sakit')
                                    <span class="text-blue-600 font-bold text-lg" title="Sakit: {{ $attendance->reason }}">●</span>
                                @elseif($attendance->status == 'Izin')
                                    <span class="text-yellow-500 font-bold text-lg" title="Izin: {{ $attendance->reason }}">▲</span>
                                @elseif($attendance->status == 'Cuti')
                                    <span class="text-red-600 font-bold text-sm" title="Cuti: {{ $attendance->reason }}">X</span>
                                @else
                                    <span class="text-green-600 font-bold text-sm">✔</span>
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
            <span class="text-red-600">X Cuti</span>
        </div>

        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl flex justify-between items-center">
            <div class="flex gap-8">
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
                <span class="text-sm font-normal">Orang</span>
            </p>
        </div>

        <div class="border-r border-blue-200 pr-4">
            <p class="text-[10px] text-yellow-600 font-bold uppercase tracking-wider">▲ Izin</p>
            <p class="text-xl font-black text-yellow-900">
                {{ $employees->map(fn($e) => $e->attendances->where('date', now()->toDateString())->where('status', 'Izin'))->collapse()->count() }}
                <span class="text-sm font-normal">Orang</span>
            </p>
        </div>

        <div class="border-r border-blue-200 pr-4">
            <p class="text-[10px] text-blue-500 font-bold uppercase tracking-wider">● Sakit</p>
            <p class="text-xl font-black text-blue-800">
                {{ $employees->map(fn($e) => $e->attendances->where('date', now()->toDateString())->where('status', 'Sakit'))->collapse()->count() }}
                <span class="text-sm font-normal">Orang</span>
            </p>
        </div>

        <div>
            <p class="text-[10px] text-red-600 font-bold uppercase tracking-wider">X Cuti</p>
            <p class="text-xl font-black text-red-900">
                {{ $employees->map(fn($e) => $e->attendances->where('date', now()->toDateString())->where('status', 'Cuti'))->collapse()->count() }}
                <span class="text-sm font-normal">Orang</span>
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
        </div>
    </div>
</body>
</html>