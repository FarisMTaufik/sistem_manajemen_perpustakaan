@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Dashboard Staff</h2>
            <p class="text-muted">Selamat datang di Panel Staff Perpustakaan</p>
        </div>
    </div>
    
    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Anggota</h6>
                            <h2 class="mb-0">{{ $totalAnggota }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="{{ route('staff.anggota.index') }}" class="card-footer d-flex justify-content-between text-white">
                    <span>Lihat Detail</span>
                    <span><i class="fas fa-arrow-circle-right"></i></span>
                </a>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Buku</h6>
                            <h2 class="mb-0">{{ $totalBuku }}</h2>
                        </div>
                        <i class="fas fa-book fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="{{ route('staff.buku.index') }}" class="card-footer d-flex justify-content-between text-white">
                    <span>Lihat Detail</span>
                    <span><i class="fas fa-arrow-circle-right"></i></span>
                </a>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Peminjaman Hari Ini</h6>
                            <h2 class="mb-0">{{ $peminjamanHariIni }}</h2>
                        </div>
                        <i class="fas fa-handshake fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="{{ route('staff.peminjaman.index') }}" class="card-footer d-flex justify-content-between text-white">
                    <span>Lihat Detail</span>
                    <span><i class="fas fa-arrow-circle-right"></i></span>
                </a>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pengembalian Hari Ini</h6>
                            <h2 class="mb-0">{{ $pengembalianHariIni }}</h2>
                        </div>
                        <i class="fas fa-undo fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="{{ route('staff.peminjaman.index') }}" class="card-footer d-flex justify-content-between text-white">
                    <span>Lihat Detail</span>
                    <span><i class="fas fa-arrow-circle-right"></i></span>
                </a>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Peminjaman Terlambat</h6>
                            <h2 class="mb-0">{{ $peminjamanTerlambat }}</h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                    </div>
                </div>
                <a href="{{ route('staff.denda.belum-dibayar') }}" class="card-footer d-flex justify-content-between text-white">
                    <span>Lihat Detail</span>
                    <span><i class="fas fa-arrow-circle-right"></i></span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Peminjaman Terbaru -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Peminjaman Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Anggota</th>
                                    <th>Buku</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamanTerbaru as $peminjaman)
                                <tr>
                                    <td>{{ $peminjaman->id }}</td>
                                    <td>{{ $peminjaman->anggota->nama_lengkap }}</td>
                                    <td>{{ $peminjaman->buku->judul }}</td>
                                    <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>{{ $peminjaman->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                                    <td>
                                        @if($peminjaman->status == 'dipinjam')
                                            <span class="badge bg-info">Dipinjam</span>
                                        @elseif($peminjaman->status == 'dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @else
                                            <span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('staff.peminjaman.show', $peminjaman->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data peminjaman terbaru</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('staff.peminjaman.index') }}" class="btn btn-primary">Lihat Semua Peminjaman</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Menu Akses Cepat -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Menu Akses Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('staff.peminjaman.create') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light">
                                    <i class="fas fa-exchange-alt fa-3x text-primary mb-3"></i>
                                    <h5>Proses Peminjaman</h5>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('staff.peminjaman.index') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light">
                                    <i class="fas fa-undo fa-3x text-success mb-3"></i>
                                    <h5>Proses Pengembalian</h5>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('staff.buku.search') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light">
                                    <i class="fas fa-search fa-3x text-info mb-3"></i>
                                    <h5>Cari Buku</h5>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('staff.anggota.index') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light">
                                    <i class="fas fa-user-check fa-3x text-warning mb-3"></i>
                                    <h5>Verifikasi Anggota</h5>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('staff.denda.belum-dibayar') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light">
                                    <i class="fas fa-money-bill fa-3x text-danger mb-3"></i>
                                    <h5>Denda Belum Dibayar</h5>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <a href="{{ route('staff.denda.index') }}" class="text-decoration-none">
                                <div class="p-3 border rounded bg-light">
                                    <i class="fas fa-file-invoice-dollar fa-3x text-secondary mb-3"></i>
                                    <h5>Semua Denda</h5>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 