# Sistem Manajemen Perpustakaan

Sistem Manajemen Perpustakaan adalah aplikasi berbasis web yang dikembangkan menggunakan framework Laravel untuk mengelola operasional perpustakaan secara efisien. Sistem ini dirancang untuk memudahkan pengelolaan katalog buku, anggota perpustakaan, proses peminjaman dan pengembalian, serta manajemen denda dan inventaris.

## Fitur Utama

- Manajemen katalog buku dan pencarian
- Manajemen anggota perpustakaan
- Sirkulasi (peminjaman dan pengembalian)
- Manajemen denda keterlambatan
- Manajemen inventaris buku
- Sistem keamanan dengan multi-level akses
- Backup dan restore database

## Teknologi yang Digunakan

- **Backend**: PHP 8.1 dengan Laravel 10
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Database**: MySQL
- **Server**: Apache
- **Autentikasi**: Laravel Authentication

## Persyaratan Sistem

- PHP 8.1 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Composer
- Web Server (Apache/Nginx)
- Ekstensi PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Instalasi

1. Clone repositori dari GitHub:
   ```
   git clone [URL_REPOSITORI]
   ```

2. Pindah ke direktori proyek:
   ```
   cd management-library
   ```

3. Instal dependensi dengan Composer:
   ```
   composer install
   ```

4. Salin file .env.example menjadi .env:
   ```
   cp .env.example .env
   ```

5. Generate application key:
   ```
   php artisan key:generate
   ```

6. Konfigurasi database di file .env:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=username
   DB_PASSWORD=password
   ```

7. Jalankan migrasi dan seeder:
   ```
   php artisan migrate --seed
   ```

8. Jalankan server development:
   ```
   php artisan serve
   ```

## Struktur Database

Sistem menggunakan beberapa tabel utama dengan prefix "faris_" sesuai ketentuan:

### Tabel Utama
1. **faris_kategori**: Menyimpan kategori buku
2. **faris_buku**: Menyimpan informasi buku
3. **faris_anggota**: Menyimpan data anggota perpustakaan
4. **faris_peminjaman**: Menyimpan data peminjaman buku
5. **faris_denda**: Menyimpan data denda keterlambatan
6. **faris_inventaris_buku**: Menyimpan data inventaris buku
7. **activity_logs**: Menyimpan log aktivitas pengguna

### Relasi Antar Tabel
- **faris_buku** memiliki relasi one-to-many dengan **faris_peminjaman**
- **faris_anggota** memiliki relasi one-to-many dengan **faris_peminjaman**
- **faris_peminjaman** memiliki relasi one-to-one dengan **faris_denda**
- **faris_buku** memiliki relasi one-to-many dengan **faris_inventaris_buku**
- **faris_buku** memiliki relasi many-to-one dengan **faris_kategori**
- **users** memiliki relasi one-to-one dengan **faris_anggota**

## Akun Default

Sistem ini memiliki beberapa akun default yang dapat digunakan untuk login:

### Admin
- Email: admin@example.com
- Password: password

### Staff
- Email: staff@example.com
- Password: password

### Anggota
- Email: anggota@example.com
- Password: password

## Peran dan Hak Akses

### Admin

Admin memiliki akses penuh ke seluruh fitur sistem, termasuk:

#### Manajemen Katalog
- Menambah, mengedit, dan menghapus kategori buku
- Menambah, mengedit, dan menghapus data buku
- Mencari buku berdasarkan berbagai kriteria
- Melihat detail buku

#### Manajemen Anggota
- Menambah, mengedit, dan menghapus data anggota
- Mengubah status anggota (aktif, nonaktif, diblokir)
- Memperpanjang masa keanggotaan
- Mereset password anggota
- Melihat detail dan riwayat peminjaman anggota

#### Manajemen Peminjaman
- Mencatat peminjaman buku baru
- Mencatat pengembalian buku
- Memperpanjang masa peminjaman
- Melihat daftar peminjaman aktif, terlambat, dan selesai

#### Manajemen Denda
- Menghitung denda keterlambatan secara otomatis
- Mencatat pembayaran denda
- Melihat daftar denda belum dibayar dan sudah dibayar
- Membuat laporan denda

#### Manajemen Inventaris
- Menambah, mengedit, dan menghapus data inventaris buku
- Menandai buku yang perlu perbaikan
- Mengelola proses perbaikan buku
- Membuat laporan inventaris

#### Keamanan dan Backup
- Melihat log aktivitas pengguna
- Membuat backup database
- Mengembalikan database dari backup
- Mengelola akun pengguna

### Staff

Staff memiliki akses terbatas dibandingkan admin, termasuk:

#### Manajemen Katalog
- Menambah dan mengedit data buku (tidak dapat menghapus)
- Mencari buku berdasarkan berbagai kriteria
- Melihat detail buku

#### Manajemen Anggota
- Menambah dan mengedit data anggota (tidak dapat menghapus)
- Mengubah status anggota (aktif, nonaktif, diblokir)
- Memperpanjang masa keanggotaan
- Mereset password anggota
- Melihat detail dan riwayat peminjaman anggota

#### Manajemen Peminjaman
- Mencatat peminjaman buku baru
- Mencatat pengembalian buku
- Memperpanjang masa peminjaman
- Melihat daftar peminjaman aktif, terlambat, dan selesai

#### Manajemen Denda
- Melihat daftar denda belum dibayar dan sudah dibayar
- Mencatat pembayaran denda
- Membuat laporan denda

#### Manajemen Inventaris
- Tidak memiliki akses ke fitur inventaris

#### Keamanan dan Backup
- Tidak memiliki akses ke log aktivitas dan fitur backup/restore

### Anggota

Anggota memiliki akses terbatas yang berfokus pada layanan perpustakaan, termasuk:

#### Katalog Buku
- Mencari buku berdasarkan berbagai kriteria
- Melihat detail buku
- Melihat ketersediaan buku

#### Peminjaman
- Melihat daftar peminjaman aktif
- Melihat riwayat peminjaman
- Memperpanjang masa peminjaman (jika memenuhi syarat)

#### Denda
- Melihat daftar denda yang belum dibayar
- Melihat riwayat pembayaran denda

#### Profil
- Melihat dan mengedit data profil
- Mengubah password

## Fitur Tambahan

### Notifikasi
- Sistem mengirimkan notifikasi untuk peminjaman yang akan jatuh tempo
- Notifikasi untuk denda yang belum dibayar

### Perhitungan Denda Otomatis
- Sistem secara otomatis menghitung denda keterlambatan berdasarkan hari keterlambatan

### Backup Otomatis
- Sistem melakukan backup database secara otomatis setiap hari

## Command Artisan Khusus

Sistem ini memiliki beberapa command artisan khusus untuk memudahkan pengelolaan:

- `php artisan hitung:denda` - Menghitung denda keterlambatan secara otomatis
- `php artisan kirim:notifikasi-jatuh-tempo` - Mengirim notifikasi untuk peminjaman yang akan jatuh tempo
- `php artisan backup:database` - Membuat backup database

## Alur Kerja Utama

### Proses Peminjaman Buku
1. Anggota mencari buku di katalog
2. Anggota memilih buku yang ingin dipinjam
3. Staff/Admin memverifikasi ketersediaan buku
4. Staff/Admin mencatat peminjaman buku
5. Sistem menetapkan tanggal jatuh tempo pengembalian

### Proses Pengembalian Buku
1. Anggota mengembalikan buku ke perpustakaan
2. Staff/Admin mencatat pengembalian buku
3. Sistem memeriksa keterlambatan
4. Jika terlambat, sistem menghitung denda
5. Staff/Admin mencatat pembayaran denda (jika ada)

### Proses Perpanjangan Keanggotaan
1. Anggota meminta perpanjangan keanggotaan
2. Staff/Admin memverifikasi status anggota
3. Staff/Admin memperpanjang masa keanggotaan
4. Sistem memperbarui tanggal kedaluwarsa keanggotaan

## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, silakan ikuti langkah-langkah berikut:

1. Fork repositori
2. Buat branch fitur baru (`git checkout -b feature/fitur-baru`)
3. Commit perubahan Anda (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin feature/fitur-baru`)
5. Buat Pull Request

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## Kontak

Jika Anda memiliki pertanyaan atau masalah, silakan hubungi kami di [email@example.com].
