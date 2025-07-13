<?php

namespace App\Console\Commands;

use App\Models\Denda;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class HitungDendaOtomatis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'denda:hitung';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghitung denda otomatis untuk semua peminjaman yang terlambat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Ambil semua peminjaman yang terlambat dan belum dikembalikan
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', $now)
            ->whereDoesntHave('denda')
            ->get();
        
        if ($peminjamanTerlambat->isEmpty()) {
            $this->info('Tidak ada peminjaman terlambat yang perlu dihitung dendanya.');
            return 0;
        }
        
        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($peminjamanTerlambat as $peminjaman) {
                // Gunakan metode hitungHariTerlambat dari model Peminjaman
                $hariTerlambat = $peminjaman->hitungHariTerlambat();
                
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
            $this->info("Berhasil menghitung denda untuk {$count} peminjaman terlambat.");
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            return 1;
        }
    }
} 