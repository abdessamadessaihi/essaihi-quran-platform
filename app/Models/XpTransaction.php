<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XpTransaction extends Model
{
    public $timestamps = false;

    const SOURCE_WARD      = 'ward';
    const SOURCE_JUZ       = 'juz';
    const SOURCE_REVISION  = 'revision';
    const SOURCE_BADGE     = 'badge';
    const SOURCE_KHATMA    = 'khatma';
    const SOURCE_MEMORIZATION = 'memorization';

    protected $fillable = [
        'user_id',
        'points',
        'source_type',
        'source_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    // ─── Relations ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Factory Helper ───────────────────────────────────

    public static function award(
        int $userId,
        int $points,
        string $sourceType,
        ?int $sourceId = null,
        ?string $description = null
    ): self {
        return self::create([
            'user_id'     => $userId,
            'points'      => $points,
            'source_type' => $sourceType,
            'source_id'   => $sourceId,
            'description' => $description,
        ]);
    }
}