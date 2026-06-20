<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ApplyUserPreferences;
use App\Http\Middleware\EnsureVerifiedOrAdmin;
use App\Http\Middleware\VendorMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            ApplyUserPreferences::class,
        ]);
        $middleware->alias([
            'admin'              => AdminMiddleware::class,
            'vendor'             => VendorMiddleware::class,
            'verified.or.admin'  => EnsureVerifiedOrAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (HttpExceptionInterface $exception, Request $request) {
            if ($exception->getStatusCode() !== 419) {
                return null;
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => __('app.session_expired')], 419);
            }

            $redirect = match (true) {
                $request->is('register') => redirect()->route('register'),
                $request->is('login') => redirect()->route('login'),
                default => redirect()->back(),
            };

            return $redirect
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', __('app.session_expired'));
        });
    })->create();
