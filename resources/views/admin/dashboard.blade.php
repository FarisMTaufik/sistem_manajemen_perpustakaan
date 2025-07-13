@extends('layouts.app')

@section('styles')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<style>
    .dashboard-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .quick-action {
        transition: all 0.3s ease;
    }
    .quick-action:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .notification-badge {
        position: absolute;
        top: -10px;
        right: -10px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Dashboard Admin</h2>
                    <p class="text-muted">Selamat datang di Panel Admin Perpustakaan</p>
                </div>
                <div>
                    <span class="badge bg-primary">{{ date('d F Y') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Anggota</h6>
                            <h2 class="mb-0">{{ $totalAnggota }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="{{ route('admin.anggota.index') }}" class="card-footer d-flex justify-content-between text-white text-decoration-none">
                    <span>Lihat Detail</span>
                    <span><i class="fas fa-arrow-circle-right"></i></span>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Buku</h6>
                            <h2 class="mb-0">{{ $totalBuku }}</h2>
                        </div>
                        <i class="fas fa-book fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="{{ route('admin.buku.index') }}" class="card-footer d-flex justify-content-between text-white text-decoration-none">
                    <span>Lihat Detail</span>
                    <span><i class="fas fa-arrow-circle-right"></i></span>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Peminjaman Aktif</h6>
                            <h2 class="mb-0">{{ $peminjamanAktif }}</h2>
                        </div>
                        <i class="fas fa-handshake fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="{{ route('admin.peminjaman.index') }}" class="card-footer d-flex justify-content-between text-white text-decoration-none">
                    <span>Lihat Detail</span>
                    <span><i class="fas fa-arrow-circle-right"></i></span>
                </a>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white dashboard-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Peminjaman Terlambat</h6>
                            <h2 class="mb-0">{{ $peminjamanTerlambat }}</h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="{{ route('admin.peminjaman.index') }}?status=terlambat" class="card-footer d-flex justify-content-between text-white text-decoration-none">
                    <span>Lihat Detail</span>
                    <span><i class="fas fa-arrow-circle-right"></i></span>
                </a>
            </div>
        </div>
    </div>

    <!-- Grafik Statistik -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Statistik Peminjaman</h5>
                </div>
                <div class="card-body">
                    <canvas id="peminjamanChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Kategori Buku Terpopuler</h5>
                </div>
                <div class="card-body">
                    <canvas id="kategoriChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Menu Akses Cepat -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Menu Akses Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.anggota.create') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light quick-action">
                                    <i class="fas fa-user-plus fa-2x text-primary mb-2"></i>
                                    <h6>Tambah Anggota</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.buku.create') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light quick-action">
                                    <i class="fas fa-book fa-2x text-success mb-2"></i>
                                    <h6>Tambah Buku</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.peminjaman.create') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light quick-action">
                                    <i class="fas fa-handshake fa-2x text-info mb-2"></i>
                                    <h6>Peminjaman Baru</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.inventaris.buku-list') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light quick-action">
                                    <i class="fas fa-clipboard-check fa-2x text-warning mb-2"></i>
                                    <h6>Inventaris</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.inventaris.perlu-perbaikan') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light quick-action position-relative">
                                    <i class="fas fa-tools fa-2x text-danger mb-2"></i>
                                    <h6>Perlu Perbaikan</h6>
                                    @if(isset($bukuPerluPerbaikan) && $bukuPerluPerbaikan > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                                            {{ $bukuPerluPerbaikan }}
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('admin.denda.belum-dibayar') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light quick-action position-relative">
                                    <i class="fas fa-money-bill fa-2x text-secondary mb-2"></i>
                                    <h6>Denda</h6>
                                    @if(isset($dendaBelumBayar) && $dendaBelumBayar > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                                            {{ $dendaBelumBayar }}
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Peminjaman Terbaru dan Notifikasi -->
    <div class="row mb-4">
        <div class="col-md-7 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Peminjaman Terbaru</h5>
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Anggota</th>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamanTerbaru ?? [] as $peminjaman)
                                <tr>
                                    <td>{{ $peminjaman->anggota->nama }}</td>
                                    <td>{{ $peminjaman->buku->judul }}</td>
                                    <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>{{ $peminjaman->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                                    <td>
                                        @if($peminjaman->status == 'dipinjam')
                                            <span class="badge bg-info">Dipinjam</span>
                                        @elseif($peminjaman->status == 'terlambat')
                                            <span class="badge bg-danger">Terlambat</span>
                                        @elseif($peminjaman->status == 'dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">Tidak ada data peminjaman terbaru</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Peminjaman Jatuh Tempo</h5>
                    <span class="badge bg-warning">{{ count($peminjamanJatuhTempo ?? []) }} buku</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($peminjamanJatuhTempo ?? [] as $peminjaman)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $peminjaman->buku->judul }}</h6>
                                    <small class="text-danger">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $peminjaman->tanggal_jatuh_tempo->format('d/m/Y') }}
                                    </small>
                                </div>
                                <p class="mb-1">Peminjam: {{ $peminjaman->anggota->nama }}</p>
                                <small class="text-muted">
                                    Jatuh tempo dalam {{ $peminjaman->tanggal_jatuh_tempo->diffInDays(now()) }} hari
                                </small>
                                <div class="mt-2">
                                    <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}" class="btn btn-sm btn-outline-info">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center py-3">
                                <p class="mb-0">Tidak ada peminjaman yang akan jatuh tempo dalam waktu dekat</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Manajemen Perpustakaan -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Manajemen Perpustakaan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Manajemen Anggota</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="{{ route('admin.anggota.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            Daftar Anggota
                                            <span class="badge bg-primary rounded-pill">{{ $totalAnggota }}</span>
                                        </a>
                                        <a href="{{ route('admin.anggota.create') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-plus-circle me-2"></i> Tambah Anggota Baru
                                        </a>
                                        <a href="{{ route('admin.anggota.index') }}?status=aktif" class="list-group-item list-group-item-action">
                                            <i class="fas fa-check-circle me-2"></i> Anggota Aktif
                                        </a>
                                        <a href="{{ route('admin.anggota.index') }}?status=nonaktif" class="list-group-item list-group-item-action">
                                            <i class="fas fa-times-circle me-2"></i> Anggota Nonaktif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Manajemen Katalog</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="{{ route('admin.kategori.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            Kategori Buku
                                            <span class="badge bg-primary rounded-pill">{{ $totalKategori }}</span>
                                        </a>
                                        <a href="{{ route('admin.buku.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            Daftar Buku
                                            <span class="badge bg-primary rounded-pill">{{ $totalBuku }}</span>
                                        </a>
                                        <a href="{{ route('admin.buku.create') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-plus-circle me-2"></i> Tambah Buku Baru
                                        </a>
                                        <a href="{{ route('admin.kategori.create') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-plus-circle me-2"></i> Tambah Kategori Baru
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Manajemen Sirkulasi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="{{ route('admin.peminjaman.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            Peminjaman Aktif
                                            <span class="badge bg-primary rounded-pill">{{ $peminjamanAktif }}</span>
                                        </a>
                                        <a href="{{ route('admin.peminjaman.index') }}?status=terlambat" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            Peminjaman Terlambat
                                            <span class="badge bg-danger rounded-pill">{{ $peminjamanTerlambat }}</span>
                                        </a>
                                        <a href="{{ route('admin.peminjaman.create') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-plus-circle me-2"></i> Peminjaman Baru
                                        </a>
                                        <a href="{{ route('admin.denda.index') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-money-bill me-2"></i> Manajemen Denda
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tambahkan Manajemen Inventaris -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Manajemen Inventaris</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline-warning w-100 mb-2">
                                                <i class="fas fa-clipboard-list me-1"></i> Data Inventaris
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('admin.inventaris.buku-list') }}" class="btn btn-outline-warning w-100 mb-2">
                                                <i class="fas fa-book-open me-1"></i> Tambah Inventaris
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('admin.inventaris.perlu-perbaikan') }}" class="btn btn-outline-warning w-100 mb-2">
                                                <i class="fas fa-tools me-1"></i> Buku Perlu Perbaikan
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('admin.inventaris.laporan') }}" class="btn btn-outline-warning w-100 mb-2">
                                                <i class="fas fa-file-alt me-1"></i> Laporan Inventaris
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tambahkan Manajemen Keamanan dan Backup -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="mb-0">Keamanan dan Backup</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <a href="{{ route('admin.security.activity-logs') }}" class="btn btn-outline-dark w-100 mb-2">
                                                <i class="fas fa-history me-1"></i> Log Aktivitas
                                            </a>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <a href="{{ route('admin.security.backups') }}" class="btn btn-outline-dark w-100 mb-2">
                                                <i class="fas fa-database me-1"></i> Backup Database
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Konfigurasi global Chart.js untuk menonaktifkan semua animasi
    Chart.defaults.animation = false;
    Chart.defaults.animations = false;
    Chart.defaults.transitions = false;
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;
    
    // Data untuk grafik peminjaman
    const peminjamanCtx = document.getElementById('peminjamanChart').getContext('2d');
    const peminjamanChart = new Chart(peminjamanCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($grafikPeminjaman['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!},
            datasets: [{
                label: 'Peminjaman',
                data: {!! json_encode($grafikPeminjaman['data'] ?? [12, 19, 3, 5, 2, 3]) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            resizeDelay: 0,
            animation: false,
            animations: false,
            transitions: false,
            hover: {
                animationDuration: 0
            },
            responsiveAnimationDuration: 0,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Statistik Peminjaman Bulanan'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Data untuk grafik kategori
    const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
    const kategoriChart = new Chart(kategoriCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($grafikKategori['labels'] ?? ['Fiksi', 'Non-Fiksi', 'Referensi', 'Lainnya']) !!},
            datasets: [{
                label: 'Jumlah Buku',
                data: {!! json_encode($grafikKategori['data'] ?? [12, 19, 3, 5]) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            resizeDelay: 0,
            animation: false,
            animations: false,
            transitions: false,
            hover: {
                animationDuration: 0
            },
            responsiveAnimationDuration: 0,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Distribusi Kategori Buku'
                }
            }
        }
    });
</script>
@endsection 