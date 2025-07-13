@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Katalog Buku</h2>
            <p class="text-muted">Jelajahi koleksi buku perpustakaan kami</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('anggota.katalog.search') }}" method="GET" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Cari judul, penulis, atau ISBN..." value="{{ request('keyword') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="tersedia" value="1" id="tersediaCheck" {{ request('tersedia') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="tersediaCheck">Hanya yang tersedia</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="text-muted">Menampilkan {{ $buku->count() }} dari {{ $buku->total() }} buku</span>
                </div>
                <div>
                    <form action="{{ route('anggota.katalog.index') }}" method="GET" class="d-flex align-items-center">
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        <input type="hidden" name="tersedia" value="{{ request('tersedia') }}">
                        <label for="sortBy" class="me-2">Urutkan:</label>
                        <select name="sort_by" id="sortBy" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                            <option value="judul" {{ request('sort_by') == 'judul' ? 'selected' : '' }}>Judul</option>
                            <option value="penulis" {{ request('sort_by') == 'penulis' ? 'selected' : '' }}>Penulis</option>
                            <option value="tahun_terbit" {{ request('sort_by') == 'tahun_terbit' ? 'selected' : '' }}>Tahun Terbit</option>
                        </select>
                        <select name="sort_order" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>A-Z</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Z-A</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($buku as $b)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    @if($b->gambar_sampul)
                        <img src="{{ asset('storage/sampul/' . $b->gambar_sampul) }}" class="card-img-top" alt="{{ $b->judul }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light text-center py-5">
                            <i class="fas fa-book fa-4x text-secondary"></i>
                        </div>
                    @endif
                    
                    @if($b->jumlah_tersedia <= 0)
                        <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded-pill">
                            <small>Tidak Tersedia</small>
                        </div>
                    @elseif($b->jumlah_tersedia <= 2)
                        <div class="position-absolute top-0 end-0 bg-warning text-dark px-2 py-1 m-2 rounded-pill">
                            <small>Stok Terbatas</small>
                        </div>
                    @else
                        <div class="position-absolute top-0 end-0 bg-success text-white px-2 py-1 m-2 rounded-pill">
                            <small>Tersedia</small>
                        </div>
                    @endif
                </div>
                
                <div class="card-body">
                    <h5 class="card-title">{{ Str::limit($b->judul, 50) }}</h5>
                    <p class="card-text text-muted mb-1">{{ $b->penulis }}</p>
                    <p class="card-text">
                        <small class="text-muted">
                            <i class="fas fa-tag me-1"></i> {{ $b->kategori->nama_kategori }}
                        </small>
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-{{ $b->kondisi == 'baik' ? 'success' : ($b->kondisi == 'rusak' ? 'danger' : 'warning') }}">
                            {{ ucfirst($b->kondisi) }}
                        </span>
                        <small class="text-muted">{{ $b->tahun_terbit }}</small>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('anggota.katalog.show', $b->id) }}" class="btn btn-primary w-100">
                        <i class="fas fa-info-circle me-1"></i> Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Tidak ada buku yang ditemukan
            </div>
        </div>
        @endforelse
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            {{ $buku->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection 