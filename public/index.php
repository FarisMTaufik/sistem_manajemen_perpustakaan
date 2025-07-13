<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Console logging untuk debugging
$startTime = microtime(true);
$requestTime = date('Y-m-d H:i:s');
$requestId = uniqid();

// Log awal request
file_put_contents(
    __DIR__ . '/../storage/logs/requests.log',
    "[{$requestTime}][{$requestId}] 🚀 Request dijalankan: {$_SERVER['REQUEST_URI']} | IP: {$_SERVER['REMOTE_ADDR']} \n",
    FILE_APPEND
);

define('LARAVEL_START', microtime(true));

// Console log untuk maintenance mode
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    file_put_contents(
        __DIR__ . '/../storage/logs/requests.log',
        "[{$requestTime}][{$requestId}] ⚠️ Aplikasi dalam maintenance mode\n",
        FILE_APPEND
    );
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

// Log sebelum memproses request
file_put_contents(
    __DIR__ . '/../storage/logs/requests.log',
    "[{$requestTime}][{$requestId}] 🔄 Memproses request: {$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']}\n",
    FILE_APPEND
);

$response = $app->handleRequest(Request::capture());

// Log waktu eksekusi di akhir request
$endTime = microtime(true);
$executionTime = number_format(($endTime - $startTime) * 1000, 2);
file_put_contents(
    __DIR__ . '/../storage/logs/requests.log',
    "[{$requestTime}][{$requestId}] ✅ Request selesai: {$response->getStatusCode()} | Waktu: {$executionTime}ms\n",
    FILE_APPEND
);