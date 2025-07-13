<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'faris_buku';

    /**
     * Atribut yang dapat diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'faris_kategori_id',
        'jumlah_salinan',
        'jumlah_tersedia',
        'kondisi',
        'deskripsi',
        'gambar_sampul',
        'tanggal_inventaris',
        'catatan_inventaris',
        'status_inventaris',
    ];

    /**
     * Atribut yang harus dikonversi menjadi tipe data.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_inventaris' => 'date',
    ];

    /**
     * Mendapatkan kategori dari buku.
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'faris_kategori_id');
    }

    /**
     * Mendapatkan peminjaman untuk buku ini.
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'faris_buku_id');
    }
    
    /**
     * Mendapatkan riwayat inventaris untuk buku ini.
     */
    public function inventaris(): HasMany
    {
        return $this->hasMany(InventarisBuku::class, 'faris_buku_id');
    }
}
