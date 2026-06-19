<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MushafUpload extends Model
{
    protected $fillable = [
        'user_id','title','file_url','file_name',
        'file_size','file_type','last_page','notes','is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes < 1024) return $bytes.' B';
        if ($bytes < 1048576) return round($bytes/1024,1).' KB';
        return round($bytes/1048576,1).' MB';
    }
}