<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Mandiri - Sigap PT MTM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .astra-gradient { background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="astra-gradient flex items-center justify-center min-h-screen p-4">

    <div class="glass-card p-8 rounded-[2rem] shadow-2xl w-full max-w-sm border border-white/20">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-2xl mb-4 shadow-inner">
                <i class="fas fa-fingerprint text-blue-600 text-3xl"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight uppercase">E-Absensi</h2>
            <div class="h-1 w-12 bg-blue-600 mx-auto mt-2 rounded-full"></div>
            <p class="text-slate-500 text-xs mt-3 font-medium tracking-wide">SIGAP MANAGEMENT SYSTEM</p>
        </div>

        <form action="/absen-mandiri" method="POST" class="space-y-5">
            @csrf
            
            <div class="relative">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Nomor Pokok Karyawan (NPK)</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-blue-600">
                        <i class="fas fa-id-card"></i>
                    </span>
                    <input type="number" name="npk" placeholder="Contoh: 3608" required 
                           class="w-full bg-slate-50 border-2 border-slate-100 pl-11 pr-4 py-3.5 rounded-2xl text-lg font-bold text-slate-700 focus:outline-none focus:border-blue-600 focus:bg-white transition-all placeholder:font-normal placeholder:text-slate-300">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Status Kehadiran</label>
                <div class="relative">
                    <select name="status" id="status_select" required
                            class="w-full appearance-none bg-slate-50 border-2 border-slate-100 p-3.5 rounded-2xl font-semibold text-slate-700 focus:outline-none focus:border-blue-600 focus:bg-white transition-all cursor-pointer">
                        <option value="Hadir" class="font-semibold">✅ HADIR</option>
                        <option value="Izin" class="font-semibold">🟡 IZIN</option>
                        <option value="Sakit" class="font-semibold">🔵 SAKIT</option>
                        <option value="Cuti" class="font-semibold">🟢 CUTI</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            <div id="reason_container" class="hidden transform transition-all duration-300 origin-top">
                <label class="block text-[10px] font-bold text-amber-600 uppercase mb-1.5 ml-1 flex items-center gap-1">
                    <i class="fas fa-info-circle"></i> Keterangan Alasan
                </label>
                <textarea name="reason" rows="3" 
                          class="w-full bg-amber-50 border-2 border-amber-100 p-4 rounded-2xl text-sm font-medium text-amber-900 focus:outline-none focus:border-amber-400 placeholder:text-amber-300" 
                          placeholder="Mohon berikan keterangan singkat..."></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-extrabold text-sm shadow-xl shadow-blue-200 hover:bg-blue-700 active:scale-95 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                    Kirim Data <i class="fas fa-paper-plane text-[10px]"></i>
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <div class="flex items-center justify-center gap-2 mb-2">
                <div class="h-px w-8 bg-slate-200"></div>
                <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">PT MTM</span>
                <div class="h-px w-8 bg-slate-200"></div>
            </div>
            <p class="text-[9px] text-slate-400 font-medium uppercase tracking-widest">
                Integrated Management System © 2026
            </p>
        </div>
    </div>

    <script>
        const statusSelect = document.getElementById('status_select');
        const reasonContainer = document.getElementById('reason_container');
        const textarea = reasonContainer.querySelector('textarea');

        statusSelect.addEventListener('change', function() {
            if (this.value !== 'Hadir') {
                reasonContainer.classList.remove('hidden');
                reasonContainer.classList.add('animate-slide-down');
                textarea.required = true;
                textarea.focus();
            } else {
                reasonContainer.classList.add('hidden');
                textarea.required = false;
            }
        });
    </script>

    <style>
        @keyframes slide-down {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-down {
            animation: slide-down 0.3s ease-out forwards;
        }
    </style>
</body>
</html>