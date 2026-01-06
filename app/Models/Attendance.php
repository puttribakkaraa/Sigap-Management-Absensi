<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    // Daftarkan kolom yang bisa diisi manual
    protected $fillable = ['employee_id', 'date', 'time_in', 'status'];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}