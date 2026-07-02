# Laundry UKOM

Aplikasi Sistem Informasi Manajemen Laundry berbasis web, dibangun menggunakan framework Laravel. Aplikasi ini dirancang untuk memenuhi kebutuhan Uji Kompetensi Keahlian (UKOM) dengan fitur pengelolaan transaksi laundry, master data, dan pelaporan yang dibedakan berdasarkan hak akses (Role).

## 🌟 Fitur Utama

Sistem ini memiliki 3 hak akses utama (Role) dengan fitur-fitur sebagai berikut:

### 1. Administrator
- **Master Data**: Kelola data Pelanggan (Customers), Layanan/Paket Laundry (Services), dan Pengguna/Karyawan (Users).
- **Transaksi**: Akses penuh ke pencatatan transaksi pesanan dan pengambilan (pickup).
- **Laporan**: Melihat dan mencetak laporan transaksi.

### 2. Operator
- **Transaksi Pesanan**: Mencatat pesanan laundry masuk dari pelanggan.
- **Transaksi Pengambilan (Pickup)**: Mencatat pengambilan laundry yang sudah selesai.
- **Cetak Struk**: Mencetak struk/nota transaksi untuk pelanggan.

### 3. Pimpinan
- **Laporan**: Melihat rekapitulasi laporan transaksi keseluruhan.
- **Cetak Laporan**: Mencetak laporan untuk kebutuhan evaluasi dan pembukuan.

---

## 💻 Teknologi yang Digunakan

- **Framework**: [Laravel 13.x](https://laravel.com)
- **Bahasa**: PHP 8.3+
- **Database**: MySQL / SQLite / PostgreSQL (mendukung Database Agnostic melalui Eloquent ORM)
- **Frontend Assets**: Vite, Node.js

---

## ⚙️ Persyaratan Sistem (Requirements)

Sebelum menginstal aplikasi ini, pastikan sistem Anda telah memiliki:
- PHP >= 8.3
- Composer
- Node.js & NPM
- Database Server (MySQL/MariaDB, dll)

---

## 🚀 Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di komputer lokal (Localhost):

1. **Clone atau Ekstrak Source Code**
   Pastikan source code berada di dalam direktori server lokal Anda (misal: `htdocs` untuk XAMPP).

2. **Install Dependencies PHP**
   Buka terminal/command prompt, arahkan ke folder project, lalu jalankan:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan sesuaikan konfigurasi database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database (dan Seeder)**
   Jalankan perintah ini untuk membuat tabel beserta data awal (jika tersedia):
   ```bash
   php artisan migrate --seed
   ```

6. **Install dan Build Frontend Assets**
   ```bash
   npm install
   npm run build
   ```

7. **Jalankan Aplikasi**
   Gunakan server bawaan Laravel untuk menjalankan aplikasi:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di: `http://localhost:8000`

---

## 📄 Lisensi

Aplikasi ini bersifat *open-sourced* dan dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
# ukom-laundry
