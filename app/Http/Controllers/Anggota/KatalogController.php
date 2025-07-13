<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Anggota;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    /**
     * Menampilkan daftar buku dalam katalog.
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori');
        
        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('faris_kategori_id', $request->kategori);
        }
        
        // Filter berdasarkan ketersediaan
        if ($request->has('tersedia') && $request->tersedia == '1') {
            $query->where('jumlah_tersedia', '>', 0);
        }
        
        // Filter berdasarkan kondisi
        if ($request->has('kondisi') && $request->kondisi != '') {
            $query->where('kondisi', $request->kondisi);
        }
        
        // Urutkan berdasarkan parameter
        $sortBy = $request->sort_by ?? 'judul';
        $sortOrder = $request->sort_order ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);
        
        $buku = $query->paginate(12);
        $kategori = Kategori::all();
        
        return view('anggota.katalog.index', compact('buku', 'kategori'));
    }
    
    /**
     * Menampilkan detail buku.
     */
    public function show(Buku $buku)
    {
        $buku->load(['kategori', 'inventaris' => function($query) {
            $query->latest('tanggal_pemeriksaan');
        }]);
        
        // Dapatkan buku terkait berdasarkan kategori yang sama
        $bukuTerkait = Buku::where('faris_kategori_id', $buku->faris_kategori_id)
            ->where('id', '!=', $buku->id)
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        return view('anggota.katalog.show', compact('buku', 'bukuTerkait'));
    }
    
    /**
     * Mencari buku berdasarkan kata kunci.
     */
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        
        $query = Buku::with('kategori');
        
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('penulis', 'like', "%{$keyword}%")
                  ->orWhere('penerbit', 'like', "%{$keyword}%")
                  ->orWhere('isbn', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }
        
        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('faris_kategori_id', $request->kategori);
        }
        
        // Filter berdasarkan ketersediaan
        if ($request->has('tersedia') && $request->tersedia == '1') {
            $query->where('jumlah_tersedia', '>', 0);
        }
        
        $buku = $query->paginate(12);
        $kategori = Kategori::all();
        
        return view('anggota.katalog.search', compact('buku', 'kategori', 'keyword'));
    }

    /**
     * Proses peminjaman buku oleh anggota.
     */
    public function pinjam(Request $request, Buku $buku)
    {
        // Cek apakah buku tersedia
        if ($buku->jumlah_tersedia <= 0) {
            return redirect()->back()->with('error', 'Maaf, buku ini tidak tersedia untuk dipinjam.');
        }

        // Cek apakah anggota sudah terdaftar
        $user = Auth::user();
        $anggota = Anggota::where('user_id', $user->id)->first();
        
        if (!$anggota) {
            return redirect()->back()->with('error', 'Data anggota tidak ditemukan. Silakan lengkapi profil Anda terlebih dahulu.');
        }

        // Cek apakah anggota memiliki peminjaman aktif untuk buku yang sama
        $peminjamanAktif = Peminjaman::where('faris_anggota_id', $anggota->id)
            ->where('faris_buku_id', $buku->id)
            ->where('status', 'dipinjam')
            ->first();
            
        if ($peminjamanAktif) {
            return redirect()->back()->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya.');
        }

        // Cek jumlah peminjaman aktif anggota (batasi maksimal 3 buku)
        $jumlahPeminjamanAktif = Peminjaman::where('faris_anggota_id', $anggota->id)
            ->where('status', 'dipinjam')
            ->count();
            
        if ($jumlahPeminjamanAktif >= 3) {
            return redirect()->back()->with('error', 'Anda sudah meminjam maksimal 3 buku. Silakan kembalikan salah satu buku terlebih dahulu.');
        }

        // Cek apakah anggota memiliki denda yang belum dibayar
        $dendaBelumBayar = Peminjaman::where('faris_anggota_id', $anggota->id)
            ->whereHas('denda', function($query) {
                $query->where('status_pembayaran', 'belum_dibayar');
            })
            ->exists();
            
        if ($dendaBelumBayar) {
            return redirect()->back()->with('error', 'Anda memiliki denda yang belum dibayar. Silakan lunasi denda terlebih dahulu.');
        }

        // Buat peminjaman baru
        $tanggalPinjam = Carbon::now();
        $tanggalJatuhTempo = Carbon::now()->addDays(7); // Durasi peminjaman 7 hari

        $peminjaman = new Peminjaman();
        $peminjaman->faris_anggota_id = $anggota->id;
        $peminjaman->faris_buku_id = $buku->id;
        $peminjaman->tanggal_pinjam = $tanggalPinjam;
        $peminjaman->tanggal_jatuh_tempo = $tanggalJatuhTempo;
        $peminjaman->status = 'dipinjam';
        $peminjaman->perpanjangan_count = 0;
        $peminjaman->save();

        // Kurangi jumlah buku tersedia
        $buku->jumlah_tersedia -= 1;
        $buku->save();

        return redirect()->route('anggota.peminjaman.aktif')->with('success', 'Buku berhasil dipinjam. Silakan ambil buku di perpustakaan dengan menunjukkan ID peminjaman: ' . $peminjaman->id);
    }
} 