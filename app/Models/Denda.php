<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'faris_denda';

    /**
     * Atribut yang dapat diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'faris_peminjaman_id',
        'jumlah_denda',
        'status_pembayaran',
        'tanggal_pembayaran',
    ];

    /**
     * Atribut yang harus dikonversi menjadi tipe data.
     *
     * @var array
     */
    protected $casts = [
        'jumlah_denda' => 'decimal:2',
        'tanggal_pembayaran' => 'date',
    ];

    /**
     * Mendapatkan peminjaman terkait denda ini.
     */
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'faris_peminjaman_id');
    }
}
