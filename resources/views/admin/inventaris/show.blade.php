@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Inventaris Buku</h5>
                        <a href="{{ route('admin.inventaris.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                @if($inventari->buku->gambar_sampul)
                                    <img src="{{ asset('storage/sampul/' . $inventari->buku->gambar_sampul) }}" alt="Sampul {{ $inventari->buku->judul }}" class="img-fluid rounded">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                                        <p class="text-muted">Tidak ada gambar sampul</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4>{{ $inventari->buku->judul }}</h4>
                                <p class="text-muted">Oleh: {{ $inventari->buku->penulis }}</p>
                                <p><strong>Kategori:</strong> {{ $inventari->buku->kategori->nama_kategori }}</p>
                                <p><strong>ISBN:</strong> {{ $inventari->buku->isbn ?? 'Tidak ada' }}</p>
                            </div>
                        </div>
                    </div>

                    <h5 class="border-bottom pb-2 mb-3">Informasi Inventaris</h5>

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Tanggal Pemeriksaan</div>
                        <div class="col-md-8">{{ $inventari->tanggal_pemeriksaan->format('d F Y') }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Kondisi</div>
                        <div class="col-md-8">
                            @if($inventari->kondisi == 'baik')
                                <span class="badge bg-success">Baik</span>
                            @elseif($inventari->kondisi == 'rusak')
                                <span class="badge bg-danger">Rusak</span>
                            @else
                                <span class="badge bg-warning">Perlu Perbaikan</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Status Inventaris</div>
                        <div class="col-md-8">
                            @if($inventari->status_inventaris == 'tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($inventari->status_inventaris == 'dipinjam')
                                <span class="badge bg-primary">Dipinjam</span>
                            @elseif($inventari->status_inventaris == 'dalam_perbaikan')
                                <span class="badge bg-warning">Dalam Perbaikan</span>
                            @else
                                <span class="badge bg-danger">Hilang</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Lokasi Penyimpanan</div>
                        <div class="col-md-8">{{ $inventari->lokasi_penyimpanan ?? 'Tidak ada' }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Petugas</div>
                        <div class="col-md-8">{{ $inventari->petugas }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Perlu Tindakan Lanjut</div>
                        <div class="col-md-8">
                            @if($inventari->perlu_tindakan_lanjut)
                                <span class="badge bg-danger">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </div>
                    </div>

                    @if($inventari->status_inventaris == 'dalam_perbaikan' || $inventari->tanggal_perbaikan)
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Tanggal Mulai Perbaikan</div>
                            <div class="col-md-8">{{ $inventari->tanggal_perbaikan ? $inventari->tanggal_perbaikan->format('d F Y') : 'Belum ditetapkan' }}</div>
                        </div>
                    @endif

                    @if($inventari->tanggal_selesai_perbaikan)
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Estimasi/Tanggal Selesai Perbaikan</div>
                            <div class="col-md-8">{{ $inventari->tanggal_selesai_perbaikan->format('d F Y') }}</div>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Catatan</div>
                        <div class="col-md-8">{{ $inventari->catatan ?? 'Tidak ada catatan' }}</div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.inventaris.index') }}" class="btn btn-secondary">Kembali</a>
                        <div>
                            <a href="{{ route('admin.inventaris.edit', $inventari->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.inventaris.destroy', $inventari->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 