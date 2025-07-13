@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Backup Database</h2>
            <p class="text-muted">Kelola backup database sistem</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Buat Backup Baru</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.security.backups.create') }}" method="POST">
                        @csrf
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Backup database akan membuat salinan dari seluruh data perpustakaan. Proses ini mungkin membutuhkan waktu beberapa saat tergantung ukuran database.
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-database me-1"></i> Buat Backup Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar File Backup</h5>
                        <span class="badge bg-primary">{{ count($backups) }} file</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama File</th>
                                    <th>Ukuran</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($backups as $backup)
                                <tr>
                                    <td>{{ $backup['name'] }}</td>
                                    <td>{{ $backup['size'] }}</td>
                                    <td>{{ $backup['date'] }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.security.backups.download', $backup['name']) }}" class="btn btn-sm btn-info" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="{{ route('admin.security.backups.show-restore', $backup['name']) }}" class="btn btn-sm btn-warning" title="Restore">
                                                <i class="fas fa-undo"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteBackupModal{{ str_replace('.', '', $backup['name']) }}" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal Konfirmasi Hapus -->
                                        <div class="modal fade" id="deleteBackupModal{{ str_replace('.', '', $backup['name']) }}" tabindex="-1" aria-labelledby="deleteBackupModalLabel{{ str_replace('.', '', $backup['name']) }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteBackupModalLabel{{ str_replace('.', '', $backup['name']) }}">Konfirmasi Hapus Backup</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus file backup <strong>{{ $backup['name'] }}</strong>?</p>
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            Tindakan ini tidak dapat dibatalkan.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('admin.security.backups.delete', $backup['name']) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">Tidak ada file backup</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Backup</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <h6><i class="fas fa-info-circle me-2"></i> Backup Otomatis</h6>
                        <p class="mb-0">Sistem melakukan backup otomatis setiap hari pukul 01:00 dan backup mingguan setiap hari Minggu pukul 02:00.</p>
                    </div>
                    
                    <div class="alert alert-warning mb-3">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i> Penting</h6>
                        <p class="mb-0">Semua file backup dienkripsi untuk keamanan. Pastikan untuk menyimpan file backup di tempat yang aman.</p>
                    </div>
                    
                    <div class="alert alert-danger mb-0">
                        <h6><i class="fas fa-exclamation-circle me-2"></i> Perhatian</h6>
                        <p class="mb-0">Restore database akan menimpa seluruh data yang ada saat ini. Lakukan dengan hati-hati dan pastikan Anda memiliki backup terbaru.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 