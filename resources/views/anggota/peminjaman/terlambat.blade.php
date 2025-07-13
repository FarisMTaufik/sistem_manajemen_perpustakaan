@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Peminjaman Terlambat') }}</div>

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
                            <a href="{{ route('anggota.peminjaman.terlambat') }}" class="btn btn-primary">Terlambat</a>
                            <a href="{{ route('anggota.peminjaman.denda') }}" class="btn btn-outline-primary">Denda</a>
                        </div>
                    </div>

                    @if ($peminjamanTerlambat->count() > 0)
                        <div class="alert alert-danger mb-3">
                            <h5 class="alert-heading">Perhatian!</h5>
                            <p>Anda memiliki {{ $peminjamanTerlambat->count() }} peminjaman yang terlambat. Segera kembalikan buku ke perpustakaan untuk menghindari denda yang lebih besar.</p>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Keterlambatan</th>
                                    <th>Estimasi Denda</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjamanTerlambat as $p)
                                    <tr>
                                        <td>{{ $p->id }}</td>
                                        <td>
                                            <a href="{{ route('anggota.katalog.show', $p->buku->id) }}">
                                                {{ $p->buku->judul }}
                                            </a>
                                        </td>
                                        <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                        <td>{{ $p->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                                        <td>
                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $hariTerlambat = (int)$now->diffInDays($p->tanggal_jatuh_tempo);
                                            @endphp
                                            <span class="badge bg-danger">{{ $hariTerlambat }} hari</span>
                                        </td>
                                        <td>
                                            @php
                                                $tarifDenda = 1000; // Rp 1.000 per hari
                                                $estimasiDenda = $hariTerlambat * $tarifDenda;
                                            @endphp
                                            Rp {{ number_format($estimasiDenda, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('anggota.peminjaman.show', $p->id) }}" class="btn btn-sm btn-info">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada peminjaman terlambat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $peminjamanTerlambat->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 