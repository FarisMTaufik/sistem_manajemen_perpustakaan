@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center mt-4 mb-5">
            <h1 class="display-4">Perpustakaan Digital</h1>
            <p class="lead">Sistem Manajemen Perpustakaan Modern</p>
        </div>
    </div>
    
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4">Selamat Datang</h2>
                    <p class="card-text">
                        Sistem Manajemen Perpustakaan adalah aplikasi yang memudahkan pengelolaan perpustakaan digital modern. 
                        Dengan fitur yang lengkap, aplikasi ini menyediakan solusi terintegrasi untuk:
                    </p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check-circle text-success me-2"></i> Pencarian dan pengelolaan katalog buku</li>
                        <li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check-circle text-success me-2"></i> Manajemen anggota dan keanggotaan</li>
                        <li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check-circle text-success me-2"></i> Sistem peminjaman dan pengembalian</li>
                        <li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check-circle text-success me-2"></i> Pengelolaan denda dan pembayaran</li>
                        <li class="list-group-item bg-transparent border-0 ps-0"><i class="fas fa-check-circle text-success me-2"></i> Inventarisasi dan laporan</li>
                    </ul>
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="{{ route('login') }}" class="btn btn-primary px-4 me-md-2">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary px-4">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-center align-items-center">
                    <div class="display-1 mb-4">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <h2 class="card-title mb-3 text-center">Akses Digital</h2>
                    <p class="card-text text-center mb-4">
                        Akses katalog buku kapanpun dan dimanapun. Cari, pinjam, dan kembalikan buku dengan mudah melalui sistem digital kami.
                    </p>
                    <div class="text-center">
                        <a href="#fitur" class="btn btn-light">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Features Section -->
    <div class="row mb-5" id="fitur">
        <div class="col-md-12 text-center mb-4">
            <h2>Fitur Unggulan</h2>
            <p class="text-muted">Nikmati berbagai fitur untuk memudahkan pengelolaan perpustakaan</p>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <div class="icon-box mb-3">
                        <i class="fas fa-search fa-3x text-primary"></i>
                    </div>
                    <h4 class="card-title">Pencarian Pintar</h4>
                    <p class="card-text">
                        Temukan buku dengan cepat berdasarkan judul, penulis, ISBN, kategori, atau kata kunci.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <div class="icon-box mb-3">
                        <i class="fas fa-exchange-alt fa-3x text-primary"></i>
                    </div>
                    <h4 class="card-title">Sirkulasi Otomatis</h4>
                    <p class="card-text">
                        Sistem peminjaman dan pengembalian otomatis dengan pelacakan status buku secara real-time.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <div class="icon-box mb-3">
                        <i class="fas fa-chart-bar fa-3x text-primary"></i>
                    </div>
                    <h4 class="card-title">Laporan & Analisis</h4>
                    <p class="card-text">
                        Dapatkan wawasan melalui laporan inventaris, sirkulasi, dan aktivitas anggota.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body p-5 text-center">
                    <h3 class="mb-4">Siap untuk memulai?</h3>
                    <p class="mb-4">Daftar sekarang dan nikmati kemudahan dalam mengelola perpustakaan digital.</p>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
