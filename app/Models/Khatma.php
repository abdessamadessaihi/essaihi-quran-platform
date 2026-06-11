<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Khatma extends Model
{
    use HasFactory;

    // ─── Type & Status Constants ──────────────────────────
    const TYPE_PLATFORM   = 'platform';
    const TYPE_FAMILY     = 'family';
    const TYPE_INDIVIDUAL = 'individual';
    const TYPE_RAMADAN    = 'ramadan';
    const TYPE_WEEKLY     = 'weekly';
    const TYPE_MONTHLY    = 'monthly';

    const STATUS_DRAFT     = 'draft';
    const STATUS_ACTIVE    = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'title',
        'type',
        'status',
        'created_by',
        'family_id',
        'starts_at',
        'ends_at',
        'auto_distribute',
        'completed_juz_count',
    ];

    protected function casts(): array
    {
        return [
            'starts_at'        => 'date',
            'ends_at'          => 'date',
            'auto_distribute'  => 'boolean',
        ];
    }

    // ─── Relations ────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    /** جميع أجزاء الختمة الـ 30 */
    public function juzAllocations(): HasMany
    {
        return $this->hasMany(JuzAllocation::class)->orderBy('juz_number');
    }

    /** الأجزاء المتاحة للحجز */
    public function availableJuz(): HasMany
    {
        return $this->juzAllocations()
                    ->where('status', 'available');
    }

    /** الأجزاء المكتملة */
    public function completedJuz(): HasMany
    {
        return $this->juzAllocations()
                    ->where('status', 'completed');
    }

    // ─── Computed Attributes ──────────────────────────────

    /** نسبة الإنجاز */
    public function getCompletionPercentageAttribute(): float
    {
        return round(($this->completed_juz_count / 30) * 100, 1);
    }

    /** هل اكتملت الختمة؟ */
    public function getIsCompleteAttribute(): bool
    {
        return $this->completed_juz_count >= 30;
    }

    // ─── Helpers ──────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function incrementCompletedJuz(): void
    {
        $this->increment('completed_juz_count');

        if ($this->completed_juz_count >= 30) {
            $this->update(['status' => self::STATUS_COMPLETED]);
        }
    }
}