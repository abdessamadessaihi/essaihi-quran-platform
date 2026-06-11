<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JuzAllocation extends Model
{
    public $timestamps = false;

    // ─── Status Constants ─────────────────────────────────
    const STATUS_AVAILABLE = 'available';
    const STATUS_RESERVED  = 'reserved';
    const STATUS_READING   = 'reading';
    const STATUS_COMPLETED = 'completed';
    const STATUS_EXPIRED   = 'expired';

    // ─── Grid Color Map (للواجهة) ─────────────────────────
    const STATUS_COLORS = [
        'available' => '#94a3b8', // رمادي — غير محجوز
        'reserved'  => '#3b82f6', // أزرق  — محجوز
        'reading'   => '#f59e0b', // ذهبي  — قيد القراءة
        'completed' => '#059669', // أخضر  — مكتمل
        'expired'   => '#ef4444', // أحمر  — منتهي الصلاحية
    ];

    protected $fillable = [
        'khatma_id',
        'user_id',
        'juz_number',
        'status',
        'claimed_at',
        'started_at',
        'completed_at',
        'deadline_at',
    ];

    protected function casts(): array
    {
        return [
            'claimed_at'    => 'datetime',
            'started_at'    => 'datetime',
            'completed_at'  => 'datetime',
            'deadline_at'   => 'datetime',
            'created_at'    => 'datetime',
        ];
    }

    // ─── Relations ────────────────────────────────────────

    public function khatma(): BelongsTo
    {
        return $this->belongsTo(Khatma::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ──────────────────────────────────────────

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? '#94a3b8';
    }

    public function isExpired(): bool
    {
        return $this->deadline_at
            && now()->greaterThan($this->deadline_at)
            && $this->status !== self::STATUS_COMPLETED;
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status'       => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        // تحديث عداد الختمة
        $this->khatma->incrementCompletedJuz();
    }
}