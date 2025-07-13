@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detail Anggota</div>

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
                            <h4>{{ $anggota->nama_lengkap }}</h4>
                            <p class="text-muted">{{ $anggota->nomor_anggota }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informasi Pribadi</h5>
                            <table class="table">
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>{{ $anggota->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td>{{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $anggota->alamat }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Telepon</th>
                                    <td>{{ $anggota->nomor_telepon }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $anggota->email }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Informasi Keanggotaan</h5>
                            <table class="table">
                                <tr>
                                    <th>Jenis Anggota</th>
                                    <td>
                                        @if($anggota->jenis_anggota == 'mahasiswa')
                                            <span class="badge bg-primary">Mahasiswa</span>
                                        @elseif($anggota->jenis_anggota == 'dosen')
                                            <span class="badge bg-info">Dosen</span>
                                        @else
                                            <span class="badge bg-secondary">Umum</span>
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
                                <tr>
                                    <th>Tanggal Bergabung</th>
                                    <td>{{ $anggota->tanggal_bergabung ? $anggota->tanggal_bergabung->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Kedaluwarsa</th>
                                    <td>
                                        @if($anggota->tanggal_kedaluwarsa)
                                            @if($anggota->tanggal_kedaluwarsa < now())
                                                <span class="text-danger">{{ $anggota->tanggal_kedaluwarsa->format('d/m/Y') }}</span>
                                            @else
                                                {{ $anggota->tanggal_kedaluwarsa->format('d/m/Y') }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Aksi Cepat</h5>
                            <div class="d-flex gap-2">
                                <form action="/admin/anggota/{{ $anggota->id }}/status" method="POST">
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

                                @if($anggota->status != 'diblokir')
                                    <form action="/admin/anggota/{{ $anggota->id }}/status" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="diblokir">
                                        <button type="submit" class="btn btn-danger">Blokir</button>
                                    </form>
                                @endif

                                <form action="/admin/anggota/{{ $anggota->id }}/perpanjang" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary">Perpanjang Keanggotaan</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Riwayat Peminjaman</h5>
                            @if($anggota->peminjaman->count() > 0)
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
                                            @foreach($anggota->peminjaman as $index => $peminjaman)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $peminjaman->buku->judul }}</td>
                                                <td>{{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d/m/Y') : '-' }}</td>
                                                <td>{{ $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                                                <td>
                                                    @if($peminjaman->status == 'dipinjam')
                                                        <span class="badge bg-primary">Dipinjam</span>
                                                    @elseif($peminjaman->status == 'dikembalikan')
                                                        <span class="badge bg-success">Dikembalikan</span>
                                                    @elseif($peminjaman->status == 'terlambat')
                                                        <span class="badge bg-danger">Terlambat</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p>Belum ada riwayat peminjaman.</p>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">Kembali</a>
                        <div>
                            <a href="{{ route('admin.anggota.edit', $anggota) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.anggota.destroy', $anggota) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" {{ $anggota->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->count() > 0 ? 'disabled' : '' }}>Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 