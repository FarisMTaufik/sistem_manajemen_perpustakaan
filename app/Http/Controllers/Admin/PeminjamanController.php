<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Denda;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftar peminjaman.
     */
    public function index()
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku.kategori'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Menampilkan form untuk membuat peminjaman baru.
     */
    public function create()
    {
        $anggota = Anggota::where('status', 'aktif')->get();
        $buku = Buku::where('jumlah_tersedia', '>', 0)->get();

        return view('admin.peminjaman.create', compact('anggota', 'buku'));
    }

    /**
     * Menyimpan peminjaman baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'faris_anggota_id' => 'required|exists:faris_anggota,id',
            'faris_buku_id' => 'required|exists:faris_buku,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
        ]);

        // Cek apakah buku tersedia
        $buku = Buku::findOrFail($request->faris_buku_id);
        if ($buku->jumlah_tersedia <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }

        // Cek apakah anggota masih aktif
        $anggota = Anggota::findOrFail($request->faris_anggota_id);
        if ($anggota->status !== 'aktif') {
            return redirect()->back()->with('error', 'Anggota tidak aktif.');
        }

        // Cek apakah anggota sudah meminjam buku yang sama dan belum dikembalikan
        $existingPeminjaman = Peminjaman::where('faris_anggota_id', $request->faris_anggota_id)
            ->where('faris_buku_id', $request->faris_buku_id)
            ->where('status', 'dipinjam')
            ->first();
            
        if ($existingPeminjaman) {
            return redirect()->back()->with('error', 'Anggota sudah meminjam buku ini dan belum mengembalikannya.');
        }

        DB::beginTransaction();
        try {
            // Buat peminjaman baru
            $peminjaman = Peminjaman::create([
                'faris_anggota_id' => $request->faris_anggota_id,
                'faris_buku_id' => $request->faris_buku_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                'status' => 'dipinjam',
            ]);

            // Kurangi jumlah buku tersedia
            $buku->jumlah_tersedia -= 1;
            $buku->save();

            DB::commit();
            return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail peminjaman.
     */
    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['anggota', 'buku.kategori', 'denda']);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    /**
     * Menampilkan form untuk mengedit peminjaman.
     */
    public function edit(Peminjaman $peminjaman)
    {
        $anggota = Anggota::where('status', 'aktif')->get();
        $buku = Buku::all();
        return view('admin.peminjaman.edit', compact('peminjaman', 'anggota', 'buku'));
    }

    /**
     * Update peminjaman.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:dipinjam,dikembalikan,terlambat',
        ]);

        $peminjaman->tanggal_jatuh_tempo = $request->tanggal_jatuh_tempo;
        $peminjaman->status = $request->status;
        $peminjaman->save();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    /**
     * Menghapus peminjaman.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dipinjam') {
            // Kembalikan jumlah buku tersedia jika peminjaman dihapus
            $buku = $peminjaman->buku;
            $buku->jumlah_tersedia += 1;
            $buku->save();
        }

        $peminjaman->delete();
        return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

    /**
     * Proses pengembalian buku.
     */
    public function pengembalian(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        DB::beginTransaction();
        try {
            $tanggalKembali = Carbon::now();
            $peminjaman->tanggal_kembali = $tanggalKembali;
            $peminjaman->status = 'dikembalikan';
            $peminjaman->save();

            // Tambahkan jumlah buku tersedia
            $buku = $peminjaman->buku;
            $buku->jumlah_tersedia += 1;
            $buku->save();

            // Cek keterlambatan dan buat denda jika terlambat
            if ($tanggalKembali->greaterThan($peminjaman->tanggal_jatuh_tempo)) {
                // Gunakan metode hitungHariTerlambat dari model
                $hariTerlambat = $peminjaman->hitungHariTerlambat();
                
                $tarifDenda = 1000; // Rp 1.000 per hari
                $jumlahDenda = $hariTerlambat * $tarifDenda;

                Denda::create([
                    'faris_peminjaman_id' => $peminjaman->id,
                    'jumlah_denda' => $jumlahDenda,
                    'status_pembayaran' => 'belum_dibayar',
                ]);
            }

            DB::commit();
            return redirect()->route('admin.peminjaman.index')->with('success', 'Buku berhasil dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Proses perpanjangan peminjaman.
     */
    public function perpanjang(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return redirect()->back()->with('error', 'Hanya peminjaman aktif yang dapat diperpanjang.');
        }

        if ($peminjaman->perpanjangan_count >= 2) {
            return redirect()->back()->with('error', 'Peminjaman sudah mencapai batas maksimum perpanjangan (2 kali).');
        }

        // Tambahkan 7 hari dari tanggal jatuh tempo saat ini
        $tanggalJatuhTempoBaru = Carbon::parse($peminjaman->tanggal_jatuh_tempo)->addDays(7);
        
        $peminjaman->tanggal_jatuh_tempo = $tanggalJatuhTempoBaru;
        $peminjaman->perpanjangan_count += 1;
        $peminjaman->save();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil diperpanjang.');
    }
} 