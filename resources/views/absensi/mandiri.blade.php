<!DOCTYPE html>
<html>
<head>
    <title>Absensi Mandiri - Sigap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-blue-600 flex items-center justify-center h-screen p-4">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-sm">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-black text-gray-800 uppercase">Input Absensi</h2>
            <p class="text-gray-500 text-sm">Silakan masukkan NPK & Status</p>
        </div>

        <form action="/absen-mandiri" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">NPK Karyawan</label>
                <input type="number" name="npk" placeholder="Contoh: 3608" required 
                       class="w-full border-2 border-gray-100 p-3 rounded-xl text-center text-xl font-bold focus:outline-none focus:border-blue-500 transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Status Hari Ini</label>
                <select name="status" id="status_select" required
                        class="w-full border-2 border-gray-100 p-3 rounded-xl bg-white font-semibold focus:outline-none focus:border-blue-500 transition-all">
                    <option value="Hadir">✔️ HADIR</option>
                    <option value="Izin">🟡 IZIN</option>
                    <option value="Sakit">🔵 SAKIT</option>
                    <option value="Cuti">❌ CUTI</option>
                </select>
            </div>

            <div id="reason_container" class="hidden animate-fade-in">
                <label class="block text-xs font-bold text-orange-500 uppercase mb-1">Alasan / Keterangan</label>
                <textarea name="reason" rows="2" 
                          class="w-full border-2 border-orange-100 p-3 rounded-xl text-sm focus:outline-none focus:border-orange-400" 
                          placeholder="Tulis alasan singkat di sini..."></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-xl font-black text-lg shadow-lg hover:bg-blue-700 active:scale-95 transition-all uppercase tracking-wider">
                Kirim Absensi
            </button>
        </form>

        <p class="text-center text-[10px] text-gray-400 mt-6 uppercase font-bold tracking-widest">
            © 2026 PT MTM - SIGAP MANAGEMENT
        </p>
    </div>

    <script>
        const statusSelect = document.getElementById('status_select');
        const reasonContainer = document.getElementById('reason_container');

        statusSelect.addEventListener('change', function() {
            if (this.value !== 'Hadir') {
                reasonContainer.classList.remove('hidden');
                reasonContainer.querySelector('textarea').required = true;
            } else {
                reasonContainer.classList.add('hidden');
                reasonContainer.querySelector('textarea').required = false;
            }
        });
    </script>
</body>
</html>