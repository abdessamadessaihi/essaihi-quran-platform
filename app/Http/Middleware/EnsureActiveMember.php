<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureActiveMember
{
    public function handle(Request $request, Closure $next): Response
    {
        // تجاهل غير المسجلين — auth middleware يتكفل بهم
        if (! $request->user()) {
            return $next($request);
        }

        if (! $request->user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'حسابك موقوف. تواصل مع مسؤول العائلة.']);
        }

        return $next($request);
    }
}