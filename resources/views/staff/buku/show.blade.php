@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Buku</span>
                    <a href="{{ route('staff.buku.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4>{{ $buku->judul }}</h4>
                            <p class="text-muted">Oleh: {{ $buku->penulis }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informasi Dasar</h5>
                            <table class="table">
                                <tr>
                                    <th>ISBN</th>
                                    <td>{{ $buku->isbn ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Penerbit</th>
                                    <td>{{ $buku->penerbit }}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Terbit</th>
                                    <td>{{ $buku->tahun_terbit }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $buku->kategori->nama_kategori }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informasi Ketersediaan</h5>
                            <table class="table">
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($buku->jumlah_tersedia > 0)
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kondisi</th>
                                    <td>
                                        @if($buku->kondisi == 'baik')
                                            <span class="badge bg-success">Baik</span>
                                        @elseif($buku->kondisi == 'rusak')
                                            <span class="badge bg-danger">Rusak</span>
                                        @else
                                            <span class="badge bg-warning">Perlu Perbaikan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah Salinan</th>
                                    <td>{{ $buku->jumlah_salinan }}</td>
                                </tr>
                                <tr>
                                    <th>Tersedia</th>
                                    <td>{{ $buku->jumlah_tersedia }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Deskripsi</h5>
                            <p>{{ $buku->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('staff.buku.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                        <div>
                            <a href="{{ route('staff.buku.edit', $buku->id) }}" class="btn btn-warning">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 