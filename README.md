# Aplikasi Keuangan Perusahaan (Laravel + MySQL)

## Fitur
- Multi-cabang: tiap cabang punya saldo & laporan sendiri
- Konsolidasi: admin lihat total semua cabang sekaligus
- 2 role: **admin** (akses semua cabang, kelola cabang/kategori, laporan & export) & **karyawan** (input transaksi cabang sendiri, langsung tercatat tanpa approval)
- Pemasukan & pengeluaran dengan kategori
- Upload bukti/lampiran transaksi (opsional)
- Filter laporan per tanggal, per cabang, per tipe
- **Halaman Laporan** dengan **export ke PDF & Excel** (admin only)
- **UI responsive** — navbar dengan menu hamburger di mobile, tabel otomatis jadi card-list di layar kecil

## Tambahan: Install package untuk Export PDF & Excel

Setelah project Laravel + Breeze sudah jalan normal, install 2 package ini (butuh internet & composer):

```bash
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel
```

Tidak perlu konfigurasi tambahan — keduanya auto-register lewat Laravel package discovery. Setelah terinstall, halaman **Laporan** (menu di navbar, khusus admin) akan punya tombol **Export PDF** dan **Export Excel** yang otomatis berfungsi.

> Kalau muncul error `Class "Maatwebsite\Excel\Facades\Excel" not found` atau sejenisnya setelah composer require, jalankan `composer dump-autoload` lalu coba lagi.


## Setup di Windows + XAMPP

### 0. Persiapan tools (sekali aja)

**a. Aktifkan extension `zip` di PHP XAMPP** (wajib, kalau belum Composer error pas download package):
1. Buka `C:\xampp\php\php.ini`
2. Cari baris `;extension=zip`, hapus tanda `;` jadi `extension=zip`
3. Save, lalu **tutup & buka ulang terminal**
4. Cek: `php -m | findstr zip` → harus muncul `zip`

**b. Install Composer** (kalau belum ada): download dari https://getcomposer.org/download/, saat instalasi arahkan ke `C:\xampp\php\php.exe`. Cek dengan `composer -V`.

**c. Pastikan versi PHP cukup**: cek `php -v`.
- PHP 8.2 → otomatis dapat **Laravel 12** (Composer akan fallback otomatis, ini normal & semua kode di repo ini tetap kompatibel)
- PHP 8.3+ → bisa dapat Laravel 13 juga aman dipakai

**d. Install Node.js** (untuk compile CSS/JS Breeze) dari https://nodejs.org jika belum ada.

### 1. Nyalakan MySQL dari XAMPP
Buka **XAMPP Control Panel** → klik **Start** di baris **MySQL** saja (Apache tidak perlu dinyalakan, lihat penjelasan di bawah).

### 2. Buat project Laravel
```bash
composer create-project laravel/laravel finance-app
cd finance-app
```

### 3. Install Laravel Breeze (auth login/register)
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

### 4. Copy file dari repo ini ke project Laravel kamu
- `app/Models/*.php` → overwrite `app/Models/`
- `app/Http/Controllers/*.php` → `app/Http/Controllers/` (termasuk `ReportController.php`)
- `app/Http/Middleware/EnsureUserHasRole.php` → `app/Http/Middleware/`
- `app/Exports/TransactionsExport.php` → buat folder `app/Exports/` lalu copy ke situ
- `database/migrations/*.php` → `database/migrations/`
- `database/seeders/FinanceDemoSeeder.php` → `database/seeders/`
- `resources/views/*` → `resources/views/` (termasuk folder `reports/`)
- `routes/web.php` → overwrite `routes/web.php`

### 5. Daftarkan middleware `role`
Lihat isi file `CARA_DAFTAR_MIDDLEWARE.txt` — tambahkan alias middleware di `bootstrap/app.php` (Laravel 11/12+) atau `app/Http/Kernel.php` (Laravel 10).

### 6. Buat database lewat phpMyAdmin
Buka `http://localhost/phpmyadmin` → buat database baru bernama `finance_app`.

### 7. Setting `.env` (sesuaikan dengan default XAMPP)
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=finance_app
DB_USERNAME=root
DB_PASSWORD=
```
(default XAMPP: user `root`, password kosong — kecuali sudah pernah diubah sendiri)

### 8. Migrate, seed, link storage
```bash
php artisan migrate
php artisan db:seed --class=FinanceDemoSeeder
php artisan storage:link
```

### 9. Jalankan aplikasi
```bash
php artisan serve
```
Buka `http://127.0.0.1:8000`

> **Kenapa pakai `php artisan serve`, bukan Apache XAMPP?**
> Laravel sudah punya web server kecil bawaan yang jauh lebih simpel buat development — tidak perlu setting virtual host/`.htaccess` di Apache. XAMPP di sini cuma dipakai untuk **MySQL**-nya. Apache XAMPP baru relevan kalau nanti mau deploy ke server produksi/hosting, bukan untuk development di laptop sendiri.

## Login demo (setelah seeding)
- Admin: admin@perusahaan.com / password
- Karyawan Jakarta: jakarta@perusahaan.com / password
- Karyawan Bandung: bandung@perusahaan.com / password

## Troubleshooting Umum

| Error | Solusi |
|---|---|
| `'composer' is not recognized` | Composer belum terinstall / belum masuk PATH. Install ulang dari getcomposer.org, restart terminal. |
| `requires php ^8.3 which is not satisfied` | Versi PHP XAMPP kamu < 8.3, Composer otomatis fallback ke Laravel 12 — ini normal, lanjut saja. |
| `The zip extension and unzip/7z commands are both missing` | Extension `zip` belum aktif di `php.ini` (lihat langkah 0a di atas). |
| `SQLSTATE[HY000] [2002] Connection refused` | MySQL di XAMPP belum di-Start, atau `DB_HOST`/`DB_PORT` di `.env` salah. |
| `SQLSTATE[HY000] [1045] Access denied` | Username/password MySQL di `.env` tidak sesuai. Default XAMPP: `root` tanpa password. |
| Halaman bukti/lampiran transaksi tidak muncul | Lupa jalankan `php artisan storage:link`. |
| `Class "Middleware" not found` saat daftarkan alias `role` | Tambahkan `use Illuminate\Foundation\Configuration\Middleware;` di atas `bootstrap/app.php`. |

## Catatan pengembangan lanjutan (saran)
- Tambah halaman "Kelola User" (admin bisa daftarkan karyawan baru & assign ke cabang)
- Export laporan ke Excel/PDF (pakai package `maatwebsite/excel` atau `barryvdh/laravel-dompdf`)
- Grafik tren pemasukan/pengeluaran (Chart.js)
- Audit log (siapa edit/hapus transaksi apa, kapan)
- Kalau nanti butuh approval admin, tinggal tambah kolom `status` (pending/approved) di tabel transactions
