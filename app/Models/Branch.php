<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = ['name', 'code', 'address', 'initial_balance'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Hitung saldo cabang saat ini = saldo awal + total pemasukan - total pengeluaran
     */
    public function getBalanceAttribute(): float
    {
        $pemasukan = $this->transactions()->where('type', 'pemasukan')->sum('amount');
        $pengeluaran = $this->transactions()->where('type', 'pengeluaran')->sum('amount');

        return (float) $this->initial_balance + (float) $pemasukan - (float) $pengeluaran;
    }
}
