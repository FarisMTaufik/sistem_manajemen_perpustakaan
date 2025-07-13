<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftar peminjaman anggota yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        
        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Data anggota tidak ditemukan.');
        }
        
        $peminjaman = Peminjaman::with(['buku', 'denda'])
            ->where('faris_anggota_id', $anggota->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('anggota.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Menampilkan detail peminjaman.
     */
    public function show(Peminjaman $peminjaman)
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        
        // Pastikan anggota hanya melihat peminjaman miliknya
        if ($peminjaman->faris_anggota_id !== $anggota->id) {
            return redirect()->route('anggota.peminjaman.index')->with('error', 'Anda tidak memiliki akses ke peminjaman ini.');
        }
        
        $peminjaman->load(['buku', 'denda']);
        return view('anggota.peminjaman.show', compact('peminjaman'));
    }

    /**
     * Menampilkan riwayat peminjaman anggota.
     */
    public function riwayat()
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        
        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Data anggota tidak ditemukan.');
        }
        
        $riwayatPeminjaman = Peminjaman::with(['buku', 'denda'])
            ->where('faris_anggota_id', $anggota->id)
            ->where('status', 'dikembalikan')
            ->orderBy('tanggal_kembali', 'desc')
            ->paginate(10);

        return view('anggota.peminjaman.riwayat', compact('riwayatPeminjaman'));
    }

    /**
     * Menampilkan peminjaman yang sedang aktif.
     */
    public function aktif()
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        
        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Data anggota tidak ditemukan.');
        }
        
        $peminjamanAktif = Peminjaman::with(['buku'])
            ->where('faris_anggota_id', $anggota->id)
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->paginate(10);

        return view('anggota.peminjaman.aktif', compact('peminjamanAktif'));
    }

    /**
     * Menampilkan peminjaman yang terlambat.
     */
    public function terlambat()
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        
        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Data anggota tidak ditemukan.');
        }
        
        $now = Carbon::now();
        
        $peminjamanTerlambat = Peminjaman::with(['buku', 'denda'])
            ->where('faris_anggota_id', $anggota->id)
            ->where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', $now)
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->paginate(10);

        return view('anggota.peminjaman.terlambat', compact('peminjamanTerlambat'));
    }

    /**
     * Menampilkan denda yang belum dibayar.
     */
    public function denda()
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        
        if (!$anggota) {
            return redirect()->route('anggota.dashboard')->with('error', 'Data anggota tidak ditemukan.');
        }
        
        $peminjaman = Peminjaman::with(['buku', 'denda'])
            ->where('faris_anggota_id', $anggota->id)
            ->whereHas('denda', function ($query) {
                $query->where('status_pembayaran', 'belum_dibayar');
            })
            ->paginate(10);

        return view('anggota.peminjaman.denda', compact('peminjaman'));
    }

    /**
     * Proses permintaan perpanjangan peminjaman.
     */
    public function perpanjang(Request $request, Peminjaman $peminjaman)
    {
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        
        // Pastikan anggota hanya bisa memperpanjang peminjaman miliknya
        if ($peminjaman->faris_anggota_id !== $anggota->id) {
            return redirect()->route('anggota.peminjaman.index')->with('error', 'Anda tidak memiliki akses ke peminjaman ini.');
        }
        
        if ($peminjaman->status !== 'dipinjam') {
            return redirect()->back()->with('error', 'Hanya peminjaman aktif yang dapat diperpanjang.');
        }

        if ($peminjaman->perpanjangan_count >= 2) {
            return redirect()->back()->with('error', 'Peminjaman sudah mencapai batas maksimum perpanjangan (2 kali).');
        }
        
        // Cek apakah sudah terlambat
        $now = Carbon::now();
        if ($now->greaterThan($peminjaman->tanggal_jatuh_tempo)) {
            return redirect()->back()->with('error', 'Peminjaman yang sudah terlambat tidak dapat diperpanjang.');
        }

        // Tambahkan 7 hari dari tanggal jatuh tempo saat ini
        $tanggalJatuhTempoBaru = Carbon::parse($peminjaman->tanggal_jatuh_tempo)->addDays(7);
        
        $peminjaman->tanggal_jatuh_tempo = $tanggalJatuhTempoBaru;
        $peminjaman->perpanjangan_count += 1;
        $peminjaman->save();

        return redirect()->route('anggota.peminjaman.aktif')->with('success', 'Peminjaman berhasil diperpanjang.');
    }
} 