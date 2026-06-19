<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuranClass extends Model
{
    protected $fillable = ['mohafid_id', 'title', 'description', 'meet_url', 'courses_materials', 'schedule','resource_file'];
    protected $casts = [
    'resource_file' => 'array',
];

    // الحصول على المحفظ المسؤول عن الحصة
    public function mohafid()
    {
        return $this->belongsTo(User::class, 'mohafid_id');
    }

    // الطلاب المسجلين في هذه الحصة
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_student', 'quran_class_id', 'student_id');
    }
}