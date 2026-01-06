<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $dateRange = CarbonPeriod::create($startOfMonth, $endOfMonth);

        $employees = Employee::when($search, function ($query, $search) {
                return $query->where('npk', 'like', "%{$search}%");
            })
            ->with(['attendances' => function($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()]);
            }])
            ->get();

        return view('absensi.grid', compact('employees', 'dateRange', 'startOfMonth'));
    }

    public function AbsenMandiri(Request $request)
    {
        $request->validate([
            'npk' => 'required',
            'status' => 'required|in:Hadir,Izin,Sakit,Cuti',
            'reason' => 'nullable|string'
        ]);

        $employee = Employee::where('npk', $request->npk)->first();
        if (!$employee) { return back()->with('error', 'NPK tidak ditemukan!'); }

        $today = now()->toDateString();
        $existing = Attendance::where('employee_id', $employee->id)->where('date', $today)->first();
        if ($existing) { return back()->with('error', 'Anda sudah melakukan absensi hari ini.'); }

        Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'time' => now()->toTimeString(),
            'status' => $request->status,
            'reason' => $request->reason,
        ]);

        return "Absensi Berhasil! Terima kasih, $employee->name.";
    }

    public function dashboard()
{
    $totalKaryawan = Employee::count();
    $startOfMonth = now()->startOfMonth();
    $endOfMonth = now()->endOfMonth();
    $dateRange = CarbonPeriod::create($startOfMonth, $endOfMonth);

    $labels = [];
    $dataPersentase = [];

    foreach ($dateRange as $date) {
        $tanggal = $date->format('Y-m-d');
        $labels[] = $date->format('d');

        // Dashboard menghitung semua yang melapor (Hadir/Izin/Sakit/Cuti)
        $jumlahInput = Attendance::where('date', $tanggal)->count();
        
        $persentase = $totalKaryawan > 0 ? ($jumlahInput / $totalKaryawan) * 100 : 0;
        $dataPersentase[] = round($persentase, 1);
    }

    // Menggunakan nama variabel $hadirHariIni dan $tidakHadir agar sesuai dengan dashboard.blade.php
    $hadirHariIni = Attendance::where('date', now()->toDateString())->count();
    $tidakHadir = $totalKaryawan - $hadirHariIni;
    $persentaseHadir = $totalKaryawan > 0 ? ($hadirHariIni / $totalKaryawan) * 100 : 0;

    return view('absensi.dashboard', compact(
        'totalKaryawan', 'hadirHariIni', 'tidakHadir', 'persentaseHadir',
        'labels', 'dataPersentase'
    ));
}

    public function scan(Request $request)
    {
        $employee = Employee::where('npk', $request->npk)->first();
        if ($employee) {
            Attendance::updateOrCreate(
                ['employee_id' => $employee->id, 'date' => now()->toDateString()],
                ['time' => now()->toTimeString(), 'status' => 'Hadir']
            );
            return response()->json(['status' => 'success', 'message' => 'Absen Berhasil!']);
        }
        return response()->json(['status' => 'error', 'message' => 'NPK tidak ditemukan'], 404);
    }
}