@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pilih Buku untuk Inventarisasi</h5>
                        <a href="{{ route('admin.inventaris.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.inventaris.buku-list') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="Cari judul, penulis, ISBN..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">Cari</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="kondisi" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Kondisi</option>
                                    <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak" {{ request('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                    <option value="perlu_perbaikan" {{ request('kondisi') == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="dalam_perbaikan" {{ request('status') == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                    <option value="hilang" {{ request('status') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.inventaris.buku-list') }}" class="btn btn-secondary w-100">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Kategori</th>
                                    <th>Kondisi</th>
                                    <th>Status</th>
                                    <th>Ketersediaan</th>
                                    <th>Terakhir Diperiksa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($buku as $index => $item)
                                <tr>
                                    <td>{{ $index + $buku->firstItem() }}</td>
                                    <td>{{ $item->judul }}</td>
                                    <td>{{ $item->penulis }}</td>
                                    <td>{{ $item->kategori->nama_kategori }}</td>
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
                                    <td>{{ $item->jumlah_tersedia }}/{{ $item->jumlah_salinan }}</td>
                                    <td>{{ $item->tanggal_inventaris ? $item->tanggal_inventaris->format('d/m/Y') : 'Belum pernah' }}</td>
                                    <td>
                                        <a href="{{ route('admin.inventaris.create', $item->id) }}" class="btn btn-primary btn-sm">Periksa</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data buku</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $buku->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 