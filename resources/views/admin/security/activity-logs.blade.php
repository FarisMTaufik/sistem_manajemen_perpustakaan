@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Log Aktivitas</h2>
            <p class="text-muted">Daftar aktivitas pengguna pada sistem</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Filter Log Aktivitas</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.security.activity-logs') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="user_id" class="form-label">Pengguna</label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">Semua Pengguna</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="user_role" class="form-label">Role</label>
                            <select name="user_role" id="user_role" class="form-select">
                                <option value="">Semua Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ request('user_role') == $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="action" class="form-label">Aksi</label>
                            <select name="action" id="action" class="form-select">
                                <option value="">Semua Aksi</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst($action) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="module" class="form-label">Modul</label>
                            <select name="module" id="module" class="form-select">
                                <option value="">Semua Modul</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                        {{ ucfirst($module) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.security.activity-logs') }}" class="btn btn-secondary">
                                <i class="fas fa-sync me-1"></i> Reset
                            </a>
                        </div>
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
                        <h5 class="mb-0">Daftar Log Aktivitas</h5>
                        <span class="badge bg-primary">{{ $logs->total() }} log</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Pengguna</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                    <th>Modul</th>
                                    <th>Deskripsi</th>
                                    <th>IP</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $log->user_name ?? 'Sistem' }}</td>
                                    <td>
                                        @if($log->user_role)
                                            <span class="badge bg-{{ $log->user_role == 'admin' ? 'danger' : ($log->user_role == 'staff' ? 'info' : 'success') }}">
                                                {{ ucfirst($log->user_role) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Sistem</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $log->action == 'view' ? 'info' : 
                                            ($log->action == 'create' ? 'success' : 
                                            ($log->action == 'update' ? 'warning' : 
                                            ($log->action == 'delete' ? 'danger' : 'secondary'))) 
                                        }}">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>
                                    <td>{{ ucfirst($log->module) }}</td>
                                    <td>{{ Str::limit($log->description, 50) }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#logModal{{ $log->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <!-- Modal Detail Log -->
                                        <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" aria-labelledby="logModalLabel{{ $log->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="logModalLabel{{ $log->id }}">Detail Log Aktivitas</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <h6>Waktu:</h6>
                                                            <p>{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h6>Pengguna:</h6>
                                                            <p>{{ $log->user_name ?? 'Sistem' }} ({{ ucfirst($log->user_role ?? 'sistem') }})</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h6>Aksi:</h6>
                                                            <p>{{ ucfirst($log->action) }} pada modul {{ ucfirst($log->module) }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h6>Deskripsi:</h6>
                                                            <p>{{ $log->description }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h6>IP Address:</h6>
                                                            <p>{{ $log->ip_address }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h6>User Agent:</h6>
                                                            <p class="text-muted small">{{ $log->user_agent }}</p>
                                                        </div>
                                                        @if($log->properties)
                                                        <div class="mb-3">
                                                            <h6>Properties:</h6>
                                                            <pre class="bg-light p-3 rounded"><code>{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</code></pre>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3">Tidak ada data log aktivitas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $logs->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 