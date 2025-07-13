<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Log seeder dimulai
        Log::info('==================================================');
        Log::info('Database Seeder : ' . now()->format('Y-m-d H:i:s'));
        Log::info('==================================================');

        $this->command->info('Memulai seeding database perpustakaan...');

        // Membuat akun admin
        $this->command->info('Membuat akun administrator...');
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        Log::info('Admin user created: ' . $admin->email);
        $this->command->line('✅ Akun admin berhasil dibuat: ' . $admin->email);

        // Membuat akun staff
        $this->command->info('Membuat akun staff...');
        $staff = User::create([
            'name' => 'Staff Perpustakaan',
            'email' => 'staff@perpustakaan.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
        ]);
        Log::info('Staff user created: ' . $staff->email);
        $this->command->line('✅ Akun staff berhasil dibuat: ' . $staff->email);

        // Membuat akun anggota (opsional)
        $this->command->info('Membuat akun anggota...');
        $anggota = User::create([
            'name' => 'Anggota',
            'email' => 'anggota@perpustakaan.com',
            'password' => Hash::make('anggota123'),
            'role' => 'anggota',
        ]);
        Log::info('Anggota user created: ' . $anggota->email);
        $this->command->line('✅ Akun anggota berhasil dibuat: ' . $anggota->email);

        // Membuat data anggota untuk user anggota
        $this->command->info('Membuat data profil anggota...');
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
        $this->command->line('✅ Data profil anggota berhasil dibuat');

        // Memanggil seeder kategori
        $this->command->info('Menjalankan seeder kategori...');
        $this->call(KategoriSeeder::class);

        // Log seeder selesai
        Log::info('Database Seeder selesai pada: ' . now()->format('Y-m-d H:i:s'));
        Log::info('==================================================');

        $this->command->info('Database seeding selesai! ✨');
        $this->command->table(
            ['User Type', 'Email', 'Password'],
            [
                ['Admin', 'admin@perpustakaan.com', 'admin123'],
                ['Staff', 'staff@perpustakaan.com', 'staff123'],
                ['Anggota', 'anggota@perpustakaan.com', 'anggota123'],
            ]
        );
    }
}