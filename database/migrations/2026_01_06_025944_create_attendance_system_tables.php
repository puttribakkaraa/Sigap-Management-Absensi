<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    // 1. Tabel Departemen
    Schema::create('departments', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Contoh: BOD, PLANT, HC & MARKETING
        $table->timestamps();
    });

    // 2. Tabel Karyawan (Employees)
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('npk')->unique();
        $table->string('name');
        $table->string('title'); // Contoh: PRESDIR, DIV.HEAD
        $table->foreignId('department_id')->constrained();
        $table->timestamps();
    });

    // 3. Tabel Absensi
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->date('date');
        $table->time('time_in')->nullable();
        $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Cuti'])->default('Hadir');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_system_tables');
    }
};
