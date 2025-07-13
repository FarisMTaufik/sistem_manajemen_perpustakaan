<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnggotaController extends Controller
{
    /**
     * Menampilkan profil anggota yang sedang login.
     */
    public function profil()
    {
        $anggota = Anggota::where('user_id', Auth::id())->firstOrFail();
        $anggota->load(['peminjaman' => function ($query) {
            $query->latest();
        }]);
        
        return view('anggota.profil', compact('anggota'));
    }
    
    /**
     * Memperbarui profil anggota yang sedang login.
     */
    public function updateProfil(Request $request)
    {
        $anggota = Anggota::where('user_id', Auth::id())->firstOrFail();
        
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update user
        $anggota->user->update([
            'name' => $request->nama_lengkap,
        ]);

        // Update anggota
        $anggota->update([
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
        ]);

        return redirect()->route('anggota.profil')
            ->with('success', 'Profil berhasil diperbarui');
    }
    
    /**
     * Memperbarui password anggota yang sedang login.
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Verifikasi password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai'])
                ->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('anggota.profil')
            ->with('success', 'Password berhasil diperbarui');
    }
} 