<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyMember extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'family_id',
        'status',
        'role',
        'approved_by',
        'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'joined_at'  => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    // ─── Status Constants ─────────────────────────────────
    const STATUS_PENDING   = 'pending';
    const STATUS_ACTIVE    = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_REJECTED  = 'rejected';

    // ─── Relations ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── Helpers ──────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function approve(int $adminId): void
    {
        $this->update([
            'status'      => self::STATUS_ACTIVE,
            'approved_by' => $adminId,
            'joined_at'   => now(),
        ]);
    }

    public function reject(): void
    {
        $this->update(['status' => self::STATUS_REJECTED]);
    }
}