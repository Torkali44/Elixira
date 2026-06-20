<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\EmailVerificationOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request, EmailVerificationOtpService $otpService): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            $otpService->send($request->user());
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', __('app.auth.verify_otp_send_failed'));
        }

        return back()->with('status', 'verification-otp-sent');
    }
}
