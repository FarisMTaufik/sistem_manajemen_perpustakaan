@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Denda') }}</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.denda.update', $denda->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="peminjaman_id" class="form-label">{{ __('Peminjaman') }}</label>
                            <input type="text" class="form-control" value="{{ $denda->peminjaman->anggota->nama_lengkap }} - {{ $denda->peminjaman->buku->judul }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah_denda" class="form-label">{{ __('Jumlah Denda (Rp)') }}</label>
                            <input type="number" class="form-control @error('jumlah_denda') is-invalid @enderror" id="jumlah_denda" name="jumlah_denda" value="{{ old('jumlah_denda', $denda->jumlah_denda) }}" required>
                            @error('jumlah_denda')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status_pembayaran" class="form-label">{{ __('Status Pembayaran') }}</label>
                            <select class="form-select @error('status_pembayaran') is-invalid @enderror" id="status_pembayaran" name="status_pembayaran" required>
                                <option value="belum_dibayar" {{ $denda->status_pembayaran == 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="dibayar" {{ $denda->status_pembayaran == 'dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                            </select>
                            @error('status_pembayaran')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3" id="tanggal_pembayaran_container" style="{{ $denda->status_pembayaran == 'dibayar' ? '' : 'display: none;' }}">
                            <label for="tanggal_pembayaran" class="form-label">{{ __('Tanggal Pembayaran') }}</label>
                            <input type="date" class="form-control @error('tanggal_pembayaran') is-invalid @enderror" id="tanggal_pembayaran" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', $denda->tanggal_pembayaran ? $denda->tanggal_pembayaran->format('Y-m-d') : date('Y-m-d')) }}">
                            @error('tanggal_pembayaran')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.denda.show', $denda->id) }}" class="btn btn-secondary">{{ __('Batal') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusPembayaran = document.getElementById('status_pembayaran');
        const tanggalPembayaranContainer = document.getElementById('tanggal_pembayaran_container');
        
        statusPembayaran.addEventListener('change', function() {
            if (this.value === 'dibayar') {
                tanggalPembayaranContainer.style.display = '';
            } else {
                tanggalPembayaranContainer.style.display = 'none';
            }
        });
    });
</script>
@endsection 