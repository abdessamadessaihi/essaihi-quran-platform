<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomResetPasswordNotification; 
class User extends Authenticatable implements MustVerifyEmail

{
    use HasFactory, Notifiable;

    // ─── Roles ───────────────────────────────────────────
    const ROLE_SUPER_ADMIN  = 'super_admin';
    const ROLE_FAMILY_ADMIN = 'family_admin';
    const ROLE_MEMBER       = 'member';
    // الأدوار الجديدة الخاصة بصفحة الحصص والمقارئ
    const ROLE_MOHAFID      = 'mohafid';
    const ROLE_STUDENT      = 'student';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar_url',
        'role',
        'locale',
        'is_active',
        'mushaf_page',
        'parent_id',
        'username',
        'pin_code',
        'age',
        'google_id',           
        'has_logged_in_before',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ─── Role Helpers ─────────────────────────────────────
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isFamilyAdmin(): bool
    {
        return $this->role === self::ROLE_FAMILY_ADMIN;
    }
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isMember(): bool
    {
        return $this->role === self::ROLE_MEMBER;
    }

    public function isMohafid(): bool
    {
        return $this->role === self::ROLE_MOHAFID;
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    // ─── Relations ────────────────────────────────────────

    /** الفصول والقرى القرآنية التي يدرّسها المحفظ */
    public function managedClasses(): HasMany
    {
        return $this->hasMany(QuranClass::class, 'mohafid_id');
    }

    /** الفصول القرآنية التي يدرس بها الطالب */
    public function enrolledClasses(): BelongsToMany
    {
        return $this->belongsToMany(QuranClass::class, 'class_student', 'student_id', 'quran_class_id');
    }

    /** العائلات التي أنشأها هذا المستخدم */
    public function createdFamilies(): HasMany
    {
        return $this->hasMany(Family::class, 'created_by');
    }


    /** سجل عضوية المستخدم في العائلات */
    public function familyMemberships(): HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }

    /** الختمات التي أنشأها */
    public function createdKhatmas(): HasMany
    {
        return $this->hasMany(Khatma::class, 'created_by');
    }

    /** أجزاء القرآن المحجوزة عليه */
    public function juzAllocations(): HasMany
    {
        return $this->hasMany(JuzAllocation::class);
    }

    /** الأوراد اليومية */
    public function dailyWards(): HasMany
    {
        return $this->hasMany(DailyWard::class);
    }

    /** ورد اليوم الحالي */
    public function todayWard(): HasOne
    {
        return $this->hasOne(DailyWard::class)
                    ->whereDate('ward_date', today());
    }

    /** سجل الـ Streak */
    public function streak(): HasOne
    {
        return $this->hasOne(Streak::class);
    }

    /** سجلات الحفظ */
    public function memorizations(): HasMany
    {
        return $this->hasMany(Memorization::class);
    }

    /** جدول المراجعات */
    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class);
    }

    /** الشارات المكتسبة */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
                    ->withPivot('earned_at')
                    ->orderByPivot('earned_at', 'desc');
    }

    /** سجلات نقاط XP */
    public function xpTransactions(): HasMany
    {
        return $this->hasMany(XpTransaction::class);
    }

    /** إجمالي نقاط XP */
    public function getTotalXpAttribute(): int
    {
        return $this->xpTransactions()->sum('points');
    }

    /** ترتيبات لوحة الشرف */
    public function leaderboardEntries(): HasMany
    {
        return $this->hasMany(LeaderboardEntry::class);
    }

    /** الإشعارات */
    public function platformNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /** الإشارات المرجعية في المصحف */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /** آخر إشارة مرجعية */
    public function lastBookmark(): HasOne
    {
        return $this->hasOne(Bookmark::class)
                    ->latestOfMany();
    }
    public function families(): BelongsToMany
    {
        return $this->belongsToMany(Family::class, 'family_members', 'user_id', 'family_id')
                    ->withPivot(['role', 'status', 'approved_by', 'joined_at']);
    }

    /** العائلات النشطة فقط (للاستخدام في لوحات الإنجاز والأوراد) */
    public function activeFamilies(): BelongsToMany
    {
        return $this->families()->wherePivot('status', 'active');
    }
    public function isChild(): bool
{
    return !is_null($this->parent_id);
}

/** الأب أو ولي الأمر */
public function parent()
{
    return $this->belongsTo(User::class, 'parent_id');
}

/** قائمة الأطفال التابعين لولي الأمر */
public function children()
{
    return $this->hasMany(User::class, 'parent_id');
}

public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}