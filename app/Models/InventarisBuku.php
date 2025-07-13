<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarisBuku extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'faris_inventaris_buku';

    /**
     * Atribut yang dapat diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'faris_buku_id',
        'tanggal_pemeriksaan',
        'kondisi',
        'status_inventaris',
        'lokasi_penyimpanan',
        'catatan',
        'petugas',
        'tanggal_perbaikan',
        'tanggal_selesai_perbaikan',
        'perlu_tindakan_lanjut',
    ];

    /**
     * Atribut yang harus dikonversi menjadi tipe data.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
        'tanggal_perbaikan' => 'date',
        'tanggal_selesai_perbaikan' => 'date',
        'perlu_tindakan_lanjut' => 'boolean',
    ];

    /**
     * Mendapatkan buku yang terkait dengan inventaris.
     */
    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'faris_buku_id');
    }
}
