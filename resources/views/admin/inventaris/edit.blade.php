@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Edit Inventaris Buku</h5>
                        <a href="{{ route('admin.inventaris.index') }}" class="btn btn-secondary btn-sm">
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

                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                @if($inventari->buku->gambar_sampul)
                                    <img src="{{ asset('storage/sampul/' . $inventari->buku->gambar_sampul) }}" alt="Sampul {{ $inventari->buku->judul }}" class="img-fluid rounded">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                                        <p class="text-muted">Tidak ada gambar sampul</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h4>{{ $inventari->buku->judul }}</h4>
                                <p class="text-muted">Oleh: {{ $inventari->buku->penulis }}</p>
                                <p><strong>Kategori:</strong> {{ $inventari->buku->kategori->nama_kategori }}</p>
                                <p><strong>ISBN:</strong> {{ $inventari->buku->isbn ?? 'Tidak ada' }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.inventaris.update', $inventari->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="tanggal_pemeriksaan" class="form-label">Tanggal Pemeriksaan</label>
                            <input type="date" class="form-control @error('tanggal_pemeriksaan') is-invalid @enderror" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" value="{{ old('tanggal_pemeriksaan', $inventari->tanggal_pemeriksaan->format('Y-m-d')) }}" required>
                            @error('tanggal_pemeriksaan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kondisi" class="form-label">Kondisi</label>
                            <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi" required>
                                <option value="baik" {{ old('kondisi', $inventari->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak" {{ old('kondisi', $inventari->kondisi) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="perlu_perbaikan" {{ old('kondisi', $inventari->kondisi) == 'perlu_perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                            </select>
                            @error('kondisi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status_inventaris" class="form-label">Status Inventaris</label>
                            <select class="form-select @error('status_inventaris') is-invalid @enderror" id="status_inventaris" name="status_inventaris" required>
                                <option value="tersedia" {{ old('status_inventaris', $inventari->status_inventaris) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="dipinjam" {{ old('status_inventaris', $inventari->status_inventaris) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="dalam_perbaikan" {{ old('status_inventaris', $inventari->status_inventaris) == 'dalam_perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                <option value="hilang" {{ old('status_inventaris', $inventari->status_inventaris) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('status_inventaris')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lokasi_penyimpanan" class="form-label">Lokasi Penyimpanan</label>
                            <input type="text" class="form-control @error('lokasi_penyimpanan') is-invalid @enderror" id="lokasi_penyimpanan" name="lokasi_penyimpanan" value="{{ old('lokasi_penyimpanan', $inventari->lokasi_penyimpanan) }}">
                            @error('lokasi_penyimpanan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3">{{ old('catatan', $inventari->catatan) }}</textarea>
                            @error('catatan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 tanggal-perbaikan" style="display: {{ ($inventari->status_inventaris == 'dalam_perbaikan' || $inventari->kondisi == 'perlu_perbaikan' || $inventari->tanggal_perbaikan) ? 'block' : 'none' }};">
                            <label for="tanggal_perbaikan" class="form-label">Tanggal Mulai Perbaikan</label>
                            <input type="date" class="form-control @error('tanggal_perbaikan') is-invalid @enderror" id="tanggal_perbaikan" name="tanggal_perbaikan" value="{{ old('tanggal_perbaikan', $inventari->tanggal_perbaikan ? $inventari->tanggal_perbaikan->format('Y-m-d') : '') }}">
                            @error('tanggal_perbaikan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 tanggal-selesai" style="display: {{ ($inventari->status_inventaris == 'dalam_perbaikan' || $inventari->kondisi == 'perlu_perbaikan' || $inventari->tanggal_selesai_perbaikan) ? 'block' : 'none' }};">
                            <label for="tanggal_selesai_perbaikan" class="form-label">Estimasi/Tanggal Selesai Perbaikan</label>
                            <input type="date" class="form-control @error('tanggal_selesai_perbaikan') is-invalid @enderror" id="tanggal_selesai_perbaikan" name="tanggal_selesai_perbaikan" value="{{ old('tanggal_selesai_perbaikan', $inventari->tanggal_selesai_perbaikan ? $inventari->tanggal_selesai_perbaikan->format('Y-m-d') : '') }}">
                            @error('tanggal_selesai_perbaikan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input @error('perlu_tindakan_lanjut') is-invalid @enderror" id="perlu_tindakan_lanjut" name="perlu_tindakan_lanjut" value="1" {{ old('perlu_tindakan_lanjut', $inventari->perlu_tindakan_lanjut) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perlu_tindakan_lanjut">Perlu Tindakan Lanjut</label>
                            @error('perlu_tindakan_lanjut')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Perbarui</button>
                        <a href="{{ route('admin.inventaris.show', $inventari->id) }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status_inventaris');
        const kondisiSelect = document.getElementById('kondisi');
        const tindakanCheck = document.getElementById('perlu_tindakan_lanjut');
        const tanggalPerbaikanDiv = document.querySelector('.tanggal-perbaikan');
        const tanggalSelesaiDiv = document.querySelector('.tanggal-selesai');

        // Function to show/hide perbaikan fields
        function updateFields() {
            if (statusSelect.value === 'dalam_perbaikan' || kondisiSelect.value === 'perlu_perbaikan' || tindakanCheck.checked) {
                tanggalPerbaikanDiv.style.display = 'block';
                tanggalSelesaiDiv.style.display = 'block';
            } else {
                tanggalPerbaikanDiv.style.display = 'none';
                tanggalSelesaiDiv.style.display = 'none';
            }
        }

        // Event listeners
        statusSelect.addEventListener('change', updateFields);
        kondisiSelect.addEventListener('change', updateFields);
        tindakanCheck.addEventListener('change', updateFields);
    });
</script>
@endpush
@endsection 