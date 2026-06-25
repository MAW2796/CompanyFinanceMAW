<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // siapa yang input
            $table->enum('type', ['pemasukan', 'pengeluaran']);
            $table->decimal('amount', 18, 2);
            $table->date('transaction_date');
            $table->string('description')->nullable();
            $table->string('attachment')->nullable(); // path bukti/struk (opsional)
            $table->timestamps();

            $table->index(['branch_id', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
