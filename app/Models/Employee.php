<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Tambahkan ini agar aman saat input data
    protected $fillable = ['npk', 'name', 'title', 'department'];

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }

    public function department() {
        // Jika masih merah, coba tulis lengkap: return $this->belongsTo(\App\Models\Department::class);
        return $this->belongsTo(Department::class);
    }
}