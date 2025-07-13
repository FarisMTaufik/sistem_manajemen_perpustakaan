@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detail Buku</div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($buku->gambar_sampul)
                                <img src="{{ asset('storage/sampul/' . $buku->gambar_sampul) }}" alt="Sampul {{ $buku->judul }}" class="img-fluid rounded">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                                    <p class="text-muted">Tidak ada gambar sampul</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $buku->judul }}</h3>
                            <p class="text-muted">Oleh: {{ $buku->penulis }}</p>
                            <p><strong>Kategori:</strong> {{ $buku->kategori->nama_kategori }}</p>
                            <p><strong>Penerbit:</strong> {{ $buku->penerbit }}</p>
                            <p><strong>Tahun Terbit:</strong> {{ $buku->tahun_terbit }}</p>
                            <p><strong>ISBN:</strong> {{ $buku->isbn ?? 'Tidak ada' }}</p>
                            <p>
                                <strong>Kondisi:</strong>
                                @if($buku->kondisi == 'baik')
                                    <span class="badge bg-success">Baik</span>
                                @elseif($buku->kondisi == 'rusak')
                                    <span class="badge bg-danger">Rusak</span>
                                @else
                                    <span class="badge bg-warning">Perlu Perbaikan</span>
                                @endif
                            </p>
                            <p><strong>Ketersediaan:</strong> {{ $buku->jumlah_tersedia }} dari {{ $buku->jumlah_salinan }} salinan</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Deskripsi:</h5>
                        <p>{{ $buku->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>

                    @if(auth()->user()->role === 'admin')
                    <div class="mb-4">
                        <h5>Riwayat Peminjaman:</h5>
                        @if($buku->peminjaman->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Anggota</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($buku->peminjaman as $index => $peminjaman)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $peminjaman->anggota->nama }}</td>
                                            <td>{{ $peminjaman->tanggal_pinjam }}</td>
                                            <td>{{ $peminjaman->tanggal_kembali ?? '-' }}</td>
                                            <td>
                                                @if($peminjaman->status == 'dipinjam')
                                                    <span class="badge bg-primary">Dipinjam</span>
                                                @elseif($peminjaman->status == 'dikembalikan')
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                @elseif($peminjaman->status == 'terlambat')
                                                    <span class="badge bg-danger">Terlambat</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>Belum ada riwayat peminjaman untuk buku ini.</p>
                        @endif
                    </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">Kembali</a>
                        <div>
                            <a href="{{ route('admin.buku.edit', $buku->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.buku.destroy', $buku->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" {{ $buku->jumlah_salinan != $buku->jumlah_tersedia ? 'disabled' : '' }}>Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 