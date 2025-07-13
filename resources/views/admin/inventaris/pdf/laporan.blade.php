<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2, .header h3 {
            margin: 5px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-style: italic;
        }
        .page-break {
            page-break-after: always;
        }
        .badge-success {
            color: green;
            font-weight: bold;
        }
        .badge-danger {
            color: red;
            font-weight: bold;
        }
        .badge-warning {
            color: orange;
            font-weight: bold;
        }
        .badge-primary {
            color: blue;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN INVENTARIS PERPUSTAKAAN</h2>
        <h3>{{ date('d F Y') }}</h3>
        <p>Total Data: {{ $inventaris->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Tanggal Pemeriksaan</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Lokasi</th>
                <th>Petugas</th>
                <th>Perlu Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventaris as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    {{ $item->buku->judul }}<br>
                    <small>{{ $item->buku->penulis }}</small>
                </td>
                <td>{{ $item->tanggal_pemeriksaan->format('d/m/Y') }}</td>
                <td>
                    @if($item->kondisi == 'baik')
                        <span class="badge-success">Baik</span>
                    @elseif($item->kondisi == 'rusak')
                        <span class="badge-danger">Rusak</span>
                    @else
                        <span class="badge-warning">Perlu Perbaikan</span>
                    @endif
                </td>
                <td>
                    @if($item->status_inventaris == 'tersedia')
                        <span class="badge-success">Tersedia</span>
                    @elseif($item->status_inventaris == 'dipinjam')
                        <span class="badge-primary">Dipinjam</span>
                    @elseif($item->status_inventaris == 'dalam_perbaikan')
                        <span class="badge-warning">Dalam Perbaikan</span>
                    @else
                        <span class="badge-danger">Hilang</span>
                    @endif
                </td>
                <td>{{ $item->lokasi_penyimpanan ?? '-' }}</td>
                <td>{{ $item->petugas }}</td>
                <td>
                    @if($item->perlu_tindakan_lanjut)
                        <span class="badge-danger">Ya</span>
                    @else
                        <span>Tidak</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name }} | {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>