# Panduan Deployment ke Render

Dokumen ini berisi langkah-langkah untuk men-deploy aplikasi Management Library ke platform Render.

## Persiapan File

Beberapa file telah disiapkan untuk memudahkan proses deployment:

1. `Procfile` - Menentukan command yang akan dijalankan oleh Render
2. `render.yaml` - Konfigurasi untuk Render Blueprint
3. `.htaccess` - Konfigurasi Apache untuk root direktori
4. `deploy.sh` - Script untuk mempersiapkan aplikasi sebelum deployment

## Langkah-langkah Deployment

### 1. Persiapan Lokal

Sebelum melakukan deployment, jalankan script persiapan:

```bash
./deploy.sh
```

Script ini akan:
- Mengoptimalkan autoload
- Meng-cache konfigurasi dan route
- Menghasilkan APP_KEY baru jika belum ada

### 2. Setup Database MySQL di Render

1. Login ke dashboard Render (https://dashboard.render.com/)
2. Pilih "New" > "MySQL"
3. Isi informasi database:
   - Name: management-library-mysql
   - Database: management_library
   - User: pilih username atau biarkan default
   - Region: pilih region terdekat
   - Plan: Free atau sesuai kebutuhan
4. Klik "Create Database"
5. Catat informasi koneksi database (host, port, username, password)

### 3. Deploy Aplikasi Laravel

#### Opsi 1: Menggunakan Blueprint (render.yaml)

1. Di dashboard Render, pilih "New" > "Blueprint"
2. Hubungkan dengan repositori GitHub Anda
3. Render akan otomatis membaca file `render.yaml` dan membuat semua service yang diperlukan
4. Sesuaikan konfigurasi jika diperlukan
5. Klik "Apply"

#### Opsi 2: Manual Setup

1. Di dashboard Render, pilih "New" > "Web Service"
2. Pilih opsi "Build and deploy from a Git repository"
3. Hubungkan dengan repositori GitHub Anda
4. Isi informasi web service:
   - Name: management-library
   - Environment: PHP
   - Region: pilih region terdekat (sebaiknya sama dengan database)
   - Branch: main/master
   - Build Command: `composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev && php artisan config:cache && php artisan route:cache`
   - Start Command: `vendor/bin/heroku-php-apache2 public/`
5. Di bagian "Environment Variables", tambahkan variabel berikut:
   - `APP_ENV`: production
   - `APP_DEBUG`: false
   - `APP_KEY`: hasil dari `php artisan key:generate --show`
   - `APP_URL`: URL dari web service Anda (contoh: https://management-library.onrender.com)
   - `DB_CONNECTION`: mysql
   - `DB_HOST`: host dari database MySQL Anda
   - `DB_PORT`: 3306
   - `DB_DATABASE`: management_library
   - `DB_USERNAME`: username database Anda
   - `DB_PASSWORD`: password database Anda
6. Klik "Create Web Service"

### 4. Migrasi Database

Setelah aplikasi berhasil di-deploy:

1. Akses shell aplikasi dari dashboard Render
2. Jalankan migrasi database:
   ```
   php artisan migrate --seed --force
   ```

## Troubleshooting

### 1. Error 500 setelah deployment

Periksa log aplikasi di dashboard Render. Beberapa penyebab umum:
- APP_KEY tidak diatur
- Konfigurasi database salah
- Migrasi database belum dijalankan

### 2. Aplikasi lambat saat pertama kali diakses

Ini normal untuk plan gratis di Render. Service akan "sleep" setelah tidak aktif dan membutuhkan waktu untuk "spin up" kembali.

### 3. Error koneksi database

Pastikan:
- Kredensial database benar
- Database dapat diakses dari web service (biasanya tidak masalah di Render)
- Firewall tidak memblokir koneksi

## Catatan Penting

1. Plan gratis di Render memiliki batasan:
   - Service akan sleep setelah tidak aktif
   - Bandwidth dan resource terbatas
   - Database MySQL gratis memiliki batasan penyimpanan 1GB

2. Untuk produksi, pertimbangkan untuk upgrade ke plan berbayar.

3. Backup database secara berkala menggunakan fitur backup di Render atau command artisan.

4. Untuk file storage, sebaiknya gunakan layanan penyimpanan cloud seperti AWS S3 atau setup disk tambahan di Render. 