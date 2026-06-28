<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Streak;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:180', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'      => 'الاسم مطلوب',
            'name.max'           => 'الاسم طويل جداً',
            'email.required'     => 'البريد الإلكتروني مطلوب',
            'email.email'        => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique'       => 'هذا البريد مسجّل مسبقاً',
            'email.dns'      => 'هذا البريد الإلكتروني غير موجود',
            'password.required'  => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => User::ROLE_MEMBER,
            'is_active' => true,
            'locale'    => 'ar',
        ]);

        Streak::create([
            'user_id'           => $user->id,
            'current_streak'    => 0,
            'longest_streak'    => 0,
            'total_active_days' => 0,
        ]);

        event(new Registered($user));
        

        return redirect()->route('login')->with('status', 'تم إنشاء حسابك بنجاح! يرجى الانتقال إلى بريدك الإلكتروني والضغط على رابط التفعيل لتتمكن من تسجيل الدخول.');    }
}