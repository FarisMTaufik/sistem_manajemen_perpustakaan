<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StaffMiddleware;
use App\Http\Middleware\AnggotaMiddleware;
use App\Http\Middleware\LogActivity;
use Illuminate\Support\Facades\Log;

// Log aplikasi dimulai
Log::info('Aplikasi perpustakaan start - ' . date('Y-m-d H:i:s'));
Log::debug('Sistem Manajemen Perpustakaan v1.0 - Diakses pada ' . date('Y-m-d H:i:s'));

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'staff' => StaffMiddleware::class,
            'anggota' => AnggotaMiddleware::class,
            'log.activity' => LogActivity::class,
        ]);

        // Tambahkan middleware global untuk log aktivitas
        $middleware->web(append: [
            LogActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();