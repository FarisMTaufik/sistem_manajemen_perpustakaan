<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\Buku;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard anggota.
     */
    public function index()
    {
        $user = Auth::user();
        $anggota = $user->anggota;
        
        // Riwayat peminjaman
        $peminjaman = Peminjaman::where('faris_anggota_id', $anggota->id)
            ->with('buku')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Peminjaman aktif
        $peminjamanAktif = Peminjaman::where('faris_anggota_id', $anggota->id)
            ->where('status', 'dipinjam')
            ->with('buku')
            ->get();
            
        // Denda yang belum dibayar
        $dendaBelumBayar = Denda::whereHas('peminjaman', function($query) use ($anggota) {
                $query->where('faris_anggota_id', $anggota->id);
            })
            ->where('status_pembayaran', 'belum_dibayar')
            ->with('peminjaman.buku')
            ->get();
            
        // Rekomendasi buku (bisa disesuaikan berdasarkan algoritma rekomendasi tertentu)
        $rekomendasiBuku = Buku::inRandomOrder()->take(4)->get();
        
        // Tanggal kedaluwarsa keanggotaan
        $tanggalKedaluwarsa = $anggota->tanggal_kedaluwarsa;
        $masaBerlaku = (int)Carbon::now()->diffInDays($tanggalKedaluwarsa, false);
        
        return view('anggota.dashboard', compact(
            'anggota',
            'peminjaman',
            'peminjamanAktif',
            'dendaBelumBayar',
            'rekomendasiBuku',
            'tanggalKedaluwarsa',
            'masaBerlaku'
        ));
    }
}
