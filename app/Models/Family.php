<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_url',
        'cover_url',
        'created_by',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ─── Auto-generate slug ───────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Family $family) {
            if (empty($family->slug)) {
                $family->slug = Str::slug($family->name) . '-' . Str::random(5);
            }
        });
    }

    // ─── Relations ────────────────────────────────────────

    /** منشئ العائلة */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** سجلات الأعضاء */
    public function memberships(): HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }

    /** الأعضاء النشطون فقط */
    public function activeMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'family_members')
                    ->withPivot(['role', 'joined_at'])
                    ->wherePivot('status', 'active');
    }

    /** الأعضاء في انتظار الموافقة */
    public function pendingMembers(): HasMany
    {
        return $this->memberships()->where('status', 'pending');
    }

    /** ختمات العائلة */
    public function khatmas(): HasMany
    {
        return $this->hasMany(Khatma::class);
    }

    /** الختمات النشطة */
    public function activeKhatmas(): HasMany
    {
        return $this->khatmas()->where('status', 'active');
    }

    /** ترتيبات لوحة الشرف على مستوى العائلة */
    public function leaderboardEntries(): HasMany
    {
        return $this->hasMany(LeaderboardEntry::class);
    }

    // ─── Computed Attributes ──────────────────────────────

    /** عدد الأعضاء النشطين */
    public function getActiveMembersCountAttribute(): int
    {
        return $this->memberships()->where('status', 'active')->count();
    }

    /** عدد الختمات المكتملة */
    public function getCompletedKhatmasCountAttribute(): int
    {
        return $this->khatmas()->where('status', 'completed')->count();
    }
}