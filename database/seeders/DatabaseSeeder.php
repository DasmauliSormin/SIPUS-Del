<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Buku;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Daftar Kategori beserta kode_kategori
        $kategoris = [
            ['kode' => 'KAT-01', 'nama' => 'Pemrograman & Rekayasa Perangkat Lunak'],
            ['kode' => 'KAT-02', 'nama' => 'Sistem Informasi & Manajemen Bisnis'],
            ['kode' => 'KAT-03', 'nama' => 'Kecerdasan Buatan & Data Science'],
            ['kode' => 'KAT-04', 'nama' => 'Jaringan Komputer & Cyber Security'],
            ['kode' => 'KAT-05', 'nama' => 'UI/UX Design & Produk Digital'],
        ];

        foreach ($kategoris as $kat) {
            Kategori::firstOrCreate(
                ['kode_kategori' => $kat['kode']],
                ['nama_kategori' => $kat['nama']]
            );
        }

        // 2. Generate 150 Buku
        Buku::factory()->count(150)->create();
    }
}