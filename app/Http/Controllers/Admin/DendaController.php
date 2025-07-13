<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DendaController extends Controller
{
    /**
     * Menampilkan daftar denda.
     */
    public function index()
    {
        $denda = Denda::with(['peminjaman.anggota', 'peminjaman.buku'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.denda.index', compact('denda'));
    }

    /**
     * Menampilkan detail denda.
     */
    public function show(Denda $denda)
    {
        $denda->load(['peminjaman.anggota', 'peminjaman.buku']);
        return view('admin.denda.show', compact('denda'));
    }

    /**
     * Menampilkan form untuk mengedit denda.
     */
    public function edit(Denda $denda)
    {
        $denda->load(['peminjaman.anggota', 'peminjaman.buku']);
        return view('admin.denda.edit', compact('denda'));
    }

    /**
     * Update denda.
     */
    public function update(Request $request, Denda $denda)
    {
        $request->validate([
            'jumlah_denda' => 'required|numeric|min:0',
            'status_pembayaran' => 'required|in:belum_dibayar,dibayar',
            'tanggal_pembayaran' => 'nullable|date|required_if:status_pembayaran,dibayar',
        ]);

        $denda->jumlah_denda = $request->jumlah_denda;
        $denda->status_pembayaran = $request->status_pembayaran;
        
        if ($request->status_pembayaran === 'dibayar') {
            $denda->tanggal_pembayaran = $request->tanggal_pembayaran ?? Carbon::now();
        } else {
            $denda->tanggal_pembayaran = null;
        }
        
        $denda->save();

        return redirect()->route('admin.denda.index')->with('success', 'Data denda berhasil diperbarui.');
    }

    /**
     * Menghapus denda.
     */
    public function destroy(Denda $denda)
    {
        $denda->delete();
        return redirect()->route('admin.denda.index')->with('success', 'Data denda berhasil dihapus.');
    }

    /**
     * Proses pembayaran denda.
     */
    public function bayar(Request $request, Denda $denda)
    {
        if ($denda->status_pembayaran === 'dibayar') {
            return redirect()->back()->with('error', 'Denda ini sudah dibayar.');
        }

        $denda->status_pembayaran = 'dibayar';
        $denda->tanggal_pembayaran = Carbon::now();
        $denda->save();

        return redirect()->route('admin.denda.index')->with('success', 'Pembayaran denda berhasil dicatat.');
    }

    /**
     * Menampilkan denda yang belum dibayar.
     */
    public function belumDibayar()
    {
        $denda = Denda::with(['peminjaman.anggota', 'peminjaman.buku'])
            ->where('status_pembayaran', 'belum_dibayar')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.denda.belum-dibayar', compact('denda'));
    }

    /**
     * Menampilkan denda yang sudah dibayar.
     */
    public function sudahDibayar()
    {
        $denda = Denda::with(['peminjaman.anggota', 'peminjaman.buku'])
            ->where('status_pembayaran', 'dibayar')
            ->orderBy('tanggal_pembayaran', 'desc')
            ->paginate(10);

        return view('admin.denda.sudah-dibayar', compact('denda'));
    }

    /**
     * Menampilkan laporan denda.
     */
    public function laporan(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ? Carbon::parse($request->tanggal_mulai) : Carbon::now()->startOfMonth();
        $tanggalAkhir = $request->tanggal_akhir ? Carbon::parse($request->tanggal_akhir) : Carbon::now();

        $denda = Denda::with(['peminjaman.anggota', 'peminjaman.buku'])
            ->whereBetween('created_at', [$tanggalMulai, $tanggalAkhir])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalDenda = $denda->sum('jumlah_denda');
        $totalDibayar = $denda->where('status_pembayaran', 'dibayar')->sum('jumlah_denda');
        $totalBelumDibayar = $denda->where('status_pembayaran', 'belum_dibayar')->sum('jumlah_denda');

        return view('admin.denda.laporan', compact('denda', 'tanggalMulai', 'tanggalAkhir', 'totalDenda', 'totalDibayar', 'totalBelumDibayar'));
    }

    /**
     * Menghitung denda otomatis untuk semua peminjaman yang terlambat.
     */
    public function hitungDendaOtomatis()
    {
        $now = Carbon::now();
        
        // Ambil semua peminjaman yang terlambat dan belum dikembalikan
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', $now)
            ->whereDoesntHave('denda')
            ->get();
        
        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($peminjamanTerlambat as $peminjaman) {
                // Perbaikan perhitungan hari keterlambatan
                $tanggalJatuhTempo = Carbon::parse($peminjaman->tanggal_jatuh_tempo);
                $hariTerlambat = $tanggalJatuhTempo->diffInDays($now);
                
                // Pastikan hariTerlambat minimal 1 hari
                $hariTerlambat = max(1, $hariTerlambat);
                
                $tarifDenda = 1000; // Rp 1.000 per hari
                $jumlahDenda = $hariTerlambat * $tarifDenda;
                
                Denda::create([
                    'faris_peminjaman_id' => $peminjaman->id,
                    'jumlah_denda' => $jumlahDenda,
                    'status_pembayaran' => 'belum_dibayar',
                ]);
                
                // Update status peminjaman menjadi terlambat
                $peminjaman->status = 'terlambat';
                $peminjaman->save();
                
                $count++;
            }
            
            DB::commit();
            return redirect()->route('admin.denda.index')->with('success', "Berhasil menghitung denda untuk {$count} peminjaman terlambat.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Menampilkan riwayat pembayaran denda.
     */
    public function riwayatPembayaran(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ? Carbon::parse($request->tanggal_mulai) : Carbon::now()->subMonth();
        $tanggalAkhir = $request->tanggal_akhir ? Carbon::parse($request->tanggal_akhir) : Carbon::now();
        
        $riwayatPembayaran = Denda::with(['peminjaman.anggota', 'peminjaman.buku'])
            ->where('status_pembayaran', 'dibayar')
            ->whereBetween('tanggal_pembayaran', [$tanggalMulai, $tanggalAkhir])
            ->orderBy('tanggal_pembayaran', 'desc')
            ->paginate(10);
            
        $totalPembayaran = Denda::where('status_pembayaran', 'dibayar')
            ->whereBetween('tanggal_pembayaran', [$tanggalMulai, $tanggalAkhir])
            ->sum('jumlah_denda');
            
        return view('admin.denda.riwayat-pembayaran', compact('riwayatPembayaran', 'tanggalMulai', 'tanggalAkhir', 'totalPembayaran'));
    }
} 