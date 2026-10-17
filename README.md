# SIPUS-Del - Sistem Informasi Perpustakaan IT Del

Aplikasi pengelolaan perpustakaan berbasis Laravel 11 untuk tugas praktikum Minggu 6 PPW.

## Kredensial Uji Pustakawan
* **Email**: `pustakawan@del.ac.id`
* **Password**: `password123`

## Cara Menjalankan Aplikasi
1. Clone repository
2. Jalankan `composer install`
3. Salin `.env.example` ke `.env` dan atur koneksi database (`sipus-del`)
4. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate:fresh --seed