@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Tambah Peminjaman Baru') }}</span>
                    <a href="{{ route('staff.peminjaman.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('staff.peminjaman.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="faris_anggota_id" class="form-label">Anggota</label>
                            <select class="form-select @error('faris_anggota_id') is-invalid @enderror" id="faris_anggota_id" name="faris_anggota_id" required>
                                <option value="">Pilih Anggota</option>
                                @foreach ($anggota as $a)
                                    <option value="{{ $a->id }}" {{ old('faris_anggota_id') == $a->id ? 'selected' : '' }}>{{ $a->nomor_anggota }} - {{ $a->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            @error('faris_anggota_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="faris_buku_id" class="form-label">Buku</label>
                            <select class="form-select @error('faris_buku_id') is-invalid @enderror" id="faris_buku_id" name="faris_buku_id" required>
                                <option value="">Pilih Buku</option>
                                @foreach ($buku as $b)
                                    <option value="{{ $b->id }}" {{ old('faris_buku_id') == $b->id ? 'selected' : '' }}>{{ $b->judul }} (Tersedia: {{ $b->jumlah_tersedia }})</option>
                                @endforeach
                            </select>
                            @error('faris_buku_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                            @error('tanggal_pinjam')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                            <input type="date" class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', date('Y-m-d', strtotime('+7 days'))) }}" required>
                            @error('tanggal_jatuh_tempo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted">Standar lama peminjaman adalah 7 hari.</small>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Peminjaman</button>
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
    // Set default jatuh tempo to 7 days from tanggal_pinjam
    document.getElementById('tanggal_pinjam').addEventListener('change', function() {
        const tanggalPinjam = new Date(this.value);
        const tanggalJatuhTempo = new Date(tanggalPinjam);
        tanggalJatuhTempo.setDate(tanggalJatuhTempo.getDate() + 7);
        
        const year = tanggalJatuhTempo.getFullYear();
        const month = String(tanggalJatuhTempo.getMonth() + 1).padStart(2, '0');
        const day = String(tanggalJatuhTempo.getDate()).padStart(2, '0');
        
        document.getElementById('tanggal_jatuh_tempo').value = `${year}-${month}-${day}`;
    });
</script>
@endsection 