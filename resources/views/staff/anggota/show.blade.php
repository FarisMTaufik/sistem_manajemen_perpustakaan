@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Anggota</span>
                    <a href="{{ route('staff.anggota.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
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
                            <div class="d-flex justify-content-between align-items-center">
                                <h4>{{ $anggota->nama_lengkap }}</h4>
                                <span class="badge {{ $anggota->status == 'aktif' ? 'bg-success' : ($anggota->status == 'nonaktif' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($anggota->status) }}
                                </span>
                            </div>
                            <p class="text-muted">Nomor Anggota: {{ $anggota->nomor_anggota }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Aksi Cepat</h5>
                            <div class="d-flex gap-2">
                                <form action="{{ route('staff.anggota.status', $anggota) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    @if($anggota->status == 'aktif')
                                        <input type="hidden" name="status" value="nonaktif">
                                        <button type="submit" class="btn btn-warning">Nonaktifkan</button>
                                    @elseif($anggota->status == 'nonaktif')
                                        <input type="hidden" name="status" value="aktif">
                                        <button type="submit" class="btn btn-success">Aktifkan</button>
                                    @elseif($anggota->status == 'diblokir')
                                        <input type="hidden" name="status" value="aktif">
                                        <button type="submit" class="btn btn-success">Buka Blokir</button>
                                    @endif
                                </form>

                                @if($anggota->tanggal_kedaluwarsa < now())
                                    <form action="{{ route('staff.anggota.perpanjang', $anggota) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-info">Perpanjang Keanggotaan</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informasi Pribadi</h5>
                            <table class="table">
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td>{{ $anggota->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $anggota->email }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Telepon</th>
                                    <td>{{ $anggota->nomor_telepon }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Anggota</th>
                                    <td>{{ ucfirst($anggota->jenis_anggota) }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $anggota->alamat ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informasi Keanggotaan</h5>
                            <table class="table">
                                <tr>
                                    <th>Nomor Anggota</th>
                                    <td>{{ $anggota->nomor_anggota }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Bergabung</th>
                                    <td>{{ $anggota->tanggal_bergabung->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Kedaluwarsa</th>
                                    <td>
                                        @if($anggota->tanggal_kedaluwarsa)
                                            <span class="{{ $anggota->tanggal_kedaluwarsa < now() ? 'text-danger' : '' }}">
                                                {{ $anggota->tanggal_kedaluwarsa->format('d/m/Y') }}
                                                @if($anggota->tanggal_kedaluwarsa < now())
                                                    (Kedaluwarsa)
                                                @endif
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($anggota->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($anggota->status == 'nonaktif')
                                            <span class="badge bg-warning">Nonaktif</span>
                                        @else
                                            <span class="badge bg-danger">Diblokir</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Riwayat Peminjaman</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul Buku</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($anggota->peminjaman as $index => $p)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $p->buku->judul }}</td>
                                            <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                            <td>{{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($p->status == 'dipinjam')
                                                    <span class="badge bg-info">Dipinjam</span>
                                                @elseif($p->status == 'dikembalikan')
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                @else
                                                    <span class="badge bg-danger">Terlambat</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data peminjaman</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('staff.anggota.edit', $anggota) }}" class="btn btn-warning">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 