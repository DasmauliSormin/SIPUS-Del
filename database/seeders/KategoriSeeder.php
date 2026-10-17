<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            'Pemrograman & Software Engineering',
            'Basis Data & Analisis Data',
            'Jaringan Komputer & Keamanan Siber',
            'Sistem Informasi & Manajemen',
            'Kecerdasan Buatan & Machine Learning',
            'Sistem Operasi & Infrastruktur',
            'Matematika & Sains Terapan',
            'Umum & Literasi Digital',
        ];

        foreach ($kategoris as $nama) {
            Kategori::firstOrCreate(['nama_kategori' => $nama]);
        }
    }
}