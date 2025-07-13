@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Peminjaman Aktif') }}</div>

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
                            <a href="{{ route('anggota.peminjaman.aktif') }}" class="btn btn-primary">Aktif</a>
                            <a href="{{ route('anggota.peminjaman.riwayat') }}" class="btn btn-outline-primary">Riwayat</a>
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
                                    <th>Jatuh Tempo</th>
                                    <th>Sisa Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjamanAktif as $p)
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
                                                $sisaHari = (int)$now->diffInDays($p->tanggal_jatuh_tempo, false);
                                            @endphp
                                            
                                            @if ($sisaHari > 0)
                                                <span class="badge bg-success">{{ $sisaHari }} hari</span>
                                            @elseif ($sisaHari == 0)
                                                <span class="badge bg-warning">Hari ini</span>
                                            @else
                                                <span class="badge bg-danger">Terlambat {{ abs($sisaHari) }} hari</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('anggota.peminjaman.show', $p->id) }}" class="btn btn-sm btn-info">Detail</a>
                                            
                                            @if ($p->perpanjangan_count < 2 && $sisaHari >= 0)
                                                <form action="{{ route('anggota.peminjaman.perpanjang', $p->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Apakah Anda yakin ingin memperpanjang peminjaman ini?')">Perpanjang</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada peminjaman aktif.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $peminjamanAktif->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 