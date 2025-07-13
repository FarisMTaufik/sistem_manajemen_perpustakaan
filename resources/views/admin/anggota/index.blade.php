@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Manajemen Anggota</span>
                    <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary btn-sm">Tambah Anggota</a>
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

                    <!-- Form Pencarian dan Filter -->
                    <div class="mb-4">
                        <form action="{{ route('admin.anggota.index') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama, nomor anggota, atau email" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="jenis_anggota" class="form-select">
                                    <option value="">Semua Jenis Anggota</option>
                                    <option value="mahasiswa" {{ request('jenis_anggota') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    <option value="dosen" {{ request('jenis_anggota') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                    <option value="umum" {{ request('jenis_anggota') == 'umum' ? 'selected' : '' }}>Umum</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="diblokir" {{ request('status') == 'diblokir' ? 'selected' : '' }}>Diblokir</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Anggota</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Jenis Anggota</th>
                                    <th>Status</th>
                                    <th>Tanggal Kedaluwarsa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($anggota as $index => $item)
                                <tr>
                                    <td>{{ $index + $anggota->firstItem() }}</td>
                                    <td>{{ $item->nomor_anggota }}</td>
                                    <td>{{ $item->nama_lengkap }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        @if($item->jenis_anggota == 'mahasiswa')
                                            <span class="badge bg-primary">Mahasiswa</span>
                                        @elseif($item->jenis_anggota == 'dosen')
                                            <span class="badge bg-info">Dosen</span>
                                        @else
                                            <span class="badge bg-secondary">Umum</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($item->status == 'nonaktif')
                                            <span class="badge bg-warning">Nonaktif</span>
                                        @else
                                            <span class="badge bg-danger">Diblokir</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->tanggal_kedaluwarsa)
                                            @if($item->tanggal_kedaluwarsa < now())
                                                <span class="text-danger">{{ $item->tanggal_kedaluwarsa->format('d/m/Y') }}</span>
                                            @else
                                                {{ $item->tanggal_kedaluwarsa->format('d/m/Y') }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.anggota.show', $item) }}" class="btn btn-info btn-sm">Detail</a>
                                            <a href="{{ route('admin.anggota.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('admin.anggota.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data anggota</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $anggota->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 