<x-layout>
    <div class="container py-4">
        {{-- Flash Alert Pesan --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Banner & Kartu Statistik --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 text-white p-3 h-100" style="background: linear-gradient(135deg, #4C1D95 0%, #3B0764 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50 text-uppercase fw-semibold">Total Judul Buku</div>
                            <div class="fs-2 fw-bold mt-1">{{ $stats['total_judul'] }}</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-3 p-3">
                            <i class="bi bi-journal-bookmark-fill fs-3 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 text-white p-3 h-100" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50 text-uppercase fw-semibold">Stok Tersedia</div>
                            <div class="fs-2 fw-bold mt-1">{{ $stats['stok_tersedia'] }}</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-3 p-3">
                            <i class="bi bi-box-seam-fill fs-3 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 text-dark p-3 h-100" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-dark-50 text-uppercase fw-semibold">Sedang Dipinjam</div>
                            <div class="fs-2 fw-bold mt-1 text-dark">{{ $stats['sedang_dipinjam'] }}</div>
                        </div>
                        <div class="bg-black bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-arrow-repeat fs-3 text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 text-white p-3 h-100" style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50 text-uppercase fw-semibold">Total Dikembalikan</div>
                            <div class="fs-2 fw-bold mt-1">{{ $stats['total_dikembalikan'] }}</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-3 p-3">
                            <i class="bi bi-check2-circle fs-3 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter & Search Bar --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-3 bg-white">
            <form action="{{ route('buku.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari Judul, Penulis, atau ISBN..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="kategori_id" class="form-select bg-light">
                        <option value="">-- Semua Kategori Perpustakaan --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn text-white w-100 fw-semibold shadow-sm" style="background-color: #4C1D95;">
                        <i class="bi bi-funnel-fill me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Katalog Utama --}}
        <div class="card border-0 shadow-sm rounded-4 mb-5 overflow-hidden">
            <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center text-white" style="background: linear-gradient(90deg, #4C1D95 0%, #6B21A8 100%);">
                <div class="d-flex align-items-center">
                    <i class="bi bi-book-half fs-4 me-2 text-warning"></i>
                    <h5 class="fw-bold mb-0">Katalog Koleksi Pustaka</h5>
                </div>
                @auth
                    <a href="{{ route('buku.create') }}" class="btn btn-sm btn-warning text-dark fw-bold rounded-3">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Buku
                    </a>
                @endauth
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ISBN</th>
                            <th>Informasi Buku</th>
                            <th>Kategori</th>
                            <th>Ketersediaan</th>
                            <th>Sirkulasi</th>
                            <th class="text-end pe-4">Aksi Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukus as $buku)
                            <tr>
                                <td class="ps-4"><span class="badge bg-light text-dark border font-monospace">{{ $buku->isbn }}</span></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $buku->judul }}</div>
                                    <div class="small text-muted"><i class="bi bi-person me-1"></i>{{ $buku->penulis }} &bull; {{ $buku->penerbit }} ({{ $buku->tahun_terbit }})</div>
                                </td>
                                <td><span class="badge rounded-pill bg-purple-subtle text-purple px-3 py-2" style="background-color: #F3E8FF; color: #6B21A8;">{{ $buku->kategori->nama_kategori ?? '-' }}</span></td>
                                <td>
                                    @if($buku->stok > 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i>Tersedia ({{ $buku->stok }})</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i>Stok Habis</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-light text-secondary border px-3 py-2 rounded-pill"><i class="bi bi-arrow-repeat me-1"></i>{{ $buku->peminjamans_count }}x Dipinjam</span></td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-outline-secondary rounded-3">Detail</a>
                                    
                                    @if($buku->stok > 0)
                                        <button type="button" class="btn btn-sm text-white rounded-3 ms-1" style="background-color: #059669;" data-bs-toggle="modal" data-bs-target="#modalPinjam{{ $buku->id }}">
                                            <i class="bi bi-journal-plus me-1"></i> Pinjam
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-light border text-muted rounded-3 ms-1" disabled>Habis</button>
                                    @endif

                                    @auth
                                        <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-outline-warning rounded-3 ms-1"><i class="bi bi-pencil"></i></a>
                                    @endauth
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block text-secondary mb-2"></i>
                                    Koleksi buku tidak ditemukan atau belum ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white py-3 px-4">
                {{ $bukus->links() }}
            </div>
        </div>

        {{-- Tabel Riwayat Peminjaman --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Sirkulasi Peminjaman Terbaru</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Kode Transaksi</th>
                            <th>Judul Buku</th>
                            <th>Peminjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Status Transaksi</th>
                            <th class="text-end pe-4">Aksi Pengembalian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $p)
                            <tr>
                                <td class="ps-4"><code class="fw-bold text-primary">{{ $p->kode_pinjam }}</code></td>
                                <td class="fw-semibold text-dark">{{ $p->buku->judul ?? '-' }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $p->nama_peminjam }}</div>
                                    <div class="small text-muted">NIM: {{ $p->nim_peminjam }}</div>
                                </td>
                                <td><i class="bi bi-calendar3 me-1 text-muted"></i>{{ $p->tanggal_pinjam }}</td>
                                <td>
                                    @if($p->status == 'dipinjam')
                                        <span class="badge bg-warning-subtle text-dark border border-warning px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i>Dipinjam</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill"><i class="bi bi-check2-all me-1"></i>Dikembalikan</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($p->status == 'dipinjam')
                                        <form action="{{ route('peminjaman.kembali', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-3">
                                                <i class="bi bi-box-arrow-in-left me-1"></i> Kembalikan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small"><i class="bi bi-check-circle-fill text-success me-1"></i>Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada riwayat peminjaman buku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Form Peminjaman --}}
    @foreach($bukus as $buku)
        @if($buku->stok > 0)
            <div class="modal fade" id="modalPinjam{{ $buku->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="modal-header text-white" style="background: linear-gradient(135deg, #4C1D95 0%, #2E1065 100%);">
                            <h5 class="modal-title fw-bold"><i class="bi bi-journal-plus me-2"></i>Peminjaman Buku</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('peminjaman.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                            <div class="modal-body p-4">
                                <div class="alert alert-light border rounded-3 mb-3">
                                    <div class="small text-muted">Judul Buku:</div>
                                    <div class="fw-bold text-dark">{{ $buku->judul }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">NIM Peminjam</label>
                                    <input type="text" name="nim_peminjam" class="form-control" placeholder="Contoh: 11322001" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Nama Lengkap Mahasiswa</label>
                                    <input type="text" name="nama_peminjam" class="form-control" placeholder="Masukkan nama peminjam" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Lama Peminjaman (Hari)</label>
                                    <input type="number" name="lama_pinjam" class="form-control" value="7" min="1" max="14" required>
                                </div>
                            </div>
                            <div class="modal-footer bg-light p-3">
                                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn text-white fw-bold rounded-3" style="background-color: #4C1D95;">Proses Peminjaman</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</x-layout>