@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Riwayat Peminjaman') }}</div>

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
                            <a href="{{ route('anggota.peminjaman.riwayat') }}" class="btn btn-primary">Riwayat</a>
                            <a href="{{ route('anggota.peminjaman.terlambat') }}" class="btn btn-outline-primary">Terlambat</a>
                            <a href="{{ route('anggota.peminjaman.denda') }}" class="btn btn-outline-primary">Denda</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatPeminjaman as $p)
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
                                            @if ($p->tanggal_kembali && $p->tanggal_kembali->greaterThan($p->tanggal_jatuh_tempo))
                                                <span class="badge bg-danger">Terlambat</span>
                                            @else
                                                <span class="badge bg-success">Tepat Waktu</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('anggota.peminjaman.show', $p->id) }}" class="btn btn-sm btn-info">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada riwayat peminjaman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $riwayatPeminjaman->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 