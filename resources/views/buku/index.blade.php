<x-layout>
    <div class="container py-4">
        {{-- Flash Alert Pesan --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Kartu Statistik (Query Builder DB::table) --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 bg-primary text-white p-3">
                    <div class="small opacity-75">Total Judul Buku</div>
                    <div class="fs-3 fw-bold">{{ $stats['total_judul'] }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 bg-success text-white p-3">
                    <div class="small opacity-75">Stok Tersedia</div>
                    <div class="fs-3 fw-bold">{{ $stats['stok_tersedia'] }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 bg-warning text-dark p-3">
                    <div class="small opacity-75">Sedang Dipinjam</div>
                    <div class="fs-3 fw-bold">{{ $stats['sedang_dipinjam'] }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-3 bg-info text-white p-3">
                    <div class="small opacity-75">Total Dikembalikan</div>
                    <div class="fs-3 fw-bold">{{ $stats['total_dikembalikan'] }}</div>
                </div>
            </div>
        </div>

        {{-- Filter & Search Form --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4 p-3">
            <form action="{{ route('buku.index') }}" method="GET" class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Cari Judul, Penulis, atau ISBN..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="kategori_id" class="form-select">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 fw-semibold">Filter</button>
                </div>
            </form>
        </div>

        {{-- Tabel Katalog Buku --}}
        <div class="card border-0 shadow-sm rounded-3 mb-5 overflow-hidden">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">Katalog Buku Perpustakaan</h5>
                @auth
                    <a href="{{ route('buku.create') }}" class="btn btn-sm btn-primary">+ Tambah Buku</a>
                @endauth
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ISBN</th>
                            <th>Judul Buku</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Total Dipinjam</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukus as $buku)
                            <tr>
                                <td><code>{{ $buku->isbn }}</code></td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $buku->judul }}</div>
                                    <div class="small text-muted">{{ $buku->penulis }} ({{ $buku->tahun_terbit }})</div>
                                </td>
                                <td><span class="badge bg-secondary">{{ $buku->kategori->nama_kategori ?? '-' }}</span></td>
                                <td>
                                    @if($buku->stok > 0)
                                        <span class="badge bg-success">{{ $buku->stok }} unit</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-info text-dark">{{ $buku->peminjamans_count }} kali</span></td>
                                <td class="text-end">
                                    <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                    
                                    {{-- Tombol Modal Pinjam --}}
                                    @if($buku->stok > 0)
                                        <button class="btn btn-sm btn-success ms-1" data-bs-toggle="modal" data-bs-target="#modalPinjam{{ $buku->id }}">
                                            Pinjam
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-secondary ms-1" disabled>Stok Habis</button>
                                    @endif

                                    @auth
                                        <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-outline-warning ms-1">Edit</a>
                                    @endauth
                                </td>
                            </tr>

                            {{-- Modal Form Peminjaman Buku --}}
                            <div class="modal fade" id="modalPinjam{{ $buku->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Peminjaman: {{ $buku->judul }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('peminjaman.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">NIM Peminjam</label>
                                                    <input type="text" name="nim_peminjam" class="form-control" placeholder="Contoh: 11322001" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Nama Peminjam</label>
                                                    <input type="text" name="nama_peminjam" class="form-control" placeholder="Nama Lengkap Mahasiswa" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Lama Pinjam (Hari)</label>
                                                    <input type="number" name="lama_pinjam" class="form-control" value="7" min="1" max="14" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Proses Peminjaman</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Buku tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white py-3">
                {{ $bukus->links() }}
            </div>
        </div>

        {{-- Tabel Riwayat Peminjaman & Pengembalian --}}
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark">Riwayat Peminjaman Terbaru</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Pinjam</th>
                            <th>Buku</th>
                            <th>Peminjam (NIM)</th>
                            <th>Tgl Pinjam</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $p)
                            <tr>
                                <td><code>{{ $p->kode_pinjam }}</code></td>
                                <td class="fw-semibold">{{ $p->buku->judul ?? '-' }}</td>
                                <td>{{ $p->nama_peminjam }} ({{ $p->nim_peminjam }})</td>
                                <td>{{ $p->tanggal_pinjam }}</td>
                                <td>
                                    @if($p->status == 'dipinjam')
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($p->status == 'dipinjam')
                                        <form action="{{ route('peminjaman.kembali', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">Kembalikan Buku</button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>