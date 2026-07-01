<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\PasswordResetOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ForgotPasswordOtpController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request, PasswordResetOtpService $otpService): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if ($user && ! $user->is_suspended) {
            if (! $otpService->send($user)) {
                return back()
                    ->withInput()
                    ->withErrors(['email' => __('app.auth.password_reset_send_failed')]);
            }
        }

        $request->session()->put('password_reset_email', $validated['email']);

        return redirect()
            ->route('password.verify')
            ->with('status', __('app.auth.password_reset_otp_sent'));
    }

    public function showVerify(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('password_reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.forgot-password-verify', [
            'email' => $request->session()->get('password_reset_email'),
        ]);
    }

    public function verifyOtp(Request $request, PasswordResetOtpService $otpService): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $email = (string) $request->session()->get('password_reset_email');

        if ($email === '') {
            return redirect()->route('password.request');
        }

        $user = User::query()->where('email', $email)->first();

        if (! $user || $user->is_suspended || ! $otpService->verify($user, $validated['code'])) {
            return back()->withErrors([
                'code' => $otpService->hasValidCode($user ?? new User)
                    ? __('app.auth.verify_otp_wrong')
                    : __('app.auth.verify_otp_expired'),
            ]);
        }

        $request->session()->put('password_reset_verified_user_id', $user->id);

        return redirect()->route('password.reset.form');
    }

    public function showResetForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('password_reset_verified_user_id')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password-otp');
    }

    public function resetPassword(Request $request, PasswordResetOtpService $otpService): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $userId = (int) $request->session()->get('password_reset_verified_user_id');

        if ($userId <= 0) {
            return redirect()->route('password.request');
        }

        $user = User::query()->find($userId);

        if (! $user || $user->is_suspended) {
            $request->session()->forget(['password_reset_email', 'password_reset_verified_user_id']);

            return redirect()
                ->route('login')
                ->withErrors(['email' => __('app.auth.account_suspended')]);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        $otpService->clear($user);

        $request->session()->forget(['password_reset_email', 'password_reset_verified_user_id']);

        return redirect()
            ->route('login')
            ->with('status', __('app.auth.password_reset_success'));
    }
}
