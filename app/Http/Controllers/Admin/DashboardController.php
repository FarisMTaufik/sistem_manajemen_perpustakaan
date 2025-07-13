<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\InventarisBuku;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index()
    {
        // Mengambil data untuk statistik
        $totalAnggota = Anggota::count();
        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();
        $totalPeminjaman = Peminjaman::count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanTerlambat = Peminjaman::where('status', 'terlambat')->count();
        
        // Data untuk buku yang perlu perbaikan
        $bukuPerluPerbaikan = InventarisBuku::where('kondisi', 'rusak')
            ->count();
            
        // Data untuk denda yang belum dibayar
        $dendaBelumBayar = Denda::where('status_pembayaran', 'belum_dibayar')->count();
        
        // Data untuk grafik peminjaman bulanan
        $grafikPeminjaman = $this->getGrafikPeminjaman();
        
        // Data untuk grafik kategori buku
        $grafikKategori = $this->getGrafikKategori();
        
        // Data peminjaman terbaru
        $peminjamanTerbaru = Peminjaman::with(['anggota', 'buku'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Data peminjaman yang akan jatuh tempo dalam 3 hari ke depan
        $peminjamanJatuhTempo = Peminjaman::with(['anggota', 'buku'])
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_jatuh_tempo', '>=', Carbon::today())
            ->whereDate('tanggal_jatuh_tempo', '<=', Carbon::today()->addDays(3))
            ->orderBy('tanggal_jatuh_tempo')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalAnggota',
            'totalBuku',
            'totalKategori',
            'totalPeminjaman',
            'peminjamanAktif',
            'peminjamanTerlambat',
            'bukuPerluPerbaikan',
            'dendaBelumBayar',
            'grafikPeminjaman',
            'grafikKategori',
            'peminjamanTerbaru',
            'peminjamanJatuhTempo'
        ));
    }
    
    /**
     * Mendapatkan data untuk grafik peminjaman bulanan
     */
    private function getGrafikPeminjaman()
    {
        $bulanIni = Carbon::now();
        $labels = [];
        $data = [];
        
        // Mengambil data peminjaman 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanIni->copy()->subMonths($i);
            $labels[] = $bulan->format('M');
            
            $jumlahPeminjaman = Peminjaman::whereYear('tanggal_pinjam', $bulan->year)
                ->whereMonth('tanggal_pinjam', $bulan->month)
                ->count();
                
            $data[] = $jumlahPeminjaman;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Mendapatkan data untuk grafik kategori buku
     */
    private function getGrafikKategori()
    {
        $kategori = Kategori::withCount('buku')
            ->orderBy('buku_count', 'desc')
            ->take(5)
            ->get();
            
        $labels = $kategori->pluck('nama_kategori')->toArray();
        $data = $kategori->pluck('buku_count')->toArray();
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
