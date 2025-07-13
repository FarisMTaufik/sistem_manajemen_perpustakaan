@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Restore Database</h2>
            <p class="text-muted">Mengembalikan database dari file backup</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Perhatian - Tindakan Berbahaya
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <h5 class="alert-heading">Peringatan!</h5>
                        <p>Anda akan melakukan restore database dari file <strong>{{ $filename }}</strong>.</p>
                        <hr>
                        <p class="mb-0">Tindakan ini akan <strong>menimpa seluruh data yang ada saat ini</strong> dengan data dari file backup. Pastikan Anda telah membuat backup data terbaru sebelum melanjutkan.</p>
                    </div>
                    
                    <form action="{{ route('admin.security.backups.restore', $filename) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="confirm" class="form-label">Konfirmasi Restore</label>
                            <div class="input-group">
                                <span class="input-group-text">Ketik "RESTORE" untuk melanjutkan</span>
                                <input type="text" name="confirm" id="confirm" class="form-control @error('confirm') is-invalid @enderror" required>
                                @error('confirm')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-text">
                                Ketik kata "RESTORE" (huruf besar) untuk mengkonfirmasi bahwa Anda memahami risiko dan ingin melanjutkan.
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <a href="{{ route('admin.security.backups') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-undo me-1"></i> Restore Database
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 