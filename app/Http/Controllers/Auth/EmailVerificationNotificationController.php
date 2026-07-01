<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\EmailVerificationOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request, EmailVerificationOtpService $otpService): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if ($otpService->send($request->user())) {
            return back()->with('status', 'verification-otp-sent');
        }

        return back()->with('error', __('app.auth.verify_otp_send_failed'));
    }
}
