<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    public $timestamps = false;

    const CHANNEL_DATABASE = 'database';
    const CHANNEL_EMAIL    = 'email';
    const CHANNEL_PUSH     = 'push';

    protected $fillable = [
        'user_id',
        'type',
        'data',
        'channel',
        'is_read',
        'read_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'data'       => 'array',
            'is_read'    => 'boolean',
            'read_at'    => 'datetime',
            'sent_at'    => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    // ─── Relations ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ──────────────────────────────────────────

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}