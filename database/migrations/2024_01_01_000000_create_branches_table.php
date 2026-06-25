<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // nama cabang
            $table->string('code')->unique(); // kode cabang, misal: JKT-01
            $table->string('address')->nullable();
            $table->decimal('initial_balance', 18, 2)->default(0); // saldo awal cabang
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
