<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\KhatmaController;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $request = Request::create('/khatmas', 'POST', [
        'title' => 'Test Khatma',
        'type' => 'family',
        'auto_distribute' => '1',
        'starts_at' => now()->format('Y-m-d'),
        'ends_at' => null
    ]);
    $user = User::first();
    $request->setUserResolver(function() use ($user) { return $user; });

    $controller = app(KhatmaController::class);
    $response = $controller->store($request);
    
    echo "Response class: " . get_class($response) . "\n";
    if ($response instanceof \Illuminate\Http\RedirectResponse) {
        echo "Redirected to: " . $response->getTargetUrl() . "\n";
        if ($response->getSession()->has('errors')) {
            echo "Errors: " . json_encode($response->getSession()->get('errors')->getBag('default')->getMessages()) . "\n";
        }
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
