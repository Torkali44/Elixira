<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user !== null && $user->is_suspended) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('app.auth.account_suspended'),
                ], 403);
            }

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => __('app.auth.account_suspended'),
                ]);
        }

        return $next($request);
    }
}
