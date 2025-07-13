<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'faris_peminjaman';

    /**
     * Atribut yang dapat diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'faris_anggota_id',
        'faris_buku_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'status',
        'perpanjangan_count',
    ];

    /**
     * Atribut yang harus dikonversi menjadi tipe data.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_kembali' => 'date',
        'perpanjangan_count' => 'integer',
    ];

    /**
     * Mendapatkan anggota yang meminjam.
     */
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'faris_anggota_id');
    }

    /**
     * Mendapatkan buku yang dipinjam.
     */
    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'faris_buku_id');
    }

    /**
     * Mendapatkan denda terkait peminjaman ini.
     */
    public function denda(): HasOne
    {
        return $this->hasOne(Denda::class, 'faris_peminjaman_id');
    }

    /**
     * Memeriksa apakah peminjaman terlambat.
     */
    public function isTerlambat(): bool
    {
        if ($this->status === 'dikembalikan') {
            return false;
        }

        return now()->greaterThan($this->tanggal_jatuh_tempo);
    }

    /**
     * Menghitung jumlah hari keterlambatan.
     */
    public function hitungHariTerlambat(): int
    {
        if (!$this->isTerlambat()) {
            return 0;
        }

        $tanggalKembali = $this->tanggal_kembali ?? now();
        $tanggalJatuhTempo = $this->tanggal_jatuh_tempo;
        
        // Hitung selisih hari dan pastikan minimal 1 hari jika terlambat
        return max(1, $tanggalJatuhTempo->diffInDays($tanggalKembali));
    }
}
