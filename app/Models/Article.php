<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    const CATEGORIES = [
        'tafsir'   => 'تفسير',
        'tadabbur' => 'تدبر',
        'fiqh'     => 'فقه',
        'seerah'   => 'سيرة',
        'general'  => 'عام',
    ];

    const CATEGORY_COLORS = [
        'tafsir'   => ['bg'=>'#ecfdf5','text'=>'#065f46','border'=>'#a7f3d0'],
        'tadabbur' => ['bg'=>'#eff6ff','text'=>'#1d4ed8','border'=>'#bfdbfe'],
        'fiqh'     => ['bg'=>'#fffbeb','text'=>'#92400e','border'=>'#fde68a'],
        'seerah'   => ['bg'=>'#fdf4ff','text'=>'#7e22ce','border'=>'#e9d5ff'],
        'general'  => ['bg'=>'#f1f5f9','text'=>'#475569','border'=>'#e2e8f0'],
    ];

    protected $fillable = [
        'user_id','title','slug','excerpt','content',
        'cover_url','category','status','views','likes',
    ];

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            $article->slug = Str::slug($article->title).'-'.Str::random(5);
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getCategoryColorAttribute(): array
    {
        return self::CATEGORY_COLORS[$this->category] ?? self::CATEGORY_COLORS['general'];
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
    public function comments()
{
    return $this->hasMany(Comment::class)->latest();
}
}