<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LoginController extends Controller implements HasMiddleware
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * إعداد الـ Middleware بالطريقة الحديثة المتوافقة مع إصدار لارافيل الحالي.
     *
     * @return array
     */
    public static function middleware(): array
    {
        return [
            // تطبيق 'guest' على جميع العمليات (مثل عرض صفحة الدخول) ما عدا تسجيل الخروج
            new Middleware('guest', except: ['logout']), 
            
            // تطبيق 'auth' على عملية تسجيل الخروج فقط لمنع الزوار من طلبها
            new Middleware('auth', only: ['logout']),
        ];
    }

}