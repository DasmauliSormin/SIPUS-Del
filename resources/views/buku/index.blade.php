<x-layout>
    <x-slot:title>E-Katalog Perpustakaan Del</x-slot:title>

    <!-- Hero Banner & Search OPAC Portal UI -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden" 
         style="background: linear-gradient(135deg, #4C1D95 0%, #2E1065 100%); position: relative;">
        
        <!-- Watermark Logo Del -->
        <div style="position: absolute; right: -20px; bottom: -20px; opacity: 0.08; pointer-events: none;">
            <img src="{{ asset('images/DEL.png') }}" width="320" alt="Watermark Logo Del">
        </div>

        <div class="card-body p-4 p-lg-5 text-white position-relative z-1">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill mb-3">
                        <i class="bi bi-star-fill me-1"></i> OPAC E-Library IT Del
                    </span>
                    <h2 class="fw-extrabold display-6 mb-2" style="letter-spacing: -1px;">
                        Layanan Pustaka & Literatur Digital
                    </h2>
                    <p class="text-white-50 mb-4 fs-6">
                        Akses cepat ke seluruh koleksi jurnal, buku teks, modul perkuliahan, dan referensi penelitian kampus.
                    </p>

                    <!-- Interactive Live Search & Filter Form Bar -->
                    <form action="{{ route('buku.index') }}" method="GET" class="row g-2">
                        <div class="col-md-5">
                            <div class="input-group input-group-lg bg-white rounded-3 overflow-hidden shadow-sm">
                                <span class="input-group-text bg-white border-0 text-muted ps-3">
                                    <i class="bi bi-search fs-5"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-0 fs-6 shadow-none" 
                                       placeholder="Cari Judul, Penulis, ISBN..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="kategori_id" class="form-select form-select-lg border-0 fs-6 shadow-none rounded-3">
                                <option value="">-- Semua Kategori --</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold rounded-3 shadow-sm">
                                <i class="bi bi-funnel-fill me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Quick Action Widget Box -->
                <div class="col-lg-5 mt-4 mt-lg-0 text-lg-end">
                    <div class="p-4 rounded-4 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-25 text-start d-inline-block w-100" style="max-width: 380px;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-bold fs-6 text-warning"><i class="bi bi-lightning-charge-fill me-1"></i> Quick Action</span>
                            <span class="badge bg-success rounded-pill px-2 py-1 fs-8">Admin Access</span>
                        </div>
                        <h6 class="fw-bold mb-1">Punya Koleksi Baru?</h6>
                        <p class="small text-white-50 mb-3">Tambahkan koleksi pustaka baru ke dalam database perpustakaan.</p>
                        <a href="{{ route('buku.create') }}" class="btn btn-light btn-sm text-del fw-bold w-100 rounded-2 shadow-sm">
                            <i class="bi bi-plus-circle-fill me-1"></i> Input Data Buku Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards Bar -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 rounded-4 shadow-xs p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3 text-white" style="background-color: #4C1D95;">
                        <i class="bi bi-book fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block fs-8">Total Koleksi</small>
                        <h4 class="fw-extrabold mb-0 text-dark">{{ $bukus->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 rounded-4 shadow-xs p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3 bg-warning text-dark">
                        <i class="bi bi-layers fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block fs-8">Total Halaman</small>
                        <h4 class="fw-extrabold mb-0 text-dark">{{ $bukus->lastPage() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 rounded-4 shadow-xs p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3 bg-success text-white">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block fs-8">Status Pustaka</small>
                        <h5 class="fw-bold mb-0 text-success">Sirkulasi Aktif</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 rounded-4 shadow-xs p-3 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3 bg-info text-white">
                        <i class="bi bi-cpu fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block fs-8">Optimasi SQL</small>
                        <h6 class="fw-bold mb-0 text-dark">Eager Loaded</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Data Table OPAC View -->
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom-0">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 rounded-2 text-del" style="background-color: #F3E8FF;">
                    <i class="bi bi-journal-text fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Daftar Pustaka Tersedia</h5>
                    <small class="text-muted">Katalog resmi Perpustakaan Institut Teknologi Del</small>
                </div>
            </div>
            <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>ISBN</th>
                        <th>Detail Buku & Informasi</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Ketersediaan</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $index => $buku)
                        <tr>
                            <td class="ps-4 fw-bold text-muted" style="width: 50px;">
                                {{ $bukus->firstItem() + $index }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark font-monospace border px-2 py-1">
                                    {{ $buku->isbn }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('buku.show', $buku->id) }}" class="fw-bold text-dark text-decoration-none fs-6 hover-primary d-block">
                                    {{ $buku->judul }}
                                </a>
                                <div class="text-muted small">
                                    <i class="bi bi-building me-1"></i> Penerbit: <b>{{ $buku->penerbit }}</b> ({{ $buku->tahun_terbit }})
                                </div>
                            </td>
                            <td>
                                <span class="badge px-3 py-2 rounded-pill fw-semibold" style="background-color: #F3E8FF; color: #4C1D95;">
                                    <i class="bi bi-bookmark-fill me-1"></i>{{ $buku->kategori->nama_kategori }}
                                </span>
                            </td>
                            <td class="fw-medium text-secondary">
                                <i class="bi bi-person-circle me-1 text-muted"></i>{{ $buku->penulis }}
                            </td>
                            <td>
                                @if($buku->stok > 10)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold">
                                        {{ $buku->stok }} eks
                                    </span>
                                @elseif($buku->stok > 0)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill fw-bold">
                                        {{ $buku->stok }} eks
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill fw-bold">
                                        Habis
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-outline-info rounded-2 px-2 fw-semibold" title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-outline-warning rounded-2 px-2 fw-semibold" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-2 px-2 fw-semibold" title="Hapus Data">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-journal-x fs-1 text-muted d-block mb-2"></i>
                                <span class="text-muted fw-semibold">Data buku tidak ditemukan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Navigation -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="text-muted small">
            Menampilkan <b>{{ $bukus->firstItem() ?? 0 }}</b> - <b>{{ $bukus->lastItem() ?? 0 }}</b> dari <b>{{ $bukus->total() }}</b> total buku
        </div>
        <div>
            {{ $bukus->links() }}
        </div>
    </div>
</x-layout>