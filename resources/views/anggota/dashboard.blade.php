@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Dashboard Anggota</h2>
            <p class="text-muted">Selamat datang, {{ $anggota->nama_lengkap }}</p>
        </div>
    </div>
    
    <!-- Informasi Anggota -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Anggota</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-light rounded-circle p-3 text-primary" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0">{{ $anggota->nama_lengkap }}</h5>
                            <p class="text-muted mb-0">{{ $anggota->nomor_anggota }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Jenis Anggota</div>
                            <div class="col-md-8">{{ ucfirst($anggota->jenis_anggota) }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Status</div>
                            <div class="col-md-8">
                                @if($anggota->status === 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($anggota->status === 'nonaktif')
                                    <span class="badge bg-warning">Non-aktif</span>
                                @else
                                    <span class="badge bg-danger">Diblokir</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">Email</div>
                            <div class="col-md-8">{{ $anggota->email }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 text-muted">No. Telepon</div>
                            <div class="col-md-8">{{ $anggota->nomor_telepon }}</div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle fa-lg"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <strong>Masa Berlaku Keanggotaan:</strong><br>
                                {{ $anggota->tanggal_kedaluwarsa->format('d F Y') }}
                                @if($masaBerlaku > 0)
                                    <span class="badge bg-success ms-2">{{ $masaBerlaku }} hari lagi</span>
                                @else
                                    <span class="badge bg-danger ms-2">Keanggotaan telah berakhir</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Aktivitas Peminjaman</h5>
                </div>
                <div class="card-body">
                    @if(count($peminjamanAktif) > 0)
                    <div class="alert alert-warning mb-3">
                        <h6 class="alert-heading">Peminjaman Aktif</h6>
                        <p class="mb-0">Anda memiliki {{ count($peminjamanAktif) }} buku yang sedang dipinjam</p>
                    </div>
                    
                    @foreach($peminjamanAktif as $pinjam)
                    <div class="d-flex mb-2 border-bottom pb-2">
                        <div class="flex-shrink-0">
                            <i class="fas fa-book fa-2x text-success me-2"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $pinjam->buku->judul }}</h6>
                            <small class="text-muted">Jatuh tempo: {{ $pinjam->tanggal_jatuh_tempo->format('d/m/Y') }}</small>
                            @if($pinjam->isTerlambat())
                            <div class="mt-1">
                                <span class="badge bg-danger">Terlambat {{ $pinjam->hitungHariTerlambat() }} hari</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-book-reader fa-3x text-muted mb-3"></i>
                        <p>Anda tidak memiliki peminjaman aktif saat ini.</p>
                        <a href="{{ route('anggota.katalog.index') }}" class="btn btn-primary btn-sm">Pinjam Buku</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Denda dan Rekomendasi -->
    <div class="row mb-4">
        @if(count($dendaBelumBayar) > 0)
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Denda yang Belum Dibayar</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Jumlah Denda</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dendaBelumBayar as $denda)
                                <tr>
                                    <td>{{ $denda->peminjaman->buku->judul }}</td>
                                    <td>{{ $denda->peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-dark">
                                    <th colspan="2">Total</th>
                                    <th>Rp {{ number_format($dendaBelumBayar->sum('jumlah_denda'), 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('anggota.peminjaman.denda') }}" class="btn btn-danger">Bayar Denda</a>
                </div>
            </div>
        </div>
        @endif
        
        <div class="col-md-{{ count($dendaBelumBayar) > 0 ? '6' : '12' }} mb-3">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Rekomendasi Buku</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($rekomendasiBuku as $buku)
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card">
                                <div class="card-body p-2 text-center">
                                    <div class="bg-light p-2 mb-2">
                                        @if($buku->gambar_sampul)
                                            <img src="{{ asset('storage/sampul/' . $buku->gambar_sampul) }}" class="img-fluid" alt="{{ $buku->judul }}" style="height: 80px; object-fit: cover;">
                                        @else
                                            <i class="fas fa-book fa-3x text-info"></i>
                                        @endif
                                    </div>
                                    <h6 class="card-title small mb-0">{{ Str::limit($buku->judul, 20) }}</h6>
                                    <small class="text-muted">{{ $buku->penulis }}</small>
                                </div>
                                <div class="card-footer p-1">
                                    <a href="{{ route('anggota.katalog.show', $buku->id) }}" class="btn btn-sm btn-info w-100">Detail</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('anggota.katalog.index') }}" class="btn btn-info">Lihat Semua Buku</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Riwayat Peminjaman -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Riwayat Peminjaman</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjaman as $pinjam)
                                <tr>
                                    <td>{{ $pinjam->buku->judul }}</td>
                                    <td>{{ $pinjam->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td>{{ $pinjam->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                                    <td>{{ $pinjam->tanggal_kembali ? $pinjam->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($pinjam->status == 'dipinjam')
                                            <span class="badge bg-info">Dipinjam</span>
                                        @elseif($pinjam->status == 'dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @else
                                            <span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada riwayat peminjaman</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('anggota.peminjaman.riwayat') }}" class="btn btn-primary">Lihat Semua Riwayat</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 