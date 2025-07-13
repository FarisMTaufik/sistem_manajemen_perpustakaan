@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Kelola Perbaikan Buku</h5>
                        <a href="{{ route('admin.inventaris.perlu-perbaikan') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                @if($buku->gambar_sampul)
                                    <img src="{{ asset('storage/sampul/' . $buku->gambar_sampul) }}" alt="Sampul {{ $buku->judul }}" class="img-fluid rounded">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                                        <p class="text-muted">Tidak ada gambar sampul</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4>{{ $buku->judul }}</h4>
                                <p class="text-muted">Oleh: {{ $buku->penulis }}</p>
                                <p><strong>Kategori:</strong> {{ $buku->kategori->nama_kategori ?? 'Tidak ada kategori' }}</p>
                                <p><strong>ISBN:</strong> {{ $buku->isbn ?? 'Tidak ada' }}</p>
                                <p>
                                    <strong>Kondisi Saat Ini:</strong>
                                    @if($buku->kondisi == 'baik')
                                        <span class="badge bg-success">Baik</span>
                                    @elseif($buku->kondisi == 'rusak')
                                        <span class="badge bg-danger">Rusak</span>
                                    @else
                                        <span class="badge bg-warning">Perlu Perbaikan</span>
                                    @endif
                                </p>
                                <p>
                                    <strong>Status Saat Ini:</strong>
                                    @if($buku->status_inventaris == 'tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($buku->status_inventaris == 'dipinjam')
                                        <span class="badge bg-primary">Dipinjam</span>
                                    @elseif($buku->status_inventaris == 'dalam_perbaikan')
                                        <span class="badge bg-warning">Dalam Perbaikan</span>
                                    @else
                                        <span class="badge bg-danger">Hilang</span>
                                    @endif
                                </p>
                                <p><strong>Terakhir Diperiksa:</strong> {{ isset($buku->tanggal_inventaris) ? $buku->tanggal_inventaris->format('d F Y') : 'Belum pernah diperiksa' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Catatan Inventaris Terakhir</h6>
                        </div>
                        <div class="card-body">
                            {{ $buku->catatan_inventaris ?? 'Tidak ada catatan inventaris' }}
                        </div>
                    </div>

                    @if($buku->status_inventaris == 'dalam_perbaikan')
                        <!-- Form Selesaikan Perbaikan -->
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Selesaikan Proses Perbaikan</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.inventaris.selesaikan-perbaikan', $buku->id) }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai Perbaikan</label>
                                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d')) }}" required>
                                        @error('tanggal_selesai')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('tanggal_selesai') }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="hasil_perbaikan" class="form-label">Hasil Perbaikan</label>
                                        <textarea class="form-control @error('hasil_perbaikan') is-invalid @enderror" id="hasil_perbaikan" name="hasil_perbaikan" rows="3" required>{{ old('hasil_perbaikan') }}</textarea>
                                        @error('hasil_perbaikan')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('hasil_perbaikan') }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="kondisi_setelah" class="form-label">Kondisi Setelah Perbaikan</label>
                                        <select class="form-select @error('kondisi_setelah') is-invalid @enderror" id="kondisi_setelah" name="kondisi_setelah" required>
                                            <option value="baik" {{ old('kondisi_setelah') == 'baik' ? 'selected' : '' }}>Baik</option>
                                            <option value="rusak" {{ old('kondisi_setelah') == 'rusak' ? 'selected' : '' }}>Rusak (Tidak Dapat Diperbaiki)</option>
                                            <option value="perlu_perbaikan" {{ old('kondisi_setelah') == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan Lanjutan</option>
                                        </select>
                                        @error('kondisi_setelah')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('kondisi_setelah') }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-success">Selesaikan Perbaikan</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Form Proses Perbaikan -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Proses Perbaikan Buku</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.inventaris.proses-perbaikan', $buku->id) }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="tanggal_perbaikan" class="form-label">Tanggal Mulai Perbaikan</label>
                                        <input type="date" class="form-control @error('tanggal_perbaikan') is-invalid @enderror" id="tanggal_perbaikan" name="tanggal_perbaikan" value="{{ old('tanggal_perbaikan', date('Y-m-d')) }}" required>
                                        @error('tanggal_perbaikan')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('tanggal_perbaikan') }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="catatan" class="form-label">Catatan Perbaikan</label>
                                        <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3" required>{{ old('catatan') }}</textarea>
                                        @error('catatan')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('catatan') }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="estimasi_selesai" class="form-label">Estimasi Selesai Perbaikan</label>
                                        <input type="date" class="form-control @error('estimasi_selesai') is-invalid @enderror" id="estimasi_selesai" name="estimasi_selesai" value="{{ old('estimasi_selesai', date('Y-m-d', strtotime('+7 days'))) }}" required>
                                        @error('estimasi_selesai')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('estimasi_selesai') }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Proses Perbaikan</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <h5 class="mb-3 mt-4">Riwayat Inventaris Buku</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kondisi</th>
                                    <th>Status</th>
                                    <th>Petugas</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatInventaris as $inventari)
                                <tr>
                                    <td>{{ $inventari->tanggal_pemeriksaan ? $inventari->tanggal_pemeriksaan->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        @if($inventari->kondisi == 'baik')
                                            <span class="badge bg-success">Baik</span>
                                        @elseif($inventari->kondisi == 'rusak')
                                            <span class="badge bg-danger">Rusak</span>
                                        @else
                                            <span class="badge bg-warning">Perlu Perbaikan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($inventari->status_inventaris == 'tersedia')
                                            <span class="badge bg-success">Tersedia</span>
                                        @elseif($inventari->status_inventaris == 'dipinjam')
                                            <span class="badge bg-primary">Dipinjam</span>
                                        @elseif($inventari->status_inventaris == 'dalam_perbaikan')
                                            <span class="badge bg-warning">Dalam Perbaikan</span>
                                        @else
                                            <span class="badge bg-danger">Hilang</span>
                                        @endif
                                    </td>
                                    <td>{{ $inventari->petugas ?? '-' }}</td>
                                    <td>{{ $inventari->catatan ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada riwayat inventaris</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 