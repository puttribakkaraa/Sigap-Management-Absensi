<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode Absensi - Sigap MTM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .print-card { 
                border: none !important; 
                box-shadow: none !important;
                margin: 0 auto !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center py-10">

    <div class="no-print w-full max-w-md mb-4 px-4">
        <a href="/dashboard" class="text-indigo-600 font-bold flex items-center gap-2">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="print-card bg-white p-10 rounded-3xl shadow-2xl border border-gray-200 text-center w-full max-w-md">
        <h1 class="text-3xl font-black text-gray-800 tracking-tighter">ABSENSI SIGAP</h1>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6 border-b pb-4">PT MENARA TERUS MAKMUR</p>
        
        @php
            // Menggunakan helper url() agar barcode otomatis berubah 
            // saat kamu pindah dari localhost ke Hosting (Domain Resmi)
            $urlAbsen = url('/absen-mandiri'); 
        @endphp

        <div class="flex justify-center mb-6">
            <div class="p-4 bg-white border-[5px] border-black rounded-2xl">
                {{-- Ini adalah Barcodenya --}}
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($urlAbsen) }}" 
                     alt="QR Code Absensi" 
                     class="w-64 h-64">
            </div>
        </div>

        <p class="text-[9px] font-mono text-gray-500 mb-6 break-all">{{ $urlAbsen }}</p>
        
        <div class="pt-4 border-t-2 border-dashed border-gray-100">
            <p class="text-xs font-bold text-gray-400 italic">Scan untuk melakukan absensi mandiri</p>
        </div>
    </div>

    <div class="mt-8 no-print">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3 rounded-xl font-bold shadow-lg transition transform active:scale-95">
            🖨️ CETAK BARCODE SEKARANG
        </button>
    </div>

</body>
</html>