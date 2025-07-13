@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Daftar Peminjaman') }}</span>
                    <a href="{{ route('staff.peminjaman.create') }}" class="btn btn-primary btn-sm">Tambah Peminjaman</a>
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

                    <div class="mb-3">
                        <div class="btn-group" role="group">
                            <a href="{{ route('staff.peminjaman.index') }}" class="btn btn-outline-primary">Semua</a>
                            <a href="{{ route('staff.peminjaman.index', ['status' => 'dipinjam']) }}" class="btn btn-outline-primary">Dipinjam</a>
                            <a href="{{ route('staff.peminjaman.index', ['status' => 'dikembalikan']) }}" class="btn btn-outline-primary">Dikembalikan</a>
                            <a href="{{ route('staff.peminjaman.index', ['status' => 'terlambat']) }}" class="btn btn-outline-primary">Terlambat</a>
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
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjaman as $p)
                                    <tr>
                                        <td>{{ $p->id }}</td>
                                        <td>
                                            <a href="{{ route('staff.anggota.show', $p->anggota->id) }}">
                                                {{ $p->anggota->nama_lengkap }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('staff.buku.show', $p->buku->id) }}">
                                                {{ $p->buku->judul }}
                                            </a>
                                        </td>
                                        <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                                        <td>{{ $p->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($p->status == 'dipinjam')
                                                <span class="badge bg-info">Dipinjam</span>
                                            @elseif ($p->status == 'dikembalikan')
                                                <span class="badge bg-success">Dikembalikan</span>
                                            @elseif ($p->status == 'terlambat')
                                                <span class="badge bg-danger">Terlambat</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('staff.peminjaman.show', $p->id) }}" class="btn btn-sm btn-info">Detail</a>
                                                
                                                @if ($p->status == 'dipinjam')
                                                    <form action="{{ route('staff.peminjaman.pengembalian', $p->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Apakah Anda yakin buku ini sudah dikembalikan?')">Kembalikan</button>
                                                    </form>
                                                    
                                                    @if ($p->perpanjangan_count < 2)
                                                        <form action="{{ route('staff.peminjaman.perpanjang', $p->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Apakah Anda yakin ingin memperpanjang peminjaman ini?')">Perpanjang</button>
                                                        </form>
                                                    @endif
                                                @endif
                                                
                                                <a href="{{ route('staff.peminjaman.edit', $p->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data peminjaman.</td>
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