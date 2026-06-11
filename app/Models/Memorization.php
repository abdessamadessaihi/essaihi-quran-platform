<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Memorization extends Model
{
    const MASTERY_WEAK      = 'weak';
    const MASTERY_FAIR      = 'fair';
    const MASTERY_GOOD      = 'good';
    const MASTERY_EXCELLENT = 'excellent';

    protected $fillable = [
        'user_id',
        'surah_number',
        'ayah_from',
        'ayah_to',
        'mastery_level',
        'memorized_at',
        'last_reviewed_at',
        'review_score',
    ];

    protected function casts(): array
    {
        return [
            'memorized_at'    => 'date',
            'last_reviewed_at'=> 'date',
        ];
    }

    // ─── Relations ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class);
    }

    public function pendingRevisions(): HasMany
    {
        return $this->revisions()->where('status', 'pending');
    }

    // ─── Helpers ──────────────────────────────────────────

    /** عدد الآيات المحفوظة في هذا السجل */
    public function getAyahCountAttribute(): int
    {
        return ($this->ayah_to - $this->ayah_from) + 1;
    }
}