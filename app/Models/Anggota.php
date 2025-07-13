<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggota extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'faris_anggota';

    /**
     * Atribut yang dapat diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nomor_anggota',
        'nama_lengkap',
        'jenis_kelamin',
        'alamat',
        'nomor_telepon',
        'email',
        'tanggal_lahir',
        'jenis_anggota',
        'status',
        'tanggal_bergabung',
        'tanggal_kedaluwarsa',
    ];

    /**
     * Atribut yang harus dikonversi menjadi tipe data.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_bergabung' => 'date',
        'tanggal_kedaluwarsa' => 'date',
    ];

    /**
     * Mendapatkan user yang terkait dengan anggota.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan semua peminjaman anggota.
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'faris_anggota_id');
    }
}
