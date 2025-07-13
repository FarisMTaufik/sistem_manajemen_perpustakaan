<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar buku dengan opsi pencarian.
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        // Filter pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('faris_kategori_id', $request->kategori);
        }

        // Filter tahun terbit
        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun_terbit', $request->tahun);
        }

        // Filter kondisi
        if ($request->has('kondisi') && $request->kondisi != '') {
            $query->where('kondisi', $request->kondisi);
        }

        $buku = $query->latest()->paginate(10);
        $kategori = Kategori::all();

        return view('admin.buku.index', compact('buku', 'kategori'));
    }

    /**
     * Menampilkan form untuk membuat buku baru.
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.buku.create', compact('kategori'));
    }

    /**
     * Menyimpan buku baru ke database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20|unique:faris_buku,isbn',
            'faris_kategori_id' => 'required|exists:faris_kategori,id',
            'jumlah_salinan' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak,perlu_perbaikan',
            'deskripsi' => 'nullable|string',
            'gambar_sampul' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // Set jumlah tersedia sama dengan jumlah salinan pada awal
        $data['jumlah_tersedia'] = $request->jumlah_salinan;
        
        // Upload gambar sampul jika ada
        if ($request->hasFile('gambar_sampul')) {
            $gambar = $request->file('gambar_sampul');
            $namaGambar = time() . '.' . $gambar->getClientOriginalExtension();
            
            // Simpan gambar ke disk 'public' di folder 'sampul'
            $path = $gambar->storeAs('sampul', $namaGambar, 'public');
            $data['gambar_sampul'] = $namaGambar;
        }

        Buku::create($data);

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    /**
     * Menampilkan detail buku.
     */
    public function show(Buku $buku)
    {
        return view('admin.buku.show', compact('buku'));
    }

    /**
     * Menampilkan form untuk mengedit buku.
     */
    public function edit(Buku $buku)
    {
        $kategori = Kategori::all();
        return view('admin.buku.edit', compact('buku', 'kategori'));
    }

    /**
     * Memperbarui buku di database.
     */
    public function update(Request $request, Buku $buku)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20|unique:faris_buku,isbn,' . $buku->id,
            'faris_kategori_id' => 'required|exists:faris_kategori,id',
            'jumlah_salinan' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak,perlu_perbaikan',
            'deskripsi' => 'nullable|string',
            'gambar_sampul' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // Hitung selisih jumlah salinan untuk update jumlah tersedia
        $selisih = $request->jumlah_salinan - $buku->jumlah_salinan;
        $data['jumlah_tersedia'] = $buku->jumlah_tersedia + $selisih;
        
        // Pastikan jumlah tersedia tidak negatif
        if ($data['jumlah_tersedia'] < 0) {
            $data['jumlah_tersedia'] = 0;
        }
        
        // Upload gambar sampul baru jika ada
        if ($request->hasFile('gambar_sampul')) {
            // Hapus gambar lama jika ada
            if ($buku->gambar_sampul) {
                Storage::disk('public')->delete('sampul/' . $buku->gambar_sampul);
            }
            
            $gambar = $request->file('gambar_sampul');
            $namaGambar = time() . '.' . $gambar->getClientOriginalExtension();
            
            // Simpan gambar ke disk 'public' di folder 'sampul'
            $path = $gambar->storeAs('sampul', $namaGambar, 'public');
            $data['gambar_sampul'] = $namaGambar;
        }

        $buku->update($data);

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil diperbarui');
    }

    /**
     * Menghapus buku dari database.
     */
    public function destroy(Buku $buku)
    {
        // Periksa apakah buku sedang dipinjam
        if ($buku->jumlah_salinan != $buku->jumlah_tersedia) {
            return redirect()->back()
                ->with('error', 'Buku tidak dapat dihapus karena sedang dipinjam');
        }

        // Hapus gambar sampul jika ada
        if ($buku->gambar_sampul) {
            Storage::disk('public')->delete('sampul/' . $buku->gambar_sampul);
        }

        $buku->delete();

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil dihapus');
    }

    /**
     * Mencari buku berdasarkan kata kunci.
     */
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        
        $buku = Buku::where('judul', 'like', "%{$keyword}%")
            ->orWhere('penulis', 'like', "%{$keyword}%")
            ->orWhere('isbn', 'like', "%{$keyword}%")
            ->with('kategori')
            ->paginate(10);
            
        return view('admin.buku.search', compact('buku', 'keyword'));
    }
} 