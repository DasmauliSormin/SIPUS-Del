<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Akun Pustakawan Uji (Modul Bagian V Poin 5)
        User::firstOrCreate(
            ['email' => 'pustakawan@del.ac.id'],
            [
                'name'     => 'Pustakawan IT Del',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Data Kategori Lengkap
        $kategoriData = [
            ['kode_kategori' => 'KAT-01', 'nama_kategori' => 'Pemrograman & Software Engineering'],
            ['kode_kategori' => 'KAT-02', 'nama_kategori' => 'Basis Data & Analisis Data'],
            ['kode_kategori' => 'KAT-03', 'nama_kategori' => 'Jaringan Komputer & Keamanan Siber'],
            ['kode_kategori' => 'KAT-04', 'nama_kategori' => 'Sistem Informasi & Manajemen'],
            ['kode_kategori' => 'KAT-05', 'nama_kategori' => 'Kecerdasan Buatan & Machine Learning'],
            ['kode_kategori' => 'KAT-06', 'nama_kategori' => 'Matematika & Sains Terapan'],
            ['kode_kategori' => 'KAT-07', 'nama_kategori' => 'Umum & Literasi Digital'],
        ];

        $kategoriMap = [];
        foreach ($kategoriData as $data) {
            $kat = Kategori::firstOrCreate(
                ['kode_kategori' => $data['kode_kategori']],
                ['nama_kategori' => $data['nama_kategori']]
            );
            $kategoriMap[$data['kode_kategori']] = $kat->id;
        }

        // 3. Sampel Uji Wajib Modul (Stok 0 untuk Rollback & Stok 1 untuk Peminjaman)
        $bukuStokKosong = Buku::firstOrCreate(
            ['isbn' => '978-602-000-000-0'],
            [
                'judul'        => 'Pemrograman Web Lanjut (Stok Habis - Uji Rollback)',
                'penulis'      => 'Chandro Pardede',
                'penerbit'     => 'IT Del Press',
                'tahun_terbit' => 2026,
                'stok'         => 0,
                'kategori_id'  => $kategoriMap['KAT-01']
            ]
        );

        $bukuStokSatu = Buku::firstOrCreate(
            ['isbn' => '978-602-000-001-7'],
            [
                'judul'        => 'Analisis dan Perancangan Sistem Informasi',
                'penulis'      => 'Tim Dosen IT Del',
                'penerbit'     => 'Informatika',
                'tahun_terbit' => 2025,
                'stok'         => 1,
                'kategori_id'  => $kategoriMap['KAT-04']
            ]
        );

        // 4. Generator 150 Data Buku Koleksi SIPUS-Del
        $topik = [
            'Pemrograman' => ['Laravel 11 Fundamentals', 'Mastering React & Next.js', 'Clean Code in Java', 'Python for Automation', 'C++ Programming Mastery', 'Go Microservices Architecture', 'Flutter Mobile App Development', 'Spring Boot Enterprise', 'Rust Programming Primer', 'Node.js Backend Architecture'],
            'Basis Data' => ['SQL Server Optimization', 'PostgreSQL Advanced Indexing', 'Database System Concepts', 'NoSQL & MongoDB Design', 'Data Warehouse Architecture', 'Redis In-Memory Caching', 'Big Data Engineering with Spark', 'Data Modeling Best Practices', 'Database Security Implementation', 'ETL Pipeline Engineering'],
            'Jaringan & Keamanan' => ['Computer Networks Topologies', 'Cybersecurity Fundamentals', 'Ethical Hacking & Penetration Testing', 'Cloud Security Engineering', 'Network Protocols & Wireshark', 'Linux System Administration', 'DevOps & Docker Deployment', 'Kubernetes Orchestration', 'Cryptography & Network Safety', 'CISSP Exam Guide'],
            'Sistem Informasi' => ['Enterprise Resource Planning Systems', 'IT Governance & COBIT', 'Agile Scrum Project Management', 'Digital Business Transformation', 'Supply Chain Management Systems', 'Business Process Modeling with BPMN', 'E-Commerce Technology Strategy', 'Customer Relationship Management', 'IT Service Management ITIL', 'System Analysis & Design'],
            'Kecerdasan Buatan' => ['Machine Learning Algorithms', 'Deep Learning with PyTorch', 'Artificial Intelligence in Practice', 'Natural Language Processing with Transformers', 'Computer Vision Fundamentals', 'Generative AI & LLM Engineering', 'Reinforcement Learning Applications', 'Data Science with Python', 'Neural Networks Fundamentals', 'Prompt Engineering Architecture'],
            'Matematika & Umum' => ['Discrete Mathematics for CS', 'Linear Algebra for Data Science', 'Probability & Statistics for Engineers', 'Calculus for Computer Science', 'Scientific Writing & Research Methods', 'Digital Literacy in Modern Era', 'Academic Integrity & Ethics', 'Information Systems Leadership', 'Technical Communication Skills', 'Software Project Economics']
        ];

        $penerbitList = ['Andi Publisher', 'Informatika Bandung', 'Pearson Education', 'O\'Reilly Media', 'Packt Publishing', 'IT Del Press', 'Erlangga', 'Gava Media'];
        $penulisList = ['Dr. Arnaldo Sinaga', 'Chandro Pardede, M.Sc.', 'Dr. Johannes Harungguan', 'Roga Kembaren, M.T.', 'Dasmauli Sormin', 'Prof. Tan Sian Lip', 'Griptha Sianipar', 'Ayu Purba, M.Kom.'];

        $count = 1;
        foreach ($topik as $katKey => $judulList) {
            $catCode = match($katKey) {
                'Pemrograman' => 'KAT-01',
                'Basis Data' => 'KAT-02',
                'Jaringan & Keamanan' => 'KAT-03',
                'Sistem Informasi' => 'KAT-04',
                'Kecerdasan Buatan' => 'KAT-05',
                default => 'KAT-06',
            };

            foreach ($judulList as $judulBase) {
                for ($v = 1; $v <= 3; $v++) {
                    $isbn = sprintf("978-602-%03d-%03d-%d", rand(100, 999), $count, $v);
                    $judul = $v === 1 ? $judulBase : $judulBase . " (Edisi Revisi " . $v . ")";
                    
                    Buku::firstOrCreate(
                        ['isbn' => $isbn],
                        [
                            'judul'        => $judul,
                            'penulis'      => $penulisList[array_rand($penulisList)],
                            'penerbit'     => $penerbitList[array_rand($penerbitList)],
                            'tahun_terbit' => rand(2018, 2026),
                            'stok'         => rand(2, 10),
                            'kategori_id'  => $kategoriMap[$catCode],
                        ]
                    );
                    $count++;
                }
            }
        }

        // 5. Sampel Transaksi Peminjaman
        Peminjaman::firstOrCreate(
            ['kode_pinjam' => 'PJM-20261016-001'],
            [
                'buku_id'             => $bukuStokSatu->id,
                'nim_peminjam'        => '12S24007',
                'nama_peminjam'       => 'Dasmauli Sormin',
                'tanggal_pinjam'      => now()->format('Y-m-d'),
                'tanggal_jatuh_tempo' => now()->addDays(7)->format('Y-m-d'),
                'tanggal_kembali'     => null,
                'status'              => 'dipinjam',
            ]
        );

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}