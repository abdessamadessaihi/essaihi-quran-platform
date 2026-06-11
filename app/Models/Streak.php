<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Streak extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'current_streak',
        'longest_streak',
        'last_active_date',
        'total_active_days',
    ];

    protected function casts(): array
    {
        return [
            'last_active_date' => 'date',
            'updated_at'       => 'datetime',
        ];
    }

    // ─── Relations ────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Core Streak Logic ────────────────────────────────

    /** تحديث الـ Streak بعد كل ورد مكتمل */
    public function recordActivity(): void
    {
        $today     = today();
        $yesterday = today()->subDay();

        if ($this->last_active_date?->equalTo($today)) {
            return; // سبق تسجيل هذا اليوم
        }

        if ($this->last_active_date?->equalTo($yesterday)) {
            // يوم متتالي — زيادة الـ Streak
            $this->current_streak++;
        } else {
            // انكسر التسلسل أو أول يوم
            $this->current_streak = 1;
        }

        if ($this->current_streak > $this->longest_streak) {
            $this->longest_streak = $this->current_streak;
        }

        $this->last_active_date = $today;
        $this->total_active_days++;
        $this->save();
    }

    /** إعادة تصفير الـ Streak إن فات يوم دون ورد */
    public function checkAndResetIfNeeded(): void
    {
        if (
            $this->last_active_date
            && $this->last_active_date->lessThan(today()->subDay())
        ) {
            $this->update(['current_streak' => 0]);
        }
    }
}