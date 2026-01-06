<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Buat Departemen dulu
    $dept = \App\Models\Department::create(['name' => 'BOD']);

    // Buat Karyawan
    \App\Models\Employee::create([
        'npk' => '3608',
        'name' => 'EVI SULISTYORINI',
        'title' => 'PRESDIR',
        'department_id' => $dept->id
    ]);
}
}
