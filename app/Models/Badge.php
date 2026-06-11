<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    public $timestamps = false;

    const CATEGORY_READING      = 'reading';
    const CATEGORY_MEMORIZATION = 'memorization';
    const CATEGORY_STREAK       = 'streak';
    const CATEGORY_KHATMA       = 'khatma';
    const CATEGORY_SOCIAL       = 'social';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon_url',
        'category',
        'criteria',
        'xp_reward',
    ];

    protected function casts(): array
    {
        return [
            'criteria'   => 'array',
            'created_at' => 'datetime',
        ];
    }

    // ─── Relations ────────────────────────────────────────

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
                    ->withPivot('earned_at');
    }
}