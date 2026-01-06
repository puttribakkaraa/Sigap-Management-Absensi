<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Sigap Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar-fixed { position: sticky; top: 0; height: 100vh; }
        .nav-link { transition: all 0.3s ease; border-radius: 0.75rem; margin: 0.25rem 1rem; }
        .nav-link:hover { background-color: #f3f4f6; transform: translateX(5px); }
        .nav-link.active { background-color: #eff6ff; color: #2563eb; font-weight: 600; border-right: 4px solid #2563eb; }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen bg-gray-100">
    <aside class="w-72 bg-white shadow-xl hidden md:flex flex-col sidebar-fixed">
        <div class="p-8 flex justify-center border-b mb-4">
            <img src="{{ asset('images/logomtmfix.png') }}" alt="Logo MTM" class="h-14 w-auto object-contain">
        </div>

            <nav class="flex-1 space-y-2">
                <p class="px-8 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>
                <a href="/dashboard" class="nav-link flex items-center px-6 py-3 {{ Request::is('dashboard') ? 'active' : 'text-gray-600' }}">
                    <i class="fas fa-th-large w-6 text-center mr-3"></i> <span>Dashboard Utama</span>
                </a>
                <a href="/absensi" class="nav-link flex items-center px-6 py-3 {{ Request::is('absensi') ? 'active' : 'text-gray-600' }}">
                    <i class="fas fa-calendar-check w-6 text-center mr-3"></i> <span>Monitoring Absensi</span>
                </a>
                <p class="px-8 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Input Data</p>
                <a href="{{ route('barcode.index') }}" class="nav-link flex items-center px-6 py-3 {{ Request::is('cetak-barcode') ? 'active' : 'text-gray-600' }}">
                    <i class="fas fa-camera w-6 text-center mr-3"></i> <span>Cetak Barcode</span>
                </a>
            </nav>
            <form action="/logout" method="POST" class="px-6 py-4 mt-auto">
    @csrf
    <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 rounded-lg transition flex items-center justify-center">
        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
    </button>
</form>
            <div class="p-6 border-t bg-gray-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white shadow-md">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-gray-800">Admin MTM</p>
                        <p class="text-xs text-green-500 flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span> Online
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto">
            <header class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-20">
                <h2 class="text-lg font-semibold text-gray-700 uppercase tracking-wider">Dashboard Utama</h2>
                
                <div class="flex items-center bg-gray-50 px-4 py-2 rounded-xl border shadow-sm">
                    <i class="far fa-clock text-blue-600 mr-3 text-xl"></i>
                    <div class="text-right">
                        <p id="realtime-clock" class="text-xl font-black text-gray-800 leading-none">00:00:00</p>
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">{{ now()->format('d F Y') }}</p>
                    </div>
                </div>
            </header>

            <main class="p-6">
                <div class="bg-orange-100 border-l-4 border-orange-500 p-4 mb-6 rounded-r-lg shadow-sm flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-triangle-exclamation text-orange-500 mr-3 text-xl"></i>
                        <p class="text-orange-700 font-medium">Informasi: Terdapat {{ $tidakHadir }} karyawan belum melakukan scan absensi hari ini.</p>
                    </div>
                    <a href="/absensi" class="bg-orange-500 text-white px-4 py-1 rounded-full text-sm font-bold shadow-md">Lihat Detail</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-blue-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs text-gray-400 font-bold mb-1 uppercase">Total Karyawan</p>
                                <h3 class="text-3xl font-black text-gray-800">{{ $totalKaryawan }}</h3>
                            </div>
                            <div class="bg-blue-100 text-blue-600 p-3 rounded-xl"><i class="fas fa-users text-xl"></i></div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-green-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs text-gray-400 font-bold mb-1 uppercase">Absen Hari Ini</p>
                                <h3 class="text-3xl font-black text-gray-800">{{ $hadirHariIni }}</h3>
                            </div>
                            <div class="bg-green-100 text-green-600 p-3 rounded-xl"><i class="fas fa-check-circle text-xl"></i></div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-red-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs text-gray-400 font-bold mb-1 uppercase">Belum Absen</p>
                                <h3 class="text-3xl font-black text-gray-800">{{ $tidakHadir }}</h3>
                            </div>
                            <div class="bg-red-100 text-red-600 p-3 rounded-xl"><i class="fas fa-clock text-xl"></i></div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-purple-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs text-gray-400 font-bold mb-1 uppercase">Status Sistem</p>
                                <h3 class="text-2xl font-black text-gray-800">AKTIF</h3>
                            </div>
                            <div class="bg-purple-100 text-purple-600 p-3 rounded-xl"><i class="fas fa-server text-xl"></i></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm mb-8 border">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-gray-700 uppercase tracking-tight">Statistik Kehadiran ({{ now()->format('F Y') }})</h3>
                        <span class="text-[10px] bg-blue-100 text-blue-600 px-3 py-1 rounded-full font-bold uppercase">Persentase (%)</span>
                    </div>
                    <div class="h-72">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-r from-red-600 to-red-400 p-8 rounded-2xl text-white shadow-lg relative overflow-hidden">
                        <div class="relative z-10">
                            <h4 class="text-lg font-bold mb-2 uppercase opacity-80">Peringatan Kehadiran</h4>
                            <p class="text-4xl font-black">{{ $tidakHadir }} KARYAWAN</p>
                            <p class="text-sm mt-2 font-medium">Segera tindak lanjuti karyawan yang belum absen.</p>
                        </div>
                        <i class="fas fa-exclamation-circle absolute -right-4 -bottom-4 text-9xl opacity-20"></i>
                    </div>

                    <div class="bg-gradient-to-r from-orange-500 to-yellow-400 p-8 rounded-2xl text-white shadow-lg relative overflow-hidden">
                        <div class="relative z-10">
                            <h4 class="text-lg font-bold mb-2 uppercase opacity-80">Target Harian</h4>
                            <p class="text-4xl font-black">100%</p>
                            <p class="text-sm mt-2 font-medium">Monitoring selesai pukul 23:59 WIB.</p>
                        </div>
                        <i class="fas fa-history absolute -right-4 -bottom-4 text-9xl opacity-20"></i>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('realtime-clock').textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock(); // Jalankan langsung saat load
    </script>

    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Persentase Kehadiran',
                    data: {!! json_encode($dataPersentase) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { callback: value => value + "%" } }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
</body>
</html>