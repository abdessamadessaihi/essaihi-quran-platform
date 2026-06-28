<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChildAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.child-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'pin_code' => 'required|digits:4',
        ]);

        // البحث عن الطفل بناءً على اسم المستخدم المبسط
        $child = User::where('username', $credentials['username'])
                     ->whereNotNull('parent_id')
                     ->first();

        // التحقق من صحة الحساب والـ PIN المشفر
        if (!$child || !Hash::check($credentials['pin_code'], $child->pin_code)) {
            return back()->withErrors(['username' => 'اسم المستخدم أو رمز PIN غير صحيح.'])->withInput();
        }

        if (!$child->is_active) {
            return back()->withErrors(['username' => 'هذا الحساب معطل حالياً.']);
        }

        // تسجيل الدخول الفعلي للطفل بالمنصة
        Auth::login($child, $request->filled('remember'));

        return redirect()->route('dashboard')->with('success', "مرحباً بك يا بطل 🌟 جاري الانتقال للوحة التحكم");
    }
}