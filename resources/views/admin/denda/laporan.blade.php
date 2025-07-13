@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Laporan Denda') }}</span>
                    <a href="{{ route('admin.denda.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('admin.denda.laporan') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tanggal_mulai" class="form-label">{{ __('Tanggal Mulai') }}</label>
                                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tanggal_akhir" class="form-label">{{ __('Tanggal Akhir') }}</label>
                                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggalAkhir->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                                    <a href="{{ route('admin.denda.laporan') }}" class="btn btn-secondary">{{ __('Reset') }}</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Denda</h5>
                                    <h3 class="card-text">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Dibayar</h5>
                                    <h3 class="card-text">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Belum Dibayar</h5>
                                    <h3 class="card-text">Rp {{ number_format($totalBelumDibayar, 0, ',', '.') }}</h3>
                                </div>
                            </div>
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
                                    <th>Keterlambatan</th>
                                    <th>Jumlah Denda</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($denda as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.anggota.show', $d->peminjaman->anggota) }}">
                                                {{ $d->peminjaman->anggota->nama_lengkap }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.buku.show', $d->peminjaman->buku->id) }}">
                                                {{ $d->peminjaman->buku->judul }}
                                            </a>
                                        </td>
                                        <td>{{ $d->peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                        <td>{{ $d->peminjaman->tanggal_kembali ? $d->peminjaman->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            @php
                                                $tanggalKembali = $d->peminjaman->tanggal_kembali ?? \Carbon\Carbon::now();
                                                $hariTerlambat = $tanggalKembali->diffInDays($d->peminjaman->tanggal_jatuh_tempo);
                                            @endphp
                                            {{ $hariTerlambat }} hari
                                        </td>
                                        <td>Rp {{ number_format($d->jumlah_denda, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($d->status_pembayaran == 'belum_dibayar')
                                                <span class="badge bg-danger">Belum Dibayar</span>
                                            @else
                                                <span class="badge bg-success">Sudah Dibayar ({{ $d->tanggal_pembayaran->format('d/m/Y') }})</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data denda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 