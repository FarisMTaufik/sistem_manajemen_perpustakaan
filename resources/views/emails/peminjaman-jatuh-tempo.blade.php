<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengingat Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3490dc;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .book-info {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8em;
            color: #6c757d;
        }
        .button {
            display: inline-block;
            background-color: #3490dc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pengingat Peminjaman Buku</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $peminjaman->anggota->nama_lengkap }}</strong>,</p>
            
            <p>Kami ingin mengingatkan bahwa buku yang Anda pinjam akan segera jatuh tempo.</p>
            
            <div class="book-info">
                <h3>Detail Peminjaman:</h3>
                <p><strong>Judul Buku:</strong> {{ $peminjaman->buku->judul }}</p>
                <p><strong>Penulis:</strong> {{ $peminjaman->buku->penulis }}</p>
                <p><strong>Tanggal Peminjaman:</strong> {{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</p>
                <p><strong>Tanggal Jatuh Tempo:</strong> {{ $peminjaman->tanggal_jatuh_tempo->format('d/m/Y') }}</p>
                <p><strong>Sisa Waktu:</strong> 
                    @if($peminjaman->tanggal_jatuh_tempo->isPast())
                        <span style="color: red;">Terlambat {{ (int)$peminjaman->tanggal_jatuh_tempo->diffInDays(now()) }} hari</span>
                    @else
                        {{ (int)now()->diffInDays($peminjaman->tanggal_jatuh_tempo) }} hari lagi
                    @endif
                </p>
            </div>
            
            <p>Mohon untuk segera mengembalikan buku tersebut sebelum tanggal jatuh tempo untuk menghindari denda keterlambatan.</p>
            
            <p>Jika Anda ingin memperpanjang masa peminjaman, silakan login ke akun Anda dan gunakan fitur perpanjangan atau kunjungi perpustakaan kami.</p>
            
            <p>Terima kasih atas perhatiannya.</p>
            
            <p>Salam,<br>
            Tim Perpustakaan</p>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon untuk tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html> 