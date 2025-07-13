<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard staff.
     */
    public function index()
    {
        // Mengambil data untuk statistik
        $totalAnggota = Anggota::count();
        $totalBuku = Buku::count();
        $peminjamanHariIni = Peminjaman::whereDate('tanggal_pinjam', today())->count();
        $pengembalianHariIni = Peminjaman::whereDate('tanggal_kembali', today())->count();
        $peminjamanTerlambat = Peminjaman::where('status', 'terlambat')->count();
        
        // Data peminjaman terbaru
        $peminjamanTerbaru = Peminjaman::with(['anggota', 'buku'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('staff.dashboard', compact(
            'totalAnggota',
            'totalBuku',
            'peminjamanHariIni',
            'pengembalianHariIni',
            'peminjamanTerlambat',
            'peminjamanTerbaru'
        ));
    }
}
