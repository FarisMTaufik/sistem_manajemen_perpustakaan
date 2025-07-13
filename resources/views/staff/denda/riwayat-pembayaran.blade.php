@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Riwayat Pembayaran Denda') }}</span>
                    <a href="{{ route('staff.denda.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('staff.denda.riwayat-pembayaran') }}" class="mb-4">
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
                                    <a href="{{ route('staff.denda.riwayat-pembayaran') }}" class="btn btn-secondary">{{ __('Reset') }}</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Pembayaran Denda</h5>
                                    <h3 class="card-text">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</h3>
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
                                    <th>Tanggal Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatPembayaran as $d)
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
                                        <td>
                                            @php
                                                $tanggalKembali = $d->peminjaman->tanggal_kembali ?? \Carbon\Carbon::now();
                                                $hariTerlambat = $tanggalKembali->diffInDays($d->peminjaman->tanggal_jatuh_tempo);
                                            @endphp
                                            {{ $hariTerlambat }} hari
                                        </td>
                                        <td>Rp {{ number_format($d->jumlah_denda, 0, ',', '.') }}</td>
                                        <td>{{ $d->tanggal_pembayaran->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada riwayat pembayaran denda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $riwayatPembayaran->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 