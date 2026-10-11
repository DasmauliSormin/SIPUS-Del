<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_kategori' => 'KAT-' . strtoupper($this->faker->unique()->lexify('???')),
            'nama_kategori' => $this->faker->unique()->randomElement([
                'Sains & Teknologi',
                'Pemrograman & Komputer',
                'Sistem Informasi',
                'Matematika & Statistik',
                'Manajemen & Bisnis',
                'Literatur & Bahasa'
            ]),
        ];
    }
}