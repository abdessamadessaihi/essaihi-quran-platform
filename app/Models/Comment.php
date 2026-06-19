<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'article_id', 'content'];

    // التعليق ينتمي لمستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // التعليق ينتمي لمقال
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}