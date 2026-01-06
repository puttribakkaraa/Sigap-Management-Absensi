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
        Schema::table('employees', function (Blueprint $table) {
            // Kita cek dulu: jika kolom 'department' BELUM ADA, maka buatkan.
            // Jika sudah ada, kode ini akan otomatis dilewati tanpa error.
            if (!Schema::hasColumn('employees', 'department')) {
                $table->string('department')->nullable()->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'department')) {
                $table->dropColumn('department');
            }
        });
    }
};