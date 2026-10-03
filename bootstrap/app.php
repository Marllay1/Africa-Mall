<?php

use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Middleware\EnsureSellerActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        then: function (): void {
            Route::middleware('web')->group(function (): void {
                require __DIR__.'/../routes/seller.php';
                require __DIR__.'/../routes/admin.php';
            });
        },
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'seller.active' => EnsureSellerActive::class,
            'admin' => EnsureIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

// Vercel's function filesystem is read-only except /tmp. Session/cache/queue already
// run on the database driver, so compiled Blade views are the only runtime write this
// app needs to redirect (logs go to LOG_CHANNEL=stderr instead, via env, no code change).
if (getenv('VERCEL')) {
    $app->useStoragePath('/tmp/storage');

    foreach (['framework/views', 'framework/cache/data', 'framework/sessions', 'framework/testing', 'logs'] as $dir) {
        @mkdir($app->storagePath($dir), 0755, recursive: true);
    }
}

return $app;
