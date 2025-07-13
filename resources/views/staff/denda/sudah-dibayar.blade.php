@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Denda Sudah Dibayar') }}</span>
                    <div>
                        <a href="{{ route('staff.denda.laporan') }}" class="btn btn-info btn-sm me-2">Laporan Denda</a>
                        <form action="{{ route('staff.denda.hitung-otomatis') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghitung denda otomatis untuk semua peminjaman terlambat?')">Hitung Denda Otomatis</button>
                        </form>
                    </div>
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

                    <div class="mb-3">
                        <div class="btn-group" role="group">
                            <a href="{{ route('staff.denda.index') }}" class="btn btn-outline-primary">Semua</a>
                            <a href="{{ route('staff.denda.belum-dibayar') }}" class="btn btn-outline-primary">Belum Dibayar</a>
                            <a href="{{ route('staff.denda.sudah-dibayar') }}" class="btn btn-primary">Sudah Dibayar</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Anggota</th>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Jumlah Denda</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($denda as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>
                                            <a href="{{ route('staff.anggota.show', $d->peminjaman->anggota) }}">
                                                {{ $d->peminjaman->anggota->nama_lengkap }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('staff.buku.show', $d->peminjaman->buku) }}">
                                                {{ $d->peminjaman->buku->judul }}
                                            </a>
                                        </td>
                                        <td>{{ $d->peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                        <td>{{ $d->peminjaman->tanggal_kembali ? $d->peminjaman->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                        <td>Rp {{ number_format($d->jumlah_denda, 0, ',', '.') }}</td>
                                        <td>{{ $d->tanggal_pembayaran->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('staff.denda.show', $d->id) }}" class="btn btn-sm btn-info">Detail</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data denda yang sudah dibayar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $denda->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 