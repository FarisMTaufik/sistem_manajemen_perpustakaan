@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detail Kategori</div>

                <div class="card-body">
                    <div class="mb-3">
                        <h5>Nama Kategori:</h5>
                        <p>{{ $kategori->nama_kategori }}</p>
                    </div>

                    <div class="mb-3">
                        <h5>Deskripsi:</h5>
                        <p>{{ $kategori->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>

                    <div class="mb-3">
                        <h5>Jumlah Buku:</h5>
                        <p>{{ $kategori->buku->count() }} buku</p>
                    </div>

                    <div class="mb-3">
                        <h5>Daftar Buku:</h5>
                        @if($kategori->buku->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul</th>
                                            <th>Penulis</th>
                                            <th>Tahun</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kategori->buku as $index => $buku)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $buku->judul }}</td>
                                            <td>{{ $buku->penulis }}</td>
                                            <td>{{ $buku->tahun_terbit }}</td>
                                            <td>
                                                <a href="{{ route('admin.buku.show', $buku->id) }}" class="btn btn-info btn-sm">Detail</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>Tidak ada buku dalam kategori ini.</p>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Kembali</a>
                        <div>
                            <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 