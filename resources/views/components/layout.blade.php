<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIPUS-Del' }} - Perpustakaan Institut Teknologi Del</title>
    
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --del-purple: #4C1D95;
            --del-purple-dark: #2E1065;
            --del-gold: #D97706;
            --del-gold-light: #FEF3C7;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #0F172A;
        }

        /* Navbar dengan Aksen Lis Gold Tipis (Subtle) */
        .navbar-del {
            background: linear-gradient(135deg, #4C1D95 0%, #2E1065 100%);
            box-shadow: 0 4px 20px rgba(76, 29, 149, 0.15);
            border-bottom: 3px solid var(--del-gold);
        }

        .brand-logo-bg {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 6px 12px;
        }

        .card-custom {
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            background: #ffffff;
        }

        .btn-del {
            background-color: var(--del-purple);
            color: #ffffff;
            border-radius: 12px;
            padding: 9px 20px;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-del:hover {
            background-color: var(--del-purple-dark);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(76, 29, 149, 0.35);
        }

        .text-del { color: var(--del-purple); }

        .table thead th {
            background-color: #F1F5F9;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.6px;
            border: none;
            padding: 16px;
        }

        .table tbody td {
            padding: 16px;
            border-color: #F1F5F9;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-del mb-4 py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('buku.index') }}">
                <div class="brand-logo-bg d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/DEL.png') }}" 
                         alt="Logo IT Del" 
                         height="40" 
                         class="me-1"
                         onerror="this.onerror=null; this.src='https://www.del.ac.id/wp-content/uploads/2018/10/logo-del.png';">
                </div>
                <div>
                    <span class="fw-extrabold fs-4 tracking-tight d-block text-white" style="line-height: 1.1;">SIPUS-Del</span>
                    <small class="text-warning fw-semibold fs-7" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                        PERPUSTAKAAN INSTITUT TEKNOLOGI DEL
                    </small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-semibold px-3 py-2 rounded-3 active" href="{{ route('buku.index') }}">
                            <i class="bi bi-grid-fill me-1 text-warning"></i> Katalog Buku
                        </a>
                    </li>

                    {{-- Tombol Tambah Data & Sesi Auth Pustakawan --}}
                    @auth
                        <li class="nav-item">
                            <a class="btn btn-warning text-dark fw-bold rounded-pill px-3 py-1 fs-7" href="{{ route('buku.create') }}">
                                <i class="bi bi-plus-circle-fill me-1"></i> Tambah Data
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <span class="badge bg-white text-dark py-2 px-3 rounded-pill">
                                <i class="bi bi-person-circle text-primary me-1"></i> Pustakawan Sesi Aktif
                            </span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1">
                                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a href="{{ route('login') }}" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 py-1">
                                <i class="bi bi-shield-lock-fill me-1"></i> Login Pustakawan
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="container flex-grow-1 pb-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                <div class="fw-semibold">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
                <div class="fw-semibold">{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Footer dengan Aksen Lis Gold & Motto MarTuhan, Marroha, Marbisuk -->
    <footer class="mt-auto py-4 bg-white border-top">
        <div class="container text-center text-muted small">
            <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                <span class="badge bg-purple-subtle text-del px-3 py-2 rounded-pill fw-bold" style="background-color: #F3E8FF;">
                    MarTuhan, Marroha, Marbisuk
                </span>
            </div>
            <p class="mb-0">© 2026 SIPUS-Del — Sistem Informasi Perpustakaan Institut Teknologi Del, Laguboti.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 