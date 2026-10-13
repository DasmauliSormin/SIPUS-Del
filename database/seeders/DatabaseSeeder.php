<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Account Pustakawan Uji
        User::updateOrCreate(
            ['email' => 'pustakawan@del.ac.id'],
            [
                'name' => 'Pustakawan Del',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Kategori Sample
        $kategori = Kategori::firstOrCreate(
            ['kode_kategori' => 'KAT-01'],
            ['nama_kategori' => 'Pemrograman & Software Engineering']
        );

        // 3. Buku Sampel Uji ACID (Stok 0 dan Stok 1)
        Buku::updateOrCreate(
            ['isbn' => '978-0000000001'],
            [
                'kategori_id' => $kategori->id,
                'judul' => 'Pemrograman Laravel Lanjut (Stok Habis)',
                'penulis' => 'Dosen IT Del',
                'penerbit' => 'Del Press',
                'tahun_terbit' => 2026,
                'stok' => 0, // Sampel Pengujian Stok = 0
                'sinopsis' => 'Buku sampel untuk pengujian rollback transaksi ACID saat stok habis.',
            ]
        );

        Buku::updateOrCreate(
            ['isbn' => '978-0000000002'],
            [
                'kategori_id' => $kategori->id,
                'judul' => 'Sistem Basis Data ACID (Stok Kritis)',
                'penulis' => 'Dosen IT Del',
                'penerbit' => 'Del Press',
                'tahun_terbit' => 2026,
                'stok' => 1, // Sampel Pengujian Stok = 1
                'sinopsis' => 'Buku sampel untuk pengujian locking concurrency.',
            ]
        );

        // Sisanya generate factory jika ada
        Buku::factory(20)->create();
    }
}