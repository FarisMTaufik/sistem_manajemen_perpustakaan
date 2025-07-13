@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Detail Denda') }}</span>
                    <a href="{{ route('admin.denda.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
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
                            <h5>Informasi Denda</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID Denda</th>
                                    <td>{{ $denda->id }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Denda</th>
                                    <td>Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Status Pembayaran</th>
                                    <td>
                                        @if ($denda->status_pembayaran == 'belum_dibayar')
                                            <span class="badge bg-danger">Belum Dibayar</span>
                                        @else
                                            <span class="badge bg-success">Sudah Dibayar</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pembayaran</th>
                                    <td>{{ $denda->tanggal_pembayaran ? $denda->tanggal_pembayaran->format('d/m/Y') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Informasi Peminjaman</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID Peminjaman</th>
                                    <td>{{ $denda->peminjaman->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pinjam</th>
                                    <td>{{ $denda->peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Jatuh Tempo</th>
                                    <td>{{ $denda->peminjaman->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Kembali</th>
                                    <td>{{ $denda->peminjaman->tanggal_kembali ? $denda->peminjaman->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if ($denda->peminjaman->status == 'dipinjam')
                                            <span class="badge bg-info">Dipinjam</span>
                                        @elseif ($denda->peminjaman->status == 'dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @elseif ($denda->peminjaman->status == 'terlambat')
                                            <span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informasi Anggota</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nomor Anggota</th>
                                    <td>{{ $denda->peminjaman->anggota->nomor_anggota }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td>{{ $denda->peminjaman->anggota->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $denda->peminjaman->anggota->email }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Telepon</th>
                                    <td>{{ $denda->peminjaman->anggota->nomor_telepon }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informasi Buku</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Judul</th>
                                    <td>{{ $denda->peminjaman->buku->judul }}</td>
                                </tr>
                                <tr>
                                    <th>Penulis</th>
                                    <td>{{ $denda->peminjaman->buku->penulis }}</td>
                                </tr>
                                <tr>
                                    <th>ISBN</th>
                                    <td>{{ $denda->peminjaman->buku->isbn }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $denda->peminjaman->buku->kategori->nama }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.denda.edit', $denda->id) }}" class="btn btn-primary">Edit</a>
                                
                                @if ($denda->status_pembayaran == 'belum_dibayar')
                                    <form action="{{ route('admin.denda.bayar', $denda->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin denda ini sudah dibayar?')">Tandai Sudah Dibayar</button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.denda.destroy', $denda->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 