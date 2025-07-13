@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('anggota.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('anggota.katalog.index') }}">Katalog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $buku->judul }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body p-0">
                    @if($buku->gambar_sampul)
                        <img src="{{ asset('storage/sampul/' . $buku->gambar_sampul) }}" class="img-fluid rounded" alt="{{ $buku->judul }}">
                    @else
                        <div class="bg-light text-center py-5">
                            <i class="fas fa-book fa-5x text-secondary"></i>
                            <p class="mt-3 text-muted">Tidak ada gambar sampul</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Status Ketersediaan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>
                            @if($buku->jumlah_tersedia > 0)
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger">Tidak Tersedia</span>
                            @endif
                        </h5>
                        <p class="mb-0">
                            <i class="fas fa-book-reader me-2"></i>
                            {{ $buku->jumlah_tersedia }} dari {{ $buku->jumlah_salinan }} salinan tersedia
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6>Kondisi Buku:</h6>
                        <span class="badge bg-{{ $buku->kondisi == 'baik' ? 'success' : ($buku->kondisi == 'rusak' ? 'danger' : 'warning') }} p-2">
                            <i class="fas fa-{{ $buku->kondisi == 'baik' ? 'check-circle' : ($buku->kondisi == 'rusak' ? 'times-circle' : 'exclamation-circle') }} me-1"></i>
                            {{ ucfirst($buku->kondisi) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <h6>Status Inventaris:</h6>
                        <span class="badge bg-{{ $buku->status_inventaris == 'tersedia' ? 'success' : ($buku->status_inventaris == 'dipinjam' ? 'primary' : ($buku->status_inventaris == 'dalam_perbaikan' ? 'warning' : 'danger')) }} p-2">
                            {{ ucfirst(str_replace('_', ' ', $buku->status_inventaris)) }}
                        </span>
                    </div>

                    @if($buku->jumlah_tersedia > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Untuk meminjam buku ini, silakan hubungi petugas perpustakaan.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detail Buku</h5>
                </div>
                <div class="card-body">
                    <h3 class="card-title">{{ $buku->judul }}</h3>
                    <h6 class="card-subtitle mb-3 text-muted">{{ $buku->penulis }}</h6>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-tag me-2"></i> Kategori</span>
                                    <span class="badge bg-secondary rounded-pill">{{ $buku->kategori->nama_kategori }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-building me-2"></i> Penerbit</span>
                                    <span>{{ $buku->penerbit }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-calendar me-2"></i> Tahun Terbit</span>
                                    <span>{{ $buku->tahun_terbit }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-barcode me-2"></i> ISBN</span>
                                    <span>{{ $buku->isbn ?? 'Tidak tersedia' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-clipboard-check me-2"></i> Terakhir Diperiksa</span>
                                    <span>{{ $buku->tanggal_inventaris ? $buku->tanggal_inventaris->format('d/m/Y') : 'Belum pernah' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i class="fas fa-copy me-2"></i> Jumlah Salinan</span>
                                    <span>{{ $buku->jumlah_salinan }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if($buku->deskripsi)
                    <div class="mb-4">
                        <h5>Deskripsi</h5>
                        <p class="card-text">{{ $buku->deskripsi }}</p>
                    </div>
                    @endif

                    @if($buku->catatan_inventaris)
                    <div class="mb-4">
                        <h5>Catatan Inventaris</h5>
                        <p class="card-text">{{ $buku->catatan_inventaris }}</p>
                    </div>
                    @endif

                    @if($buku->jumlah_tersedia > 0)
                        <div class="d-grid gap-2 mt-3">
                            <form action="{{ route('anggota.katalog.pinjam', $buku->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-book-reader me-2"></i> Pinjam Buku Ini
                                </button>
                            </form>
                            <div class="alert alert-info mt-2">
                                <i class="fas fa-info-circle me-2"></i>
                                Buku dapat dipinjam selama 7 hari dan dapat diperpanjang maksimal 2 kali.
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            Maaf, buku ini sedang tidak tersedia untuk dipinjam.
                        </div>
                    @endif
                </div>
            </div>

            @if(count($bukuTerkait) > 0)
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Buku Terkait</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($bukuTerkait as $bt)
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body p-2 text-center">
                                    @if($bt->gambar_sampul)
                                        <img src="{{ asset('storage/sampul/' . $bt->gambar_sampul) }}" class="img-fluid mb-2" alt="{{ $bt->judul }}" style="height: 100px; object-fit: cover;">
                                    @else
                                        <div class="bg-light p-2 mb-2">
                                            <i class="fas fa-book fa-3x text-secondary"></i>
                                        </div>
                                    @endif
                                    <h6 class="card-title small mb-0">{{ Str::limit($bt->judul, 30) }}</h6>
                                </div>
                                <div class="card-footer p-1">
                                    <a href="{{ route('anggota.katalog.show', $bt->id) }}" class="btn btn-sm btn-info w-100">Detail</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 