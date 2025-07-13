<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Admin\DendaController as AdminDendaController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use App\Http\Controllers\Staff\PeminjamanController as StaffPeminjamanController;
use App\Http\Controllers\Staff\DendaController as StaffDendaController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\Anggota\AnggotaController as AnggotaProfilController;
use App\Http\Controllers\Anggota\PeminjamanController as AnggotaPeminjamanController;
use App\Http\Controllers\Anggota\KatalogController as AnggotaKatalogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute publik
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rute autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Kategori routes
    Route::resource('kategori', App\Http\Controllers\Admin\KategoriController::class);
    
    // Buku routes
    Route::resource('buku', App\Http\Controllers\Admin\BukuController::class);
    Route::get('buku/search', [App\Http\Controllers\Admin\BukuController::class, 'search'])->name('buku.search');
    
    // Anggota routes
    Route::resource('anggota', App\Http\Controllers\Admin\AnggotaController::class, ['parameters' => [
        'anggota' => 'anggota'
    ]]);
    Route::put('anggota/{id}/status', [App\Http\Controllers\Admin\AnggotaController::class, 'updateStatus'])->name('anggota.status')->where('id', '[0-9]+');
    Route::put('anggota/{id}/perpanjang', [App\Http\Controllers\Admin\AnggotaController::class, 'perpanjang'])->name('anggota.perpanjang')->where('id', '[0-9]+');
    Route::put('anggota/{anggota}/reset-password', [App\Http\Controllers\Admin\AnggotaController::class, 'resetPassword'])->name('anggota.reset-password');
    
    // Peminjaman routes
    Route::resource('peminjaman', App\Http\Controllers\Admin\PeminjamanController::class);
    Route::post('peminjaman/{peminjaman}/pengembalian', [App\Http\Controllers\Admin\PeminjamanController::class, 'pengembalian'])->name('peminjaman.pengembalian');
    Route::post('peminjaman/{peminjaman}/perpanjangan', [App\Http\Controllers\Admin\PeminjamanController::class, 'perpanjang'])->name('peminjaman.perpanjangan');
    
    // Denda routes
    Route::get('denda/belum-dibayar', [App\Http\Controllers\Admin\DendaController::class, 'belumDibayar'])->name('denda.belum-dibayar');
    Route::get('denda/sudah-dibayar', [App\Http\Controllers\Admin\DendaController::class, 'sudahDibayar'])->name('denda.sudah-dibayar');
    Route::get('denda/laporan', [App\Http\Controllers\Admin\DendaController::class, 'laporan'])->name('denda.laporan');
    Route::get('denda/riwayat-pembayaran', [App\Http\Controllers\Admin\DendaController::class, 'riwayatPembayaran'])->name('denda.riwayat-pembayaran');
    Route::post('denda/hitung-otomatis', [App\Http\Controllers\Admin\DendaController::class, 'hitungDendaOtomatis'])->name('denda.hitung-otomatis');
    Route::resource('denda', App\Http\Controllers\Admin\DendaController::class)->except(['create', 'store']);
    Route::post('denda/{denda}/pembayaran', [App\Http\Controllers\Admin\DendaController::class, 'pembayaran'])->name('denda.pembayaran');
    Route::post('denda/{denda}/bayar', [App\Http\Controllers\Admin\DendaController::class, 'bayar'])->name('denda.bayar');

    // Inventaris routes
    Route::get('inventaris', [App\Http\Controllers\Admin\InventarisController::class, 'index'])->name('inventaris.index');
    Route::get('inventaris/buku-list', [App\Http\Controllers\Admin\InventarisController::class, 'bukuList'])->name('inventaris.buku-list');
    Route::get('inventaris/create/{buku}', [App\Http\Controllers\Admin\InventarisController::class, 'create'])->name('inventaris.create');
    Route::post('inventaris', [App\Http\Controllers\Admin\InventarisController::class, 'store'])->name('inventaris.store');
    Route::get('inventaris/{inventari}', [App\Http\Controllers\Admin\InventarisController::class, 'show'])->name('inventaris.show');
    Route::get('inventaris/{inventari}/edit', [App\Http\Controllers\Admin\InventarisController::class, 'edit'])->name('inventaris.edit');
    Route::put('inventaris/{inventari}', [App\Http\Controllers\Admin\InventarisController::class, 'update'])->name('inventaris.update');
    Route::delete('inventaris/{inventari}', [App\Http\Controllers\Admin\InventarisController::class, 'destroy'])->name('inventaris.destroy');
    Route::get('inventaris-laporan', [App\Http\Controllers\Admin\InventarisController::class, 'laporan'])->name('inventaris.laporan');
    Route::get('inventaris-perlu-perbaikan', [App\Http\Controllers\Admin\InventarisController::class, 'perluPerbaikan'])->name('inventaris.perlu-perbaikan');
    Route::get('inventaris-kelola-perbaikan/{buku}', [App\Http\Controllers\Admin\InventarisController::class, 'kelolaPerbaikan'])->name('inventaris.kelola-perbaikan');
    Route::post('inventaris-proses-perbaikan/{buku}', [App\Http\Controllers\Admin\InventarisController::class, 'prosesPerbaikan'])->name('inventaris.proses-perbaikan');
    Route::post('inventaris-selesaikan-perbaikan/{buku}', [App\Http\Controllers\Admin\InventarisController::class, 'selesaikanPerbaikan'])->name('inventaris.selesaikan-perbaikan');
    
    // Security routes
    Route::get('security/activity-logs', [App\Http\Controllers\Admin\SecurityController::class, 'activityLogs'])->name('security.activity-logs');
    Route::get('security/backups', [App\Http\Controllers\Admin\SecurityController::class, 'backups'])->name('security.backups');
    Route::post('security/backups/create', [App\Http\Controllers\Admin\SecurityController::class, 'createBackup'])->name('security.backups.create');
    Route::get('security/backups/{filename}/download', [App\Http\Controllers\Admin\SecurityController::class, 'downloadBackup'])->name('security.backups.download');
    Route::delete('security/backups/{filename}', [App\Http\Controllers\Admin\SecurityController::class, 'deleteBackup'])->name('security.backups.delete');
    Route::get('security/backups/{filename}/restore', [App\Http\Controllers\Admin\SecurityController::class, 'showRestore'])->name('security.backups.show-restore');
    Route::post('security/backups/{filename}/restore', [App\Http\Controllers\Admin\SecurityController::class, 'restoreBackup'])->name('security.backups.restore');
});

// Rute untuk Staff
Route::prefix('staff')->name('staff.')->middleware(['auth', 'staff'])->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    
    // Rute Kategori untuk Staff
    Route::resource('kategori', App\Http\Controllers\Admin\KategoriController::class)->except(['destroy']);
    
    // Rute Buku untuk Staff
    // Route statis harus didefinisikan sebelum route dinamis
    Route::get('/buku/search', [App\Http\Controllers\Staff\BukuController::class, 'search'])->name('buku.search');
    Route::resource('buku', App\Http\Controllers\Staff\BukuController::class, ['parameters' => [
        'buku' => 'buku'
    ]])->except(['destroy']);
    
    // Rute Anggota untuk Staff
    Route::resource('anggota', App\Http\Controllers\Staff\AnggotaController::class, ['parameters' => [
        'anggota' => 'anggota'
    ]])->except(['destroy']);
    Route::put('/anggota/{anggota}/status', [App\Http\Controllers\Staff\AnggotaController::class, 'updateStatus'])->name('anggota.status');
    Route::put('/anggota/{anggota}/perpanjang', [App\Http\Controllers\Staff\AnggotaController::class, 'perpanjang'])->name('anggota.perpanjang');
    Route::put('/anggota/{anggota}/reset-password', [App\Http\Controllers\Staff\AnggotaController::class, 'resetPassword'])->name('anggota.reset-password');
    
    // Rute Peminjaman untuk Staff
    Route::resource('peminjaman', StaffPeminjamanController::class)->except(['destroy']);
    Route::post('/peminjaman/{peminjaman}/pengembalian', [StaffPeminjamanController::class, 'pengembalian'])->name('peminjaman.pengembalian');
    Route::post('/peminjaman/{peminjaman}/perpanjang', [StaffPeminjamanController::class, 'perpanjang'])->name('peminjaman.perpanjang');
    
    // Rute Denda untuk Staff
    Route::get('/denda', [StaffDendaController::class, 'index'])->name('denda.index');
    Route::get('/denda/belum-dibayar', [StaffDendaController::class, 'belumDibayar'])->name('denda.belum-dibayar');
    Route::get('/denda/sudah-dibayar', [StaffDendaController::class, 'sudahDibayar'])->name('denda.sudah-dibayar');
    Route::get('/denda/laporan', [StaffDendaController::class, 'laporan'])->name('denda.laporan');
    Route::get('/denda/riwayat-pembayaran', [StaffDendaController::class, 'riwayatPembayaran'])->name('denda.riwayat-pembayaran');
    Route::post('/denda/hitung-otomatis', [StaffDendaController::class, 'hitungDendaOtomatis'])->name('denda.hitung-otomatis');
    Route::get('/denda/{denda}', [StaffDendaController::class, 'show'])->name('denda.show');
    Route::post('/denda/{denda}/bayar', [StaffDendaController::class, 'bayar'])->name('denda.bayar');
});

// Rute untuk Anggota
Route::prefix('anggota')->name('anggota.')->middleware(['anggota'])->group(function () {
    Route::get('/dashboard', [AnggotaDashboardController::class, 'index'])->name('dashboard');
    
    // Rute katalog buku untuk anggota
    Route::get('/katalog', [AnggotaKatalogController::class, 'index'])->name('katalog.index');
    Route::get('/katalog/search', [AnggotaKatalogController::class, 'search'])->name('katalog.search');
    Route::get('/katalog/{buku}', [AnggotaKatalogController::class, 'show'])->name('katalog.show');
    Route::post('/katalog/{buku}/pinjam', [AnggotaKatalogController::class, 'pinjam'])->name('katalog.pinjam');
    
    // Rute profil anggota
    Route::get('/profil', [AnggotaProfilController::class, 'profil'])->name('profil');
    Route::put('/profil', [AnggotaProfilController::class, 'updateProfil'])->name('profil.update');
    Route::put('/profil/password', [AnggotaProfilController::class, 'updatePassword'])->name('profil.password');
    
    // Rute peminjaman untuk anggota
    Route::get('/peminjaman', [AnggotaPeminjamanController::class, 'index'])->name('peminjaman.index');
    // Rute dengan parameter statis harus didefinisikan sebelum rute dengan parameter dinamis
    Route::get('/peminjaman/riwayat', [AnggotaPeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');
    Route::get('/peminjaman/aktif', [AnggotaPeminjamanController::class, 'aktif'])->name('peminjaman.aktif');
    Route::get('/peminjaman/terlambat', [AnggotaPeminjamanController::class, 'terlambat'])->name('peminjaman.terlambat');
    Route::get('/peminjaman/denda', [AnggotaPeminjamanController::class, 'denda'])->name('peminjaman.denda');
    // Rute dengan parameter dinamis harus didefinisikan setelah rute dengan parameter statis
    Route::get('/peminjaman/{peminjaman}', [AnggotaPeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('/peminjaman/{peminjaman}/perpanjang', [AnggotaPeminjamanController::class, 'perpanjang'])->name('peminjaman.perpanjang');
});
