<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FinanceDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Cabang
        $jakarta = Branch::create(['name' => 'Cabang Jakarta', 'code' => 'JKT-01', 'initial_balance' => 5000000]);
        $bandung = Branch::create(['name' => 'Cabang Bandung', 'code' => 'BDG-01', 'initial_balance' => 2000000]);

        // Kategori
        Category::insert([
            ['name' => 'Penjualan', 'type' => 'pemasukan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pendapatan Lain', 'type' => 'pemasukan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gaji Karyawan', 'type' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sewa Tempat', 'type' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Listrik & Air', 'type' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // User admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@perusahaan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // User karyawan per cabang
        User::create([
            'name' => 'Karyawan Jakarta',
            'email' => 'jakarta@perusahaan.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
            'branch_id' => $jakarta->id,
        ]);

        User::create([
            'name' => 'Karyawan Bandung',
            'email' => 'bandung@perusahaan.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
            'branch_id' => $bandung->id,
        ]);
    }
}
