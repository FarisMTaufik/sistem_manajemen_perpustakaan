@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Profil Anggota</div>

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
                                    <td>{{ $anggota->tanggal_lahir->format('d/m/Y') }}</td>
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
                                    <td>{{ $anggota->tanggal_bergabung->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Kedaluwarsa</th>
                                    <td>
                                        @if($anggota->tanggal_kedaluwarsa)
                                            @if($anggota->tanggal_kedaluwarsa < now())
                                                <span class="text-danger">{{ $anggota->tanggal_kedaluwarsa->format('d/m/Y') }}</span>
                                                <small class="d-block text-danger">Keanggotaan Anda telah kedaluwarsa. Silakan hubungi petugas perpustakaan.</small>
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
                            <h5>Edit Profil</h5>
                            <form method="POST" action="{{ route('anggota.profil.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required>
                                    @error('nama_lengkap')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $anggota->alamat) }}</textarea>
                                    @error('alamat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control @error('nomor_telepon') is-invalid @enderror" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $anggota->nomor_telepon) }}" required>
                                    @error('nomor_telepon')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Perbarui Profil</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Ubah Password</h5>
                            <form method="POST" action="{{ route('anggota.profil.password') }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Password Saat Ini</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-warning">Ubah Password</button>
                                </div>
                            </form>
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
                                                <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 