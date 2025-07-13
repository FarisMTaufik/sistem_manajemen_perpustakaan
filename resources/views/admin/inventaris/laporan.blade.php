@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Laporan Inventaris Buku</h5>
                        <a href="{{ route('admin.inventaris.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.inventaris.laporan') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="kondisi" class="form-label">Kondisi</label>
                                    <select class="form-select" id="kondisi" name="kondisi">
                                        <option value="">Semua Kondisi</option>
                                        <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="rusak" {{ request('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                        <option value="perlu_perbaikan" {{ request('kondisi') == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                        <option value="dalam_perbaikan" {{ request('status') == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                        <option value="hilang" {{ request('status') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="perlu_tindakan_lanjut" class="form-label">Perlu Tindakan</label>
                                    <select class="form-select" id="perlu_tindakan_lanjut" name="perlu_tindakan_lanjut">
                                        <option value="">Semua</option>
                                        <option value="1" {{ request('perlu_tindakan_lanjut') == '1' ? 'selected' : '' }}>Ya</option>
                                        <option value="0" {{ request('perlu_tindakan_lanjut') == '0' ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="d-flex justify-content-between mb-3">
                        <h6>Total Data: {{ $inventaris->total() }}</h6>
                        <div class="btn-group">
                            <button class="btn btn-outline-secondary" onclick="window.print()">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                            <a href="{{ route('admin.inventaris.laporan', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Pemeriksaan</th>
                                    <th>Kondisi</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Petugas</th>
                                    <th>Perlu Tindakan</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventaris as $index => $item)
                                <tr>
                                    <td>{{ $index + $inventaris->firstItem() }}</td>
                                    <td>
                                        <a href="{{ route('admin.buku.show', $item->buku->id) }}">{{ $item->buku->judul }}</a>
                                        <small class="d-block text-muted">{{ $item->buku->penulis }}</small>
                                    </td>
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
                                    <td>{{ $item->lokasi_penyimpanan ?? '-' }}</td>
                                    <td>{{ $item->petugas }}</td>
                                    <td>
                                        @if($item->perlu_tindakan_lanjut)
                                            <span class="badge bg-danger">Ya</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->catatan ? \Illuminate\Support\Str::limit($item->catatan, 50) : '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data inventaris</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $inventaris->appends(request()->query())->links() }}
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Ringkasan Laporan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6>Berdasarkan Kondisi</h6>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Baik
                                                    <span class="badge bg-success rounded-pill">
                                                        {{ $inventaris->where('kondisi', 'baik')->count() }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Rusak
                                                    <span class="badge bg-danger rounded-pill">
                                                        {{ $inventaris->where('kondisi', 'rusak')->count() }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Perlu Perbaikan
                                                    <span class="badge bg-warning rounded-pill">
                                                        {{ $inventaris->where('kondisi', 'perlu_perbaikan')->count() }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6>Berdasarkan Status</h6>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Tersedia
                                                    <span class="badge bg-success rounded-pill">
                                                        {{ $inventaris->where('status_inventaris', 'tersedia')->count() }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Dipinjam
                                                    <span class="badge bg-primary rounded-pill">
                                                        {{ $inventaris->where('status_inventaris', 'dipinjam')->count() }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Dalam Perbaikan
                                                    <span class="badge bg-warning rounded-pill">
                                                        {{ $inventaris->where('status_inventaris', 'dalam_perbaikan')->count() }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Hilang
                                                    <span class="badge bg-danger rounded-pill">
                                                        {{ $inventaris->where('status_inventaris', 'hilang')->count() }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6>Tindakan Lanjut</h6>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Perlu Tindakan
                                                    <span class="badge bg-danger rounded-pill">
                                                        {{ $inventaris->where('perlu_tindakan_lanjut', true)->count() }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Tidak Perlu Tindakan
                                                    <span class="badge bg-secondary rounded-pill">
                                                        {{ $inventaris->where('perlu_tindakan_lanjut', false)->count() }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .no-print {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .container {
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>
@endsection