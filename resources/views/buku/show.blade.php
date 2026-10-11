<x-layout>
    <x-slot:title>Detail - {{ $buku->judul }}</x-slot:title>

    <div class="mb-3">
        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog
        </a>
    </div>

    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="p-5 rounded-4 text-white d-flex align-items-center justify-content-center" 
                     style="background: linear-gradient(135deg, #4C1D95 0%, #2E1065 100%); min-height: 280px;">
                    <div>
                        <i class="bi bi-book display-1 text-warning"></i>
                        <h6 class="mt-3 text-warning fw-bold">{{ $buku->kategori->nama_kategori }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <span class="badge bg-purple-subtle text-del px-3 py-2 rounded-pill fw-bold mb-2" style="background-color: #F3E8FF;">
                    ISBN: {{ $buku->isbn }}
                </span>
                <h3 class="fw-extrabold text-dark mb-2">{{ $buku->judul }}</h3>
                <p class="text-muted mb-3"><i class="bi bi-person-fill me-1"></i> Penulis: <b>{{ $buku->penulis }}</b></p>

                <div class="row g-3 mb-4">
                    <div class="col-6 col-sm-4">
                        <small class="text-muted d-block">Penerbit</small>
                        <span class="fw-semibold text-dark">{{ $buku->penerbit }}</span>
                    </div>
                    <div class="col-6 col-sm-4">
                        <small class="text-muted d-block">Tahun Terbit</small>
                        <span class="fw-semibold text-dark">{{ $buku->tahun_terbit }}</span>
                    </div>
                    <div class="col-6 col-sm-4">
                        <small class="text-muted d-block">Sisa Stok</small>
                        <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill fw-bold">
                            {{ $buku->stok }} Eksemplar
                        </span>
                    </div>
                </div>

                <h6 class="fw-bold text-dark">Sinopsis / Ringkasan Buku</h6>
                <p class="text-secondary small leading-relaxed">{{ $buku->sinopsis ?? 'Belum ada sinopsis untuk buku ini.' }}</p>

                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-warning fw-bold px-4 rounded-3">
                        <i class="bi bi-pencil-square me-1"></i> Edit Data
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Buku Terkait Section -->
    @if($bukuTerkait->count() > 0)
        <h5 class="fw-bold text-del mb-3"><i class="bi bi-bookmarks me-2"></i>Buku Lain di Kategori Yang Sama</h5>
        <div class="row g-3">
            @foreach($bukuTerkait as $item)
                <div class="col-md-3">
                    <div class="card border-0 rounded-4 shadow-xs p-3 h-100 bg-white">
                        <span class="badge bg-light text-dark font-monospace mb-2 align-self-start">{{ $item->isbn }}</span>
                        <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $item->judul }}</h6>
                        <small class="text-muted d-block mb-3">{{ $item->penulis }}</small>
                        <a href="{{ route('buku.show', $item->id) }}" class="btn btn-outline-primary btn-sm rounded-pill mt-auto fw-semibold">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layout>