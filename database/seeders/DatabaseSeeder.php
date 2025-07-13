<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Membuat akun staff
        $staff = User::create([
            'name' => 'Staff Perpustakaan',
            'email' => 'staff@perpustakaan.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);

        // Membuat akun anggota (opsional)
        $anggota = User::create([
            'name' => 'Anggota',
            'email' => 'anggota@perpustakaan.com',
            'password' => Hash::make('anggota123'),
            'role' => 'anggota',
        ]);

        // Membuat data anggota untuk user anggota
        Anggota::create([
            'user_id' => $anggota->id,
            'nomor_anggota' => 'A00001',
            'nama_lengkap' => 'Anggota Perpustakaan',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Contoh No. 123',
            'nomor_telepon' => '08123456789',
            'email' => 'anggota@perpustakaan.com',
            'tanggal_lahir' => '1990-01-01',
            'jenis_anggota' => 'umum',
            'status' => 'aktif',
            'tanggal_bergabung' => now(),
            'tanggal_kedaluwarsa' => now()->addYear(),
        ]);
        
        // Memanggil seeder kategori
        $this->call(KategoriSeeder::class);
    }
}
