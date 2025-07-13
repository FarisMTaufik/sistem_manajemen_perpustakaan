<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore {file : Path file backup yang akan direstore}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database dari file backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $backupFile = $this->argument('file');
        $backupPath = storage_path('app/backups');
        $fullPath = str_starts_with($backupFile, '/') ? $backupFile : $backupPath . '/' . $backupFile;
        
        // Periksa apakah file ada
        if (!File::exists($fullPath)) {
            $this->error("File backup tidak ditemukan: {$fullPath}");
            return Command::FAILURE;
        }
        
        // Konfirmasi dari pengguna
        if (!$this->confirm('Restore database akan menimpa data yang ada. Lanjutkan?', false)) {
            $this->info('Restore dibatalkan.');
            return Command::SUCCESS;
        }
        
        try {
            // Jika file terenkripsi, dekripsi terlebih dahulu
            if (str_ends_with($fullPath, '.enc')) {
                $this->info('Mendekripsi file backup...');
                $fullPath = $this->decryptBackup($fullPath);
            }
            
            // Periksa apakah file SQL valid
            $fileContent = File::get($fullPath);
            if (empty($fileContent) || !str_contains($fileContent, 'CREATE TABLE') && !str_contains($fileContent, 'INSERT INTO')) {
                throw new \Exception('File backup tidak valid atau kosong.');
            }
            
            // Mendapatkan konfigurasi database
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUsername = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');
            
            // Membuat command untuk mysql
            $command = [
                'mysql',
                '--host=' . $dbHost,
                '--port=' . $dbPort,
                '--user=' . $dbUsername,
            ];
            
            // Tambahkan password jika ada
            if ($dbPassword) {
                $command[] = '--password=' . $dbPassword;
            }
            
            // Tambahkan database
            $command[] = $dbName;
            
            $this->info('Melakukan restore database...');
            
            // Jalankan command mysql < backup_file
            $process = new Process(array_merge($command, ['<', $fullPath]));
            $process->setTimeout(300); // 5 menit timeout
            $process->setInput(File::get($fullPath));
            $process->run();
            
            // Cek jika proses gagal
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            
            $this->info('Database berhasil direstore dari: ' . $fullPath);
            
            // Log aktivitas restore
            if (class_exists('App\Models\ActivityLog')) {
                \App\Models\ActivityLog::log(
                    'restore',
                    'database',
                    'Database berhasil direstore dari: ' . $fullPath,
                    ['file' => $fullPath]
                );
            }
            
            // Hapus file dekripsi sementara jika ada
            if (str_ends_with($this->argument('file'), '.enc') && File::exists($fullPath)) {
                File::delete($fullPath);
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Restore database gagal: ' . $e->getMessage());
            
            // Log error
            if (class_exists('App\Models\ActivityLog')) {
                \App\Models\ActivityLog::log(
                    'error',
                    'database',
                    'Restore database gagal: ' . $e->getMessage(),
                    ['error' => $e->getMessage()]
                );
            }
            
            return Command::FAILURE;
        }
    }
    
    /**
     * Dekripsi file backup.
     */
    protected function decryptBackup(string $encryptedFilePath): string
    {
        $decryptedFilePath = str_replace('.enc', '', $encryptedFilePath);
        
        // Baca file terenkripsi
        $encryptedContent = File::get($encryptedFilePath);
        
        // Dekripsi konten
        try {
            $decryptedContent = decrypt($encryptedContent);
            
            // Simpan konten terdekripsi
            File::put($decryptedFilePath, $decryptedContent);
            
            return $decryptedFilePath;
        } catch (\Exception $e) {
            throw new \Exception('Gagal mendekripsi file backup: ' . $e->getMessage());
        }
    }
}
