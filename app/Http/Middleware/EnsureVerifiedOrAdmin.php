<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class EnsureVerifiedOrAdmin
{
    /**
     * Handle an incoming request.
     * Admins bypass email verification entirely.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Admins are always allowed — no email verification required
        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        // For all other users, enforce email verification
        if (! $user || ! $user->hasVerifiedEmail()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your email address is not verified.'], 403);
            }

            return Redirect::guest(URL::route('verification.notice'));
        }

        return $next($request);
    }
}
