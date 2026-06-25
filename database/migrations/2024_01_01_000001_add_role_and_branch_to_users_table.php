<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // role: admin (akses semua cabang & konsolidasi) | karyawan (akses cabang sendiri saja)
            $table->enum('role', ['admin', 'karyawan'])->default('karyawan')->after('email');
            $table->foreignId('branch_id')->nullable()->after('role')
                ->constrained('branches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('branch_id');
            $table->dropColumn('role');
        });
    }
};
