<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class PeminjamanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'nim_peminjam' => 'required|string|max:15',
            'nama_peminjam' => 'required|string|max:255',
            'lama_pinjam' => 'required|integer|min:1|max:14',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Concurrency Locking: lockForUpdate() baris buku
                $buku = Buku::where('id', $request->buku_id)->lockForUpdate()->firstOrFail();

                // 2. Cek ketersediaan stok
                if ($buku->stok <= 0) {
                    throw new \Exception("Gagal Meminjam: Stok buku '{$buku->judul}' sedang habis!");
                }

                // 3. Cek apakah NIM yang sama masih meminjam buku ini & belum dikembalikan
                $sudahPinjam = Peminjaman::where('buku_id', $buku->id)
                    ->where('nim_peminjam', $request->nim_peminjam)
                    ->where('status', 'dipinjam')
                    ->exists();

                if ($sudahPinjam) {
                    throw new \Exception("NIM {$request->nim_peminjam} sedang meminjam buku ini dan belum mengembalikannya!");
                }

                // 4. Kurangi stok secara atomik
                $buku->decrement('stok');

                // 5. Catat transaksi peminjaman
                Peminjaman::create([
                    'kode_pinjam' => 'PNJ-' . strtoupper(Str::random(8)),
                    'buku_id' => $buku->id,
                    'nim_peminjam' => $request->nim_peminjam,
                    'nama_peminjam' => $request->nama_peminjam,
                    'tanggal_pinjam' => now()->toDateString(),
                    'tanggal_jatuh_tempo' => now()->addDays($request->lama_pinjam)->toDateString(),
                    'status' => 'dipinjam',
                ]);
            });

            return redirect()->back()->with('success', 'Transaksi peminjaman berhasil dicatat!');

        } catch (QueryException $e) {
            Log::error('QueryException pada Peminjaman: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem pada database.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function kembalikan($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // 1. Lock transaksi peminjaman & buku
                $peminjaman = Peminjaman::where('id', $id)->where('status', 'dipinjam')->firstOrFail();
                $buku = Buku::where('id', $peminjaman->buku_id)->lockForUpdate()->firstOrFail();

                // 2. Ubah status & isi tanggal kembali
                $peminjaman->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);

                // 3. Kembalikan stok buku
                $buku->increment('stok');
            });

            return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');

        } catch (QueryException $e) {
            Log::error('QueryException pada Pengembalian: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses pengembalian pada database.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Transaksi pengembalian gagal diproses.');
        }
    }
}