<?php

namespace App\Policies;

use App\Models\QuranClass;
use App\Models\User;

class QuranClassPolicy
{
    // المشاهدة: يسمح فقط للمحفظ الخاص بالحلقة أو الطلاب المسجلين فيها أو الآدمين
    public function view(User $user, QuranClass $quranClass): bool
    {
        if ($user->isAdmin()) return true;
        
        if ($user->isMohafid()) {
            return $user->id === $quranClass->mohafid_id;
        }
        
        return $quranClass->students()->where('student_id', $user->id)->exists();
    }

    // التعديل (رابط الـ Meet والدروس): مسموح فقط للمحفظ صاحب الحلقة أو الآدمين
    public function update(User $user, QuranClass $quranClass): bool
    {
        return $user->isAdmin() || ($user->isMohafid() && $user->id === $quranClass->mohafid_id);
    }
}