@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Pencarian Buku</span>
                    <a href="{{ route('staff.buku.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>

                <div class="card-body">
                    <!-- Form Pencarian -->
                    <div class="mb-4">
                        <form action="{{ route('staff.buku.search') }}" method="GET" class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="Cari judul, penulis, atau ISBN" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <select name="kategori" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategori as $kat)
                                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Cari</button>
                            </div>
                        </form>
                    </div>

                    @if(isset($results))
                        <div class="mt-4">
                            <h5>Hasil Pencarian: {{ $results->total() }} buku ditemukan</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Penulis</th>
                                            <th>Kategori</th>
                                            <th>Tahun</th>
                                            <th>ISBN</th>
                                            <th>Tersedia</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($results as $item)
                                        <tr>
                                            <td>{{ $item->judul }}</td>
                                            <td>{{ $item->penulis }}</td>
                                            <td>{{ $item->kategori->nama_kategori }}</td>
                                            <td>{{ $item->tahun_terbit }}</td>
                                            <td>{{ $item->isbn ?? '-' }}</td>
                                            <td>{{ $item->jumlah_tersedia }}/{{ $item->jumlah_salinan }}</td>
                                            <td>
                                                <a href="{{ route('staff.buku.show', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada buku yang ditemukan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-3">
                                {{ $results->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 