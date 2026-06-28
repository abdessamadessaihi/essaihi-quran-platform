<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ResetPasswordController extends Controller implements HasMiddleware
{
    use ResetsPasswords;

    protected $redirectTo = '/dashboard'; // التوجيه للـ dashboard بعد تغيير الباسورد

    public static function middleware(): array
    {
        return [
            new Middleware('guest'),
        ];
    }
}