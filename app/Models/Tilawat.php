<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tilawat extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'reciter_name',
        'surah_name',
        'media_type',
        'media_url',
        'cover_image',
        'views_count',
        'is_featured'
    ];
}