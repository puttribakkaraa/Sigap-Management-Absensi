<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;

// 1. Redirect Halaman Utama ke Login
Route::redirect('/', '/login');

// 2. Route Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::post('/employees/store', [AttendanceController::class, 'store'])->name('employees.store');
// 3. Route Absensi Mandiri (Bisa diakses tanpa login/publik via QR)
Route::get('/absen-mandiri', function () { 
    return view('absensi.mandiri'); 
});
Route::post('/absen-mandiri', [AttendanceController::class, 'AbsenMandiri']);

// 4. Route Terproteksi (Harus Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AttendanceController::class, 'dashboard'])->name('dashboard');
    Route::get('/absensi', [AttendanceController::class, 'index'])->name('absensi.index');
});

// 5. Route Pendukung Lainnya
Route::post('/scan-attendance', [AttendanceController::class, 'scan']);
Route::get('/scan/{npk}', [AttendanceController::class, 'publicScan']);
Route::middleware(['auth'])->group(function () {
    // ... route lainnya ...
    Route::get('/cetak-barcode', function () {
        return view('absensi.barcode');
    })->name('barcode.index');
});