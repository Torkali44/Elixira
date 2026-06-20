<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(RegisterUserRequest $request): RedirectResponse
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

        try {
            event(new Registered($user));
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('verification.notice')
                ->with('error', __('app.auth.verify_otp_send_failed'));
        }

        return redirect()
            ->route('verification.notice')
            ->with('status', 'verification-otp-sent');
    }
}
