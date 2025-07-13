@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Buku Perlu Perbaikan</h5>
                        <a href="{{ route('admin.inventaris.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
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
                                    <th>Penulis</th>
                                    <th>Kondisi</th>
                                    <th>Status</th>
                                    <th>Catatan Inventaris</th>
                                    <th>Tanggal Terakhir Diperiksa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($buku as $index => $item)
                                <tr>
                                    <td>{{ $index + $buku->firstItem() }}</td>
                                    <td>{{ $item->judul }}</td>
                                    <td>{{ $item->penulis }}</td>
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
                                    <td>{{ $item->catatan_inventaris ?? '-' }}</td>
                                    <td>{{ $item->tanggal_inventaris ? $item->tanggal_inventaris->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.inventaris.kelola-perbaikan', $item->id) }}" class="btn btn-primary btn-sm">
                                            @if($item->status_inventaris == 'dalam_perbaikan')
                                                Kelola Perbaikan
                                            @else
                                                Proses Perbaikan
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada buku yang perlu perbaikan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $buku->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 