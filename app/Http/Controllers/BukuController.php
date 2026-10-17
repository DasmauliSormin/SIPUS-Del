<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class BukuController extends Controller
{
    /**
     * Tampilkan daftar buku dengan fitur pencarian when(), agregasi withCount, 
     * statistik DB::table(), serta manajemen Session & Cookie.
     */
    public function index(Request $request)
    {
        // 1. Simpan histori pencarian terakhir ke Session & Cookie (Spesifikasi Minggu 6)
        if ($request->filled('search')) {
            session(['last_search' => $request->search]);
            Cookie::queue('last_search_keyword', $request->search, 60);
        }

        // 2. Query katalog dengan Eager Loading & Agregasi withCount('peminjamans')
        $query = Buku::with('kategori')->withCount('peminjamans');

        // Filter Pencarian Dinamis menggunakan when()
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($sub) use ($search) {
                $sub->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        });

        // Filter Dropdown Kategori Dinamis menggunakan when()
        $query->when($request->filled('kategori_id'), function ($q) use ($request) {
            $q->where('kategori_id', $request->kategori_id);
        });

        $bukus = $query->latest()->paginate(10)->withQueryString();
        $kategoris = Kategori::all();

        // 3. Kartu Statistik Perpustakaan menggunakan Fluent Query Builder DB::table()
        $stats = [
            'total_judul'        => DB::table('bukus')->count(),
            'stok_tersedia'      => DB::table('bukus')->sum('stok'),
            'sedang_dipinjam'    => DB::table('peminjamans')->where('status', 'dipinjam')->count(),
            'total_dikembalikan' => DB::table('peminjamans')->where('status', 'dikembalikan')->count(),
        ];

        // 4. Ambil transaksi peminjaman terbaru untuk tampilan antarmuka
        $peminjamans = Peminjaman::with('buku')->latest()->take(10)->get();

        return view('buku.index', compact('bukus', 'kategoris', 'stats', 'peminjamans'));
    }

    /**
     * Tampilkan form untuk menambah buku baru.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('buku.create', compact('kategoris'));
    }

    /**
     * Simpan buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'  => 'required|exists:kategoris,id',
            'isbn'         => 'required|unique:bukus,isbn',
            'judul'        => 'required|string|min:5|max:255',
            'penulis'      => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric|digits:4',
            'stok'         => 'required|integer|min:0',
            'sinopsis'     => 'nullable|string',
        ]);

        Buku::create($validated);

        return redirect()->route('buku.index')->with('success', 'Buku baru berhasil ditambahkan ke katalog!');
    }

    /**
     * Tampilkan detail buku beserta rekomendasi buku terkait.
     */
    public function show(Buku $buku)
    {
        $buku->load('kategori')->loadCount('peminjamans');

        $bukuTerkait = Buku::where('kategori_id', $buku->kategori_id)
            ->where('id', '!=', $buku->id)
            ->take(4)
            ->get();

        return view('buku.show', compact('buku', 'bukuTerkait'));
    }

    /**
     * Tampilkan form edit buku.
     */
    public function edit(Buku $buku)
    {
        $kategoris = Kategori::all();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    /**
     * Perbarui data buku di database.
     */
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'kategori_id'  => 'required|exists:kategoris,id',
            'isbn'         => 'required|unique:bukus,isbn,' . $buku->id,
            'judul'        => 'required|string|min:5|max:255',
            'penulis'      => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|numeric|digits:4',
            'stok'         => 'required|integer|min:0',
            'sinopsis'     => 'nullable|string',
        ]);

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    /**
     * Hapus buku dari database.
     */
    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus dari sistem!');
    }
}