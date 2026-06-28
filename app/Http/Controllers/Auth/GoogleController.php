<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Mail\WelcomeFirstLoginMail;
use Illuminate\Support\Facades\Mail;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // 🌟 التصحيح: التوجيه العادي بدون دالة stateless هنا
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // 🌟 هنا فقط نستخدم stateless لجلب بيانات المستخدم دون تعارض الجلسات
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // 1. البحث عن المستخدم بالبريد
            $user = User::where('email', $googleUser->getEmail())->first();
            $isNewUser = false;

            if (!$user) {
                $isNewUser = true;
                
                $user = new User();
                $user->forceFill([
                    'name'                 => $googleUser->getName(),
                    'email'                => $googleUser->getEmail(),
                    'google_id'            => $googleUser->getId(),
                    'password'             => Hash::make(Str::random(16)),
                    'email_verified_at'    => now(), 
                    'has_logged_in_before' => true,
                ])->saveQuietly(); 
                
            } else {
                // إذا كان الحساب موجوداً مسبقاً، نضمن توثيقه
                $user->google_id = $googleUser->getId();
                $user->has_logged_in_before = true;
                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                }
                $user->saveQuietly();
            }

            // 2. تسجيل الدخول وتثبيت الجلسة بشكل دائم (Remember Me = true)
            Auth::guard('web')->login($user, true);
            
            // تثبيت الكوكيز في المتصفح حالاً
            request()->session()->put('auth.password_confirmed_at', time());
            request()->session()->save();

            // 3. إرسال البريد الترحيبي للمستخدم الجديد في الخلفية
            if ($isNewUser) {
                Mail::to($user->email)->send(new WelcomeFirstLoginMail($user));
            }

            // 4. التوجيه النهائي للـ Dashboard
            return redirect()->to('/dashboard');

        } catch (\Exception $e) {
            dd('خطأ في الاتصال بجوجل: ' . $e->getMessage());
        }
    }
}