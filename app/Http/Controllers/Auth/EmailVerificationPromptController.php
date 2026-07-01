<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\EmailVerificationOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    public function __invoke(Request $request, EmailVerificationOtpService $otpService): RedirectResponse|View
    {
        $user = $request->user();

        // Admins are always considered verified — skip verification prompt
        if ($user->role === 'admin') {
            return redirect()->intended(route('profile.edit', absolute: false));
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if (! $otpService->hasValidCode($user)) {
            if ($otpService->send($user)) {
                session()->flash('status', 'verification-otp-sent');
            } else {
                session()->flash('error', __('app.auth.verify_otp_send_failed'));
            }
        }

        return view('auth.verify-email');
    }
}
