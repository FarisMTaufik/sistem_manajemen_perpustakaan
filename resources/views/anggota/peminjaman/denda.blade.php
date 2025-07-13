@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Denda Saya') }}</div>

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
                            <a href="{{ route('anggota.peminjaman.index') }}" class="btn btn-outline-primary">Semua</a>
                            <a href="{{ route('anggota.peminjaman.aktif') }}" class="btn btn-outline-primary">Aktif</a>
                            <a href="{{ route('anggota.peminjaman.riwayat') }}" class="btn btn-outline-primary">Riwayat</a>
                            <a href="{{ route('anggota.peminjaman.terlambat') }}" class="btn btn-outline-primary">Terlambat</a>
                            <a href="{{ route('anggota.peminjaman.denda') }}" class="btn btn-primary">Denda</a>
                        </div>
                    </div>

                    @if ($peminjaman->count() > 0)
                        <div class="alert alert-warning mb-3">
                            <h5 class="alert-heading">Informasi Denda</h5>
                            <p>Anda memiliki {{ $peminjaman->count() }} denda yang belum dibayar. Silakan lakukan pembayaran ke petugas perpustakaan.</p>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Keterlambatan</th>
                                    <th>Jumlah Denda</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjaman as $p)
                                    <tr>
                                        <td>{{ $p->id }}</td>
                                        <td>
                                            <a href="{{ route('anggota.katalog.show', $p->buku->id) }}">
                                                {{ $p->buku->judul }}
                                            </a>
                                        </td>
                                        <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                        <td>{{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            @php
                                                $tanggalKembali = $p->tanggal_kembali ?? \Carbon\Carbon::now();
                                                $hariTerlambat = $tanggalKembali->diffInDays($p->tanggal_jatuh_tempo);
                                            @endphp
                                            <span class="badge bg-danger">{{ $hariTerlambat }} hari</span>
                                        </td>
                                        <td>
                                            Rp {{ number_format($p->denda->jumlah_denda, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">Belum Dibayar</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('anggota.peminjaman.show', $p->id) }}" class="btn btn-sm btn-info">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada denda yang belum dibayar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $peminjaman->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 