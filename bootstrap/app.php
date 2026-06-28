<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureSuperAdmin;
use App\Http\Middleware\EnsureFamilyAdmin;
use App\Http\Middleware\EnsureActiveMember;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ─── Named aliases فقط — لا شيء على web العام ────
        $middleware->alias([
            'super_admin'   => EnsureSuperAdmin::class,
            'family_admin'  => EnsureFamilyAdmin::class,
            'active_member' => EnsureActiveMember::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();