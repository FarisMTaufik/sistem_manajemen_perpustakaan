@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Manajemen Inventaris Buku</h5>
                        <div>
                            <a href="{{ route('admin.inventaris.buku-list') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Pemeriksaan Baru
                            </a>
                            <a href="{{ route('admin.inventaris.perlu-perbaikan') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-tools"></i> Buku Perlu Perbaikan
                            </a>
                            <a href="{{ route('admin.inventaris.laporan') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-clipboard"></i> Laporan Inventaris
                            </a>
                        </div>
                    </div>
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

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Pemeriksaan</th>
                                    <th>Kondisi</th>
                                    <th>Status</th>
                                    <th>Petugas</th>
                                    <th>Perlu Tindakan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventaris as $index => $item)
                                <tr>
                                    <td>{{ $index + $inventaris->firstItem() }}</td>
                                    <td>{{ $item->buku->judul }}</td>
                                    <td>{{ $item->tanggal_pemeriksaan->format('d/m/Y') }}</td>
                                    <td>
                                        @if($item->kondisi == 'baik')
                                            <span class="badge bg-success">Baik</span>
                                        @elseif($item->kondisi == 'rusak')
                                            <span class="badge bg-danger">Rusak</span>
                                        @else
                                            <span class="badge bg-warning">Perlu Perbaikan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status_inventaris == 'tersedia')
                                            <span class="badge bg-success">Tersedia</span>
                                        @elseif($item->status_inventaris == 'dipinjam')
                                            <span class="badge bg-primary">Dipinjam</span>
                                        @elseif($item->status_inventaris == 'dalam_perbaikan')
                                            <span class="badge bg-warning">Dalam Perbaikan</span>
                                        @else
                                            <span class="badge bg-danger">Hilang</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->petugas }}</td>
                                    <td>
                                        @if($item->perlu_tindakan_lanjut)
                                            <span class="badge bg-danger">Ya</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.inventaris.show', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                                            <a href="{{ route('admin.inventaris.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('admin.inventaris.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data inventaris</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $inventaris->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 