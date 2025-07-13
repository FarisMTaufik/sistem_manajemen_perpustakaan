<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            [
                'nama_kategori' => 'Fiksi',
                'deskripsi' => 'Buku-buku fiksi termasuk novel, cerpen, dan karya sastra lainnya',
            ],
            [
                'nama_kategori' => 'Non-Fiksi',
                'deskripsi' => 'Buku-buku non-fiksi seperti biografi, sejarah, dan ilmu pengetahuan umum',
            ],
            [
                'nama_kategori' => 'Pendidikan',
                'deskripsi' => 'Buku-buku pendidikan dan buku teks untuk berbagai jenjang pendidikan',
            ],
            [
                'nama_kategori' => 'Teknologi',
                'deskripsi' => 'Buku-buku tentang teknologi, komputer, dan pemrograman',
            ],
            [
                'nama_kategori' => 'Seni dan Budaya',
                'deskripsi' => 'Buku-buku tentang seni, musik, film, dan budaya',
            ],
            [
                'nama_kategori' => 'Agama',
                'deskripsi' => 'Buku-buku tentang agama dan spiritualitas',
            ],
            [
                'nama_kategori' => 'Kesehatan',
                'deskripsi' => 'Buku-buku tentang kesehatan, medis, dan gaya hidup sehat',
            ],
            [
                'nama_kategori' => 'Bisnis dan Ekonomi',
                'deskripsi' => 'Buku-buku tentang bisnis, ekonomi, keuangan, dan manajemen',
            ],
            [
                'nama_kategori' => 'Referensi',
                'deskripsi' => 'Buku-buku referensi seperti kamus, ensiklopedia, dan atlas',
            ],
            [
                'nama_kategori' => 'Anak-anak',
                'deskripsi' => 'Buku-buku untuk anak-anak dan remaja',
            ],
        ];

        foreach ($kategori as $kat) {
            Kategori::create($kat);
        }
    }
} 