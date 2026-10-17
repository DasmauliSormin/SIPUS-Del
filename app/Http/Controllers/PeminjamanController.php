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
        $validated = $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'nim_peminjam' => 'required|string|min:6|max:15',
            'nama_peminjam' => 'required|string|min:3|max:150',
            'lama_pinjam' => 'required|integer|min:1|max:14',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                // 1. Pessimistic Locking untuk mencegah Race Condition
                $buku = Buku::where('id', $validated['buku_id'])->lockForUpdate()->firstOrFail();

                // 2. Cek stok
                if ($buku->stok < 1) {
                    throw new \RuntimeException("Gagal Meminjam: Stok buku '{$buku->judul}' telah habis!");
                }

                // 3. Cek apakah NIM yang sama sedang meminjam buku yang sama & belum dikembalikan
                $sedangPinjam = Peminjaman::where('buku_id', $buku->id)
                    ->where('nim_peminjam', $validated['nim_peminjam'])
                    ->where('status', 'dipinjam')
                    ->exists();

                if ($sedangPinjam) {
                    throw new \RuntimeException("NIM {$validated['nim_peminjam']} sedang meminjam buku ini dan belum mengembalikannya!");
                }

                // 4. Kurangi stok secara atomik
                $buku->decrement('stok');

                // 5. Catat transaksi (Konversi lama_pinjam ke integer)
                $lamaPinjam = (int) $validated['lama_pinjam'];

                Peminjaman::create([
                    'kode_pinjam' => 'PNJ-' . strtoupper(Str::random(8)),
                    'buku_id' => $buku->id,
                    'nim_peminjam' => strtoupper(trim($validated['nim_peminjam'])),
                    'nama_peminjam' => $validated['nama_peminjam'],
                    'tanggal_pinjam' => now()->toDateString(),
                    'tanggal_jatuh_tempo' => now()->addDays($lamaPinjam)->toDateString(),
                    'status' => 'dipinjam',
                ]);
            });

            return back()->with('success', 'Transaksi peminjaman berhasil dicatat!');

        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION PEMINJAMAN]: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan basis data saat memproses peminjaman.');
        }
    }

    public function kembalikan($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $peminjaman = Peminjaman::where('id', $id)->where('status', 'dipinjam')->firstOrFail();
                $buku = Buku::where('id', $peminjaman->buku_id)->lockForUpdate()->firstOrFail();

                $peminjaman->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);

                $buku->increment('stok');
            });

            return back()->with('success', 'Buku berhasil dikembalikan!');

        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION PENGEMBALIAN]: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pengembalian pada basis data.');
        } catch (\Exception $e) {
            return back()->with('error', 'Transaksi pengembalian gagal diproses.');
        }
    }
}