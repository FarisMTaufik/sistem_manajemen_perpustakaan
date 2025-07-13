<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AnggotaController extends Controller
{
    /**
     * Menampilkan daftar anggota.
     */
    public function index(Request $request)
    {
        $query = Anggota::with('user');

        // Filter pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_anggota', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter jenis anggota
        if ($request->has('jenis_anggota') && $request->jenis_anggota != '') {
            $query->where('jenis_anggota', $request->jenis_anggota);
        }

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $anggota = $query->latest()->paginate(10);
        
        return view('staff.anggota.index', compact('anggota'));
    }

    /**
     * Menampilkan form untuk membuat anggota baru.
     */
    public function create()
    {
        return view('staff.anggota.create');
    }

    /**
     * Menyimpan anggota baru ke database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_anggota' => 'required|in:mahasiswa,dosen,umum',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate nomor anggota
        $prefix = strtoupper(substr($request->jenis_anggota, 0, 1));
        $tahun = date('Y');
        $count = Anggota::count() + 1;
        $nomor_anggota = $prefix . $tahun . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Buat user baru
        $user = User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'anggota',
        ]);

        // Hitung tanggal kedaluwarsa (1 tahun dari tanggal bergabung)
        $tanggal_bergabung = Carbon::now();
        $tanggal_kedaluwarsa = Carbon::now()->addYear();

        // Buat anggota baru
        Anggota::create([
            'user_id' => $user->id,
            'nomor_anggota' => $nomor_anggota,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
            'email' => $request->email,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_anggota' => $request->jenis_anggota,
            'status' => 'aktif',
            'tanggal_bergabung' => $tanggal_bergabung,
            'tanggal_kedaluwarsa' => $tanggal_kedaluwarsa,
        ]);

        return redirect()->route('staff.anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan');
    }

    /**
     * Menampilkan detail anggota.
     */
    public function show(Anggota $anggota)
    {
        // Tambahkan debugging
        \Log::info('Staff AnggotaController@show dipanggil dengan ID: ' . $anggota->id);
        
        $anggota->load(['peminjaman' => function ($query) {
            $query->latest();
        }]);
        
        return view('staff.anggota.show', compact('anggota'));
    }

    /**
     * Menampilkan form untuk mengedit anggota.
     */
    public function edit(Anggota $anggota)
    {
        // Tambahkan debugging
        \Log::info('Staff AnggotaController@edit dipanggil dengan ID: ' . $anggota->id);
        
        return view('staff.anggota.edit', compact('anggota'));
    }

    /**
     * Memperbarui anggota di database.
     */
    public function update(Request $request, Anggota $anggota)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:users,email,' . $anggota->user_id,
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_anggota' => 'required|in:mahasiswa,dosen,umum',
            'status' => 'required|in:aktif,nonaktif,diblokir',
            'tanggal_kedaluwarsa' => 'nullable|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update user
        $anggota->user->update([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
        ]);

        // Update anggota
        $anggota->update([
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
            'email' => $request->email,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_anggota' => $request->jenis_anggota,
            'status' => $request->status,
            'tanggal_kedaluwarsa' => $request->tanggal_kedaluwarsa,
        ]);

        return redirect()->route('staff.anggota.index')
            ->with('success', 'Anggota berhasil diperbarui');
    }

    /**
     * Memperbarui status anggota.
     */
    public function updateStatus(Request $request, Anggota $anggota)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:aktif,nonaktif,diblokir',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $anggota->update([
            'status' => $request->status
        ]);

        return redirect()->back()
            ->with('success', 'Status anggota berhasil diperbarui');
    }

    /**
     * Memperpanjang masa keanggotaan.
     */
    public function perpanjang(Anggota $anggota)
    {
        // Perpanjang masa keanggotaan 1 tahun dari tanggal kedaluwarsa atau dari sekarang
        $tanggal_kedaluwarsa = $anggota->tanggal_kedaluwarsa && $anggota->tanggal_kedaluwarsa > Carbon::now() 
            ? Carbon::parse($anggota->tanggal_kedaluwarsa)->addYear() 
            : Carbon::now()->addYear();
        
        $anggota->update([
            'status' => 'aktif',
            'tanggal_kedaluwarsa' => $tanggal_kedaluwarsa,
        ]);

        return redirect()->back()
            ->with('success', 'Masa keanggotaan berhasil diperpanjang');
    }
    
    /**
     * Reset password anggota.
     */
    public function resetPassword(Request $request, Anggota $anggota)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $anggota->user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()
            ->with('success', 'Password anggota berhasil direset');
    }
} 