<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ForgotPasswordController extends Controller implements HasMiddleware
{
    use SendsPasswordResetEmails;

    public static function middleware(): array
    {
        return [
            new Middleware('guest'),
        ];
    }
}