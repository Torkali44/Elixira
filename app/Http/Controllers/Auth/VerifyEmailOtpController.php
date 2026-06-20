<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailOtpRequest;
use App\Support\EmailVerificationOtpService;
use Illuminate\Http\RedirectResponse;

class VerifyEmailOtpController extends Controller
{
    public function store(VerifyEmailOtpRequest $request, EmailVerificationOtpService $otpService): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if (! $otpService->hasValidCode($user)) {
            return back()->withErrors([
                'code' => __('app.auth.verify_otp_expired'),
            ]);
        }

        if (! $otpService->verify($user, $request->validated('code'))) {
            return back()->withErrors([
                'code' => __('app.auth.verify_otp_wrong'),
            ]);
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
