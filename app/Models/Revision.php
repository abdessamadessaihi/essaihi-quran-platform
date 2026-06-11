<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Revision extends Model
{
    public $timestamps = false;

    const TYPE_DAILY   = 'daily';
    const TYPE_WEEKLY  = 'weekly';
    const TYPE_MONTHLY = 'monthly';

    const STATUS_PENDING   = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_SKIPPED   = 'skipped';
    const STATUS_OVERDUE   = 'overdue';

    protected $fillable = [
        'user_id',
        'memorization_id',
        'revision_type',
        'status',
        'scheduled_date',
        'completed_date',
        'score',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'completed_date' => 'date',
            'created_at'     => 'datetime',
        ];
    }

    // ─── Relations ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function memorization(): BelongsTo
    {
        return $this->belongsTo(Memorization::class);
    }

    // ─── Helpers ──────────────────────────────────────────

    public function complete(int $score, ?string $notes = null): void
    {
        $this->update([
            'status'         => self::STATUS_COMPLETED,
            'completed_date' => today(),
            'score'          => $score,
            'notes'          => $notes,
        ]);

        // تحديث تاريخ آخر مراجعة في سجل الحفظ
        $this->memorization->update([
            'last_reviewed_at' => today(),
            'review_score'     => $score,
        ]);
    }
}