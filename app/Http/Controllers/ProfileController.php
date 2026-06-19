<?php

namespace App\Http\Controllers;

use App\Models\DailyWard;
use App\Models\Memorization;
use App\Models\Streak;
use App\Models\XpTransaction;
use App\Models\UserBadge;
use App\Models\User; // تم استيراد موديل المستخدم لتوثيق الكود
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $streak = Streak::firstOrCreate(['user_id' => $user->id]);

        // حساب إحصائيات الحفظ مباشرة من قاعدة البيانات بدلاً من الذاكرة
        $totalAyahs = Memorization::where('user_id', $user->id)
            ->select(DB::raw('SUM(ayah_to - ayah_from + 1) as total'))
            ->value('total') ?? 0;

        $stats = [
            'total_wards'    => DailyWard::where('user_id', $user->id)->where('is_completed', true)->count(),
            'total_ayahs'    => $totalAyahs,
            'total_xp'       => (int) XpTransaction::where('user_id', $user->id)->sum('points'),
            'badges_count'   => UserBadge::where('user_id', $user->id)->count(),
            'current_streak' => $streak->current_streak,
            'longest_streak' => $streak->longest_streak,
            'member_since'   => $user->created_at->locale('ar')->isoFormat('MMMM YYYY'),
        ];

        // الآن سيتعرف المحرر على العلاقتين بدون أخطاء وهمية
        $badges   = $user->badges()->take(6)->get();
        $families = $user->families()->take(3)->get();

        return view('profile.show', compact('user', 'stats', 'badges', 'families'));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // دمج التحقق من الصورة مع التحقق الرئيسي لمنع الثغرات
        $validated = $request->validate([
            'name'   => 'required|string|max:120',
            'phone'  => 'nullable|string|max:20',
            'locale' => 'nullable|in:ar,fr,en',
            'avatar' => 'nullable|image|max:2048', 
        ]);

        // التعامل مع رفع الصورة الشخصية وحذف القديمة
        if ($request->hasFile('avatar')) {
            if ($user->avatar_url && $user->avatar_url !== 'images/user.png') {
                $oldPath = str_replace('storage/', '', $user->avatar_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar_url'] = 'storage/' . $path;
        }

        // الآن سيتعرف المحرر على دالة التحديث بنجاح
        $user->update($validated);

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح ✅');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        // تم استبدال المحاذاة الغامضة بـ $user مباشرة لحل تنبيه update الأخير
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح 🔐');
    }
}