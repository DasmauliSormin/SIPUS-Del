<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Tampilkan daftar buku dengan fitur pencarian, filter kategori, dan eager loading.
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        // Filter Pencarian (Judul, Penulis, ISBN)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Filter Dropdown Kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $bukus = $query->latest()->paginate(10)->withQueryString();
        $kategoris = Kategori::all();

        return view('buku.index', compact('bukus', 'kategoris'));
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
            'judul'        => 'required|string|max:255',
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
        $buku->load('kategori');

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
            'judul'        => 'required|string|max:255',
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