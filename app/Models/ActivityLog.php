<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_role',
        'user_name',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
        'properties',
    ];

    /**
     * Atribut yang harus dikonversi menjadi tipe data.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Mendapatkan user yang melakukan aktivitas.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Membuat log aktivitas baru.
     *
     * @param string $action
     * @param string $module
     * @param string|null $description
     * @param array|null $properties
     * @return ActivityLog
     */
    public static function log(string $action, string $module, ?string $description = null, ?array $properties = null): self
    {
        $user = auth()->user();
        
        return self::create([
            'user_id' => $user?->id,
            'user_role' => $user?->role ?? 'system',
            'user_name' => $user?->name ?? 'System',
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip() ?? '0.0.0.0',
            'user_agent' => request()->userAgent() ?? 'System',
            'properties' => $properties,
        ]);
    }
}
