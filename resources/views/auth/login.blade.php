<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pustakawan - SIPUS-Del</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #F8FAFC; }
        .card-login { border-radius: 18px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .bg-purple { background: linear-gradient(135deg, #4C1D95 0%, #2E1065 100%); }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 py-5">
    <div class="container" style="max-width: 420px;">
        <div class="card card-login bg-white overflow-hidden p-4">
            <div class="text-center mb-4">
                <img src="{{ asset('images/DEL.png') }}" height="50" alt="Logo IT Del" class="mb-2" onerror="this.onerror=null; this.src='https://www.del.ac.id/wp-content/uploads/2018/10/logo-del.png';">
                <h4 class="fw-bold text-dark">Portal Pustakawan</h4>
                <p class="text-muted small">Sistem Informasi Perpustakaan IT Del</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger small rounded-3 mb-3">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger small rounded-3 mb-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email Pustakawan</label>
                    <input type="email" name="email" class="form-control" value="pustakawan@del.ac.id" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Password</label>
                    <input type="password" name="password" class="form-control" value="password123" required>
                </div>
                <button type="submit" class="btn text-white w-100 fw-bold py-2 bg-purple rounded-3">
                    Masuk ke Sistem
                </button>
            </form>
        </div>
    </div>
</body>
</html>