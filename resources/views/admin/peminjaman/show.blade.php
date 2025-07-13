@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Detail Peminjaman') }}</span>
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
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
                        <div class="col-md-6">
                            <h5>Informasi Peminjaman</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID Peminjaman</th>
                                    <td>{{ $peminjaman->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pinjam</th>
                                    <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Jatuh Tempo</th>
                                    <td>{{ $peminjaman->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Kembali</th>
                                    <td>{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if ($peminjaman->status == 'dipinjam')
                                            <span class="badge bg-info">Dipinjam</span>
                                        @elseif ($peminjaman->status == 'dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @elseif ($peminjaman->status == 'terlambat')
                                            <span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Perpanjangan</th>
                                    <td>{{ $peminjaman->perpanjangan_count }} kali</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informasi Anggota</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nomor Anggota</th>
                                    <td>{{ $peminjaman->anggota->nomor_anggota }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td>{{ $peminjaman->anggota->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $peminjaman->anggota->email }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Telepon</th>
                                    <td>{{ $peminjaman->anggota->nomor_telepon }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if ($peminjaman->anggota->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif ($peminjaman->anggota->status == 'nonaktif')
                                            <span class="badge bg-warning">Nonaktif</span>
                                        @elseif ($peminjaman->anggota->status == 'diblokir')
                                            <span class="badge bg-danger">Diblokir</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Informasi Buku</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Judul</th>
                                    <td>{{ $peminjaman->buku->judul }}</td>
                                </tr>
                                <tr>
                                    <th>Penulis</th>
                                    <td>{{ $peminjaman->buku->penulis }}</td>
                                </tr>
                                <tr>
                                    <th>Penerbit</th>
                                    <td>{{ $peminjaman->buku->penerbit }}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Terbit</th>
                                    <td>{{ $peminjaman->buku->tahun_terbit }}</td>
                                </tr>
                                <tr>
                                    <th>ISBN</th>
                                    <td>{{ $peminjaman->buku->isbn }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $peminjaman->buku->kategori->nama_kategori }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if ($peminjaman->denda)
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5>Informasi Denda</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Jumlah Denda</th>
                                        <td>Rp {{ number_format($peminjaman->denda->jumlah_denda, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Pembayaran</th>
                                        <td>
                                            @if ($peminjaman->denda->status_pembayaran == 'belum_dibayar')
                                                <span class="badge bg-danger">Belum Dibayar</span>
                                            @else
                                                <span class="badge bg-success">Sudah Dibayar</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Pembayaran</th>
                                        <td>{{ $peminjaman->denda->tanggal_pembayaran ? $peminjaman->denda->tanggal_pembayaran->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                </table>

                                @if ($peminjaman->denda->status_pembayaran == 'belum_dibayar')
                                    <form action="{{ route('admin.denda.bayar', $peminjaman->denda->id) }}" method="POST" class="mt-3">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin denda ini sudah dibayar?')">Tandai Sudah Dibayar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.peminjaman.edit', $peminjaman->id) }}" class="btn btn-primary">Edit</a>
                                
                                @if ($peminjaman->status == 'dipinjam')
                                    <form action="{{ route('admin.peminjaman.pengembalian', $peminjaman->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin buku ini sudah dikembalikan?')">Kembalikan</button>
                                    </form>
                                    
                                    @if ($peminjaman->perpanjangan_count < 2)
                                        <form action="{{ route('admin.peminjaman.perpanjangan', $peminjaman->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" onclick="return confirm('Apakah Anda yakin ingin memperpanjang peminjaman ini?')">Perpanjang</button>
                                        </form>
                                    @endif
                                @endif
                                
                                <form action="{{ route('admin.peminjaman.destroy', $peminjaman->id) }}" method="POST">
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