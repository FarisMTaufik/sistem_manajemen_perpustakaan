<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--filename= : Nama file backup (opsional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database ke file SQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Membuat direktori backup jika belum ada
        $backupPath = storage_path('app/backups');
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }
        
        // Mendapatkan nama file
        $filename = $this->option('filename') ?? 'backup_' . Carbon::now()->format('Y-m-d_His') . '.sql';
        
        // Pastikan nama file berakhiran .sql
        if (!str_ends_with($filename, '.sql')) {
            $filename .= '.sql';
        }
        
        // Path lengkap file backup
        $backupFilePath = $backupPath . '/' . $filename;
        
        // Mendapatkan konfigurasi database
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');
        $dbName = config('database.connections.mysql.database');
        $dbUsername = config('database.connections.mysql.username');
        $dbPassword = config('database.connections.mysql.password');
        
        // Membuat command untuk mysqldump
        $command = [
            'mysqldump',
            '--host=' . $dbHost,
            '--port=' . $dbPort,
            '--user=' . $dbUsername,
        ];
        
        // Tambahkan password jika ada
        if ($dbPassword) {
            $command[] = '--password=' . $dbPassword;
        }
        
        // Tambahkan opsi lainnya
        $command = array_merge($command, [
            '--single-transaction',
            '--skip-lock-tables',
            '--routines',
            '--triggers',
            $dbName,
        ]);
        
        try {
            // Jalankan command mysqldump
            $process = new Process($command);
            $process->setTimeout(300); // 5 menit timeout
            $process->run();
            
            // Cek jika proses gagal
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            
            // Pastikan output tidak kosong
            $output = $process->getOutput();
            if (empty($output)) {
                throw new \Exception('Output dari mysqldump kosong. Pastikan database tidak kosong dan konfigurasi database benar.');
            }
            
            // Simpan output ke file
            File::put($backupFilePath, $output);
            
            // Enkripsi file backup (opsional)
            $encryptedFilePath = $this->encryptBackup($backupFilePath);
            
            $this->info('Database berhasil dibackup ke: ' . $encryptedFilePath);
            
            // Log aktivitas backup
            if (class_exists('App\Models\ActivityLog')) {
                \App\Models\ActivityLog::log(
                    'backup',
                    'database',
                    'Database berhasil dibackup ke: ' . $encryptedFilePath,
                    ['file' => $encryptedFilePath]
                );
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Backup database gagal: ' . $e->getMessage());
            
            // Log error
            if (class_exists('App\Models\ActivityLog')) {
                \App\Models\ActivityLog::log(
                    'error',
                    'database',
                    'Backup database gagal: ' . $e->getMessage(),
                    ['error' => $e->getMessage()]
                );
            }
            
            return Command::FAILURE;
        }
    }
    
    /**
     * Enkripsi file backup dengan password aplikasi.
     */
    protected function encryptBackup(string $filePath): string
    {
        $encryptedFilePath = $filePath . '.enc';
        
        // Baca file backup
        $content = File::get($filePath);
        
        // Enkripsi konten dengan key aplikasi
        $encryptedContent = encrypt($content);
        
        // Simpan konten terenkripsi
        File::put($encryptedFilePath, $encryptedContent);
        
        // Hapus file asli
        File::delete($filePath);
        
        return $encryptedFilePath;
    }
}
