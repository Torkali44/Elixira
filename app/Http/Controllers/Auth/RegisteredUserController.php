<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'avatar' => $request->hasFile('avatar')
                ? $request->file('avatar')->store('users/avatars', 'public')
                : null,
            'password' => Hash::make($request->password),
        ]);

        if ($request->account_type === 'vendor') {
            \App\Models\VendorProfile::create([
                'user_id' => $user->id,
                'brand_name' => $request->brand_name,
                'status' => 'draft',
                'payment_method' => 'cash_on_delivery',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($request->account_type === 'vendor') {
            return redirect()->route('vendor.onboarding')->with('status', 'Welcome! Please complete your vendor profile.');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
