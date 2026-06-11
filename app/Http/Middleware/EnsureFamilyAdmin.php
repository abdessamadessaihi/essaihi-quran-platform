<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFamilyAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->isSuperAdmin() && ! $user?->isFamilyAdmin()) {
            abort(403, 'هذه الصفحة مخصصة لمسؤول العائلة.');
        }

        return $next($request);
    }
}