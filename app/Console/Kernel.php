<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Menjalankan command hitung denda otomatis setiap hari pukul 00:01
        $schedule->command('denda:hitung')->dailyAt('00:01');
        
        // Menjalankan command notifikasi jatuh tempo setiap hari pukul 09:00
        $schedule->command('notifikasi:jatuh-tempo')->dailyAt('09:00');
        
        // Menjalankan backup database otomatis setiap hari pukul 01:00
        $schedule->command('db:backup')->dailyAt('01:00')
            ->appendOutputTo(storage_path('logs/backup.log'));
        
        // Menjalankan backup database otomatis setiap minggu pada hari Minggu pukul 02:00
        $schedule->command('db:backup --filename=backup_weekly_' . date('Y-m-d') . '.sql')
            ->weekly()
            ->sundays()
            ->at('02:00')
            ->appendOutputTo(storage_path('logs/backup.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 