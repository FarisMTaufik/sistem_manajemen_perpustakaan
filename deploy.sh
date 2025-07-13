#!/bin/bash

# Script untuk mempersiapkan aplikasi Laravel untuk deployment ke Render

echo "Mempersiapkan aplikasi untuk deployment ke Render..."

# Mengoptimalkan autoload
echo "Mengoptimalkan autoload..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Mengoptimalkan konfigurasi
echo "Mengoptimalkan konfigurasi..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Membuat key aplikasi jika belum ada
if [ -z "$APP_KEY" ]; then
    echo "Membuat APP_KEY baru..."
    php artisan key:generate --show
fi

echo "Aplikasi siap untuk deployment ke Render!"
echo "Pastikan untuk mengatur variabel lingkungan berikut di dashboard Render:"
echo "- APP_KEY (gunakan nilai yang dihasilkan oleh key:generate --show)"
echo "- DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD (sesuai dengan database MySQL Anda)"

echo "Setelah deployment berhasil, jalankan migrasi database dengan:"
echo "php artisan migrate --force" 