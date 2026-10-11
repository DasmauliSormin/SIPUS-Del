<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BukuFactory extends Factory
{
    public function definition(): array
    {
        $topikBuku = [
            // Pemrograman & Web
            'Pemrograman Web Modern dengan Laravel 11 & Vue.js',
            'Membangun RESTful API Berkinerja Tinggi dengan Node.js',
            'Fullstack Web Development: Bootstrap 5, Tailwind & React',
            'Arsitektur Microservices & Clean Code dalam Java',
            'Trik & Praktik Terbaik Pemrograman C dan C++',
            
            // AI, Data & Cloud
            'Penerapan Machine Learning & Deep Learning untuk Big Data',
            'Mastering Python for Data Science & Artificial Intelligence',
            'Prompt Engineering & Implementasi Generative AI',
            'Pengantar Cloud Computing, Docker, & Kubernetes',
            'Optimasi Database Relasional: SQL Server & MySQL',

            // UI/UX & System Analysis
            'Desain UI/UX Modern: Wireframing & Prototyping dengan Figma',
            'Analisis & Perancangan Sistem Informasi Berbasis UML',
            'Pemodelan Proses Bisnis dengan Bizagi & BPMN',
            'Interaksi Manusia dan Komputer (IMK) dalam Sistem Informasi',

            // Cyber Security & Network
            'Dasar-Dasar Keamanan Cyber & Ethical Hacking',
            'Jaringan Komputer & Konfigurasi Routing Cisco',

            // Bisnis, IT Management & Project
            'Manajemen Proyek TI dengan Metode Agile & Scrum',
            'Strategi Bisnis Teknologi Informasi & Inovasi Digital',
            'Pengembangan Aplikasi Mobile Cross-Platform dengan Flutter'
        ];

        return [
            'kategori_id' => Kategori::inRandomOrder()->first()->id ?? Kategori::factory(),
            'isbn' => $this->faker->unique()->isbn13(),
            'judul' => $this->faker->randomElement($topikBuku) . ' (Edisi Ke-' . $this->faker->numberBetween(1, 4) . ')',
            'penulis' => $this->faker->name(),
            'penerbit' => $this->faker->randomElement([
                'Informatika Bandung', 
                'Andi Publisher', 
                'Erlangga', 
                'Gava Media', 
                'Tekno Press Del',
                'Elex Media Komputindo'
            ]),
            'tahun_terbit' => $this->faker->numberBetween(2019, 2026),
            'stok' => $this->faker->numberBetween(0, 35),
            'sinopsis' => $this->faker->paragraph(3),
        ];
    }
}