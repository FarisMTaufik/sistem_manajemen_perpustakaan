<?php

namespace App\Console\Commands;

use App\Mail\PeminjamanJatuhTempoNotification;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class KirimNotifikasiJatuhTempo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifikasi:jatuh-tempo {hari=3 : Jumlah hari sebelum jatuh tempo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirim notifikasi email untuk peminjaman yang akan jatuh tempo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hariSebelumJatuhTempo = (int) $this->argument('hari');
        $tanggalTarget = Carbon::now()->addDays($hariSebelumJatuhTempo);
        
        $this->info("Mencari peminjaman yang akan jatuh tempo pada: " . $tanggalTarget->format('Y-m-d'));
        
        // Ambil semua peminjaman yang akan jatuh tempo dalam X hari
        $peminjaman = Peminjaman::with(['anggota', 'buku'])
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_jatuh_tempo', $tanggalTarget->format('Y-m-d'))
            ->get();
        
        if ($peminjaman->isEmpty()) {
            $this->info('Tidak ada peminjaman yang akan jatuh tempo dalam ' . $hariSebelumJatuhTempo . ' hari.');
            return 0;
        }
        
        $count = 0;
        foreach ($peminjaman as $p) {
            // Pastikan anggota dan email ada
            if ($p->anggota && $p->anggota->email) {
                try {
                    Mail::to($p->anggota->email)->send(new PeminjamanJatuhTempoNotification($p));
                    $this->info("Notifikasi terkirim ke: " . $p->anggota->email . " untuk buku: " . $p->buku->judul);
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Gagal mengirim email ke: " . $p->anggota->email . " - " . $e->getMessage());
                }
            } else {
                $this->warn("Anggota ID: " . ($p->anggota ? $p->anggota->id : 'Unknown') . " tidak memiliki email atau data anggota tidak ditemukan.");
            }
        }
        
        $this->info("Total notifikasi terkirim: " . $count);
        return 0;
    }
} 