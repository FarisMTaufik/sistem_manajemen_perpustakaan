@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Peminjaman') }}</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.peminjaman.update', $peminjaman->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="faris_anggota_id" class="form-label">{{ __('Anggota') }}</label>
                            <select class="form-select @error('faris_anggota_id') is-invalid @enderror" id="faris_anggota_id" name="faris_anggota_id" disabled>
                                <option value="{{ $peminjaman->anggota->id }}" selected>
                                    {{ $peminjaman->anggota->nomor_anggota }} - {{ $peminjaman->anggota->nama_lengkap }}
                                </option>
                            </select>
                            <small class="form-text text-muted">Anggota tidak dapat diubah.</small>
                        </div>

                        <div class="mb-3">
                            <label for="faris_buku_id" class="form-label">{{ __('Buku') }}</label>
                            <select class="form-select @error('faris_buku_id') is-invalid @enderror" id="faris_buku_id" name="faris_buku_id" disabled>
                                <option value="{{ $peminjaman->buku->id }}" selected>
                                    {{ $peminjaman->buku->judul }} ({{ $peminjaman->buku->penulis }})
                                </option>
                            </select>
                            <small class="form-text text-muted">Buku tidak dapat diubah.</small>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label">{{ __('Tanggal Pinjam') }}</label>
                            <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam->format('Y-m-d')) }}" readonly>
                            <small class="form-text text-muted">Tanggal pinjam tidak dapat diubah.</small>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_jatuh_tempo" class="form-label">{{ __('Tanggal Jatuh Tempo') }}</label>
                            <input type="date" class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', $peminjaman->tanggal_jatuh_tempo->format('Y-m-d')) }}" required>
                            @error('tanggal_jatuh_tempo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                <option value="terlambat" {{ $peminjaman->status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.peminjaman.show', $peminjaman->id) }}" class="btn btn-secondary">{{ __('Batal') }}</a>
                            <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 