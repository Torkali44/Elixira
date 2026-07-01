<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use App\Models\VendorProfile;
use App\Support\EmailVerificationOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(RegisterUserRequest $request, EmailVerificationOtpService $otpService): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => ($request->phone_country_code ?? '+966').ltrim((string) $request->phone, '0'),
            'avatar' => $request->hasFile('avatar')
                ? $request->file('avatar')->store('users/avatars', 'public')
                : null,
            'password' => Hash::make($request->password),
        ]);

        if ($request->account_type === 'vendor') {
            VendorProfile::create([
                'user_id' => $user->id,
                'brand_name' => $request->brand_name,
                'status' => 'draft',
                'payment_method' => 'cash_on_delivery',
            ]);
        }

        Auth::login($user);

        $redirect = redirect()->route('verification.notice');

        if ($otpService->send($user)) {
            return $redirect->with('status', 'verification-otp-sent');
        }

        return $redirect->with('error', __('app.auth.verify_otp_send_failed'));
    }
}
