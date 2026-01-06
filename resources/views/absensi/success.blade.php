<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Berhasil - Sigap MTM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">

    <div class="max-w-sm w-full bg-white rounded-3xl shadow-xl p-8 text-center animate__animated animate__fadeInUp">
        <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 animate__animated animate__zoomIn animate__delay-1s">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h1 class="text-2xl font-black text-gray-800 mb-2">ABSENSI BERHASIL!</h1>
        <p class="text-gray-500 text-sm mb-8">Data absensi kamu sudah masuk ke sistem PT Menara Terus Makmur.</p>

        <div class="bg-gray-50 rounded-2xl p-4 mb-8">
            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Waktu Server</p>
            <p class="text-lg font-mono font-bold text-gray-700">{{ date('H:i:s') }} WIB</p>
            <p class="text-[10px] text-gray-400 font-bold italic mt-1">{{ date('d F Y') }}</p>
        </div>

        <button onclick="window.close()" class="w-full bg-gray-800 text-white py-4 rounded-2xl font-bold shadow-lg active:scale-95 transition">
            Tutup Halaman
        </button>
        
        <p class="mt-6 text-[9px] text-gray-300 font-bold uppercase tracking-tighter">Sigap Management System v1.0</p>
    </div>

</body>
</html>