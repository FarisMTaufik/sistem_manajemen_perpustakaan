<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\InventarisBuku;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarisController extends Controller
{
    /**
     * Menampilkan daftar inventaris buku.
     */
    public function index()
    {
        $inventaris = InventarisBuku::with('buku')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.inventaris.index', compact('inventaris'));
    }

    /**
     * Menampilkan daftar buku untuk keperluan inventarisasi.
     */
    public function bukuList(Request $request)
    {
        $query = Buku::query();

        // Filter pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Filter kondisi
        if ($request->has('kondisi') && $request->kondisi != '') {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter status inventaris
        if ($request->has('status') && $request->status != '') {
            $query->where('status_inventaris', $request->status);
        }

        $buku = $query->latest()->paginate(10);

        return view('admin.inventaris.buku-list', compact('buku'));
    }

    /**
     * Menampilkan form untuk membuat pemeriksaan inventaris baru.
     */
    public function create(Buku $buku)
    {
        return view('admin.inventaris.create', compact('buku'));
    }

    /**
     * Menyimpan data pemeriksaan inventaris baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faris_buku_id' => 'required|exists:faris_buku,id',
            'tanggal_pemeriksaan' => 'required|date|before_or_equal:today',
            'kondisi' => 'required|in:baik,rusak,perlu_perbaikan',
            'status_inventaris' => 'required|in:tersedia,dipinjam,dalam_perbaikan,hilang',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'tanggal_perbaikan' => 'nullable|date',
            'tanggal_selesai_perbaikan' => 'nullable|date|after_or_equal:tanggal_perbaikan',
            'perlu_tindakan_lanjut' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['petugas'] = Auth::user()->name;

        DB::beginTransaction();
        try {
            // Simpan data inventaris
            $inventaris = InventarisBuku::create($data);

            // Update informasi buku
            $buku = Buku::findOrFail($request->faris_buku_id);
            $buku->kondisi = $request->kondisi;
            $buku->status_inventaris = $request->status_inventaris;
            $buku->tanggal_inventaris = $request->tanggal_pemeriksaan;
            $buku->catatan_inventaris = $request->catatan;

            // Jika status dalam perbaikan, kurangi jumlah tersedia
            if ($request->status_inventaris === 'dalam_perbaikan' || $request->status_inventaris === 'hilang') {
                $buku->jumlah_tersedia = max(0, $buku->jumlah_tersedia - 1);
            }

            $buku->save();

            DB::commit();
            return redirect()->route('admin.inventaris.index')
                ->with('success', 'Data inventaris berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan detail inventaris.
     */
    public function show(InventarisBuku $inventari)
    {
        $inventari->load('buku');
        return view('admin.inventaris.show', compact('inventari'));
    }

    /**
     * Menampilkan form untuk mengedit data inventaris.
     */
    public function edit(InventarisBuku $inventari)
    {
        $inventari->load('buku');
        return view('admin.inventaris.edit', compact('inventari'));
    }

    /**
     * Memperbarui data inventaris.
     */
    public function update(Request $request, InventarisBuku $inventari)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_pemeriksaan' => 'required|date|before_or_equal:today',
            'kondisi' => 'required|in:baik,rusak,perlu_perbaikan',
            'status_inventaris' => 'required|in:tersedia,dipinjam,dalam_perbaikan,hilang',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'tanggal_perbaikan' => 'nullable|date',
            'tanggal_selesai_perbaikan' => 'nullable|date|after_or_equal:tanggal_perbaikan',
            'perlu_tindakan_lanjut' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        DB::beginTransaction();
        try {
            // Update data inventaris
            $inventari->update($data);

            // Update informasi buku
            $buku = Buku::findOrFail($inventari->faris_buku_id);

            // Jika status berubah, perlu update jumlah tersedia
            $statusLama = $inventari->getOriginal('status_inventaris');
            $statusBaru = $request->status_inventaris;

            // Kembalikan jumlah tersedia jika sebelumnya dalam perbaikan
            if (($statusLama === 'dalam_perbaikan' || $statusLama === 'hilang') &&
                ($statusBaru === 'tersedia' || $statusBaru === 'dipinjam')) {
                $buku->jumlah_tersedia = $buku->jumlah_tersedia + 1;
            }
            // Kurangi jumlah tersedia jika sekarang dalam perbaikan
            else if (($statusLama === 'tersedia' || $statusLama === 'dipinjam') &&
                     ($statusBaru === 'dalam_perbaikan' || $statusBaru === 'hilang')) {
                $buku->jumlah_tersedia = max(0, $buku->jumlah_tersedia - 1);
            }

            $buku->kondisi = $request->kondisi;
            $buku->status_inventaris = $request->status_inventaris;
            $buku->tanggal_inventaris = $request->tanggal_pemeriksaan;
            $buku->catatan_inventaris = $request->catatan;
            $buku->save();

            DB::commit();
            return redirect()->route('admin.inventaris.index')
                ->with('success', 'Data inventaris berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menghapus data inventaris.
     */
    public function destroy(InventarisBuku $inventari)
    {
        $inventari->delete();
        return redirect()->route('admin.inventaris.index')
            ->with('success', 'Data inventaris berhasil dihapus');
    }

    /**
     * Menampilkan laporan inventaris.
     */
    public function laporan(Request $request)
    {
        $query = InventarisBuku::with('buku');

        // Filter berdasarkan kondisi
        if ($request->has('kondisi') && $request->kondisi != '') {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_inventaris', $request->status);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->has('tanggal_awal') && $request->tanggal_awal != '') {
            $query->where('tanggal_pemeriksaan', '>=', $request->tanggal_awal);
        }

        if ($request->has('tanggal_akhir') && $request->tanggal_akhir != '') {
            $query->where('tanggal_pemeriksaan', '<=', $request->tanggal_akhir);
        }

        // Filter berdasarkan perlu tindakan lanjut
        if ($request->has('perlu_tindakan_lanjut') && $request->perlu_tindakan_lanjut != '') {
            $query->where('perlu_tindakan_lanjut', $request->perlu_tindakan_lanjut == '1');
        }

        // Handle export
        if ($request->has('export') && $request->export === 'pdf') {
            $inventaris = $query->orderBy('tanggal_pemeriksaan', 'desc')->get();
            $pdf = PDF::loadView('admin.inventaris.pdf.laporan', compact('inventaris'));
            return $pdf->download('laporan-inventaris-' . date('Y-m-d') . '.pdf');
        }

        $inventaris = $query->orderBy('tanggal_pemeriksaan', 'desc')->paginate(15);

        return view('admin.inventaris.laporan', compact('inventaris'));
    }

    /**
     * Menandai buku perlu perbaikan.
     */
    public function perluPerbaikan()
    {
        $buku = Buku::where('kondisi', 'perlu_perbaikan')
            ->orWhere('kondisi', 'rusak')
            ->orWhere('status_inventaris', 'dalam_perbaikan')
            ->latest()
            ->paginate(10);

        return view('admin.inventaris.perlu-perbaikan', compact('buku'));
    }

    /**
     * Mengelola perbaikan buku.
     */
    public function kelolaPerbaikan(Buku $buku)
    {
        $riwayatInventaris = $buku->inventaris()->latest()->get();
        return view('admin.inventaris.kelola-perbaikan', compact('buku', 'riwayatInventaris'));
    }

    /**
     * Memproses perbaikan buku.
     */
    public function prosesPerbaikan(Request $request, Buku $buku)
    {
        $request->validate([
            'tanggal_perbaikan' => 'required|date|before_or_equal:today',
            'catatan' => 'required|string',
            'estimasi_selesai' => 'required|date|after_or_equal:tanggal_perbaikan',
        ]);

        DB::beginTransaction();
        try {
            // Catat inventaris baru
            $inventaris = new InventarisBuku();
            $inventaris->faris_buku_id = $buku->id;
            $inventaris->tanggal_pemeriksaan = Carbon::today();
            $inventaris->kondisi = 'perlu_perbaikan';
            $inventaris->status_inventaris = 'dalam_perbaikan';
            $inventaris->catatan = $request->catatan;
            $inventaris->petugas = Auth::user()->name;
            $inventaris->tanggal_perbaikan = $request->tanggal_perbaikan;
            $inventaris->tanggal_selesai_perbaikan = $request->estimasi_selesai;
            $inventaris->perlu_tindakan_lanjut = true;
            $inventaris->save();

            // Update status buku
            $buku->status_inventaris = 'dalam_perbaikan';
            $buku->kondisi = 'perlu_perbaikan';
            $buku->catatan_inventaris = $request->catatan;
            $buku->save();

            DB::commit();
            return redirect()->route('admin.inventaris.perlu-perbaikan')
                ->with('success', 'Buku berhasil diproses untuk perbaikan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menyelesaikan perbaikan buku.
     */
    public function selesaikanPerbaikan(Request $request, Buku $buku)
    {
        $request->validate([
            'tanggal_selesai' => 'required|date|before_or_equal:today',
            'hasil_perbaikan' => 'required|string',
            'kondisi_setelah' => 'required|in:baik,rusak,perlu_perbaikan',
        ]);

        DB::beginTransaction();
        try {
            // Catat inventaris baru untuk menandai selesai perbaikan
            $inventaris = new InventarisBuku();
            $inventaris->faris_buku_id = $buku->id;
            $inventaris->tanggal_pemeriksaan = Carbon::today();
            $inventaris->kondisi = $request->kondisi_setelah;
            $inventaris->status_inventaris = 'tersedia';
            $inventaris->catatan = $request->hasil_perbaikan;
            $inventaris->petugas = Auth::user()->name;
            $inventaris->tanggal_selesai_perbaikan = $request->tanggal_selesai;
            $inventaris->perlu_tindakan_lanjut = $request->kondisi_setelah !== 'baik';
            $inventaris->save();

            // Update status buku
            $buku->status_inventaris = 'tersedia';
            $buku->kondisi = $request->kondisi_setelah;
            $buku->catatan_inventaris = $request->hasil_perbaikan;
            $buku->jumlah_tersedia = $buku->jumlah_tersedia + 1;  // Kembalikan jumlah tersedia
            $buku->save();

            DB::commit();
            return redirect()->route('admin.inventaris.perlu-perbaikan')
                ->with('success', 'Perbaikan buku berhasil diselesaikan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}