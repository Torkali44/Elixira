<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('email')) {
            $this->merge([
                'email' => Str::lower(trim((string) $this->input('email'))),
            ]);
        }

        if ($this->filled('phone')) {
            $this->merge([
                'phone' => preg_replace('/\D+/', '', (string) $this->input('phone')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s.\'-]+$/u'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone_country_code' => ['required', 'in:+966,+971'],
            'phone' => ['required', 'string', 'regex:/^\d{8,12}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'account_type' => ['required', 'string', 'in:customer,vendor'],
            'brand_name' => ['nullable', 'string', 'max:255', 'required_if:account_type,vendor'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'name.regex' => 'Your name may only contain letters, spaces, apostrophes, dots, and dashes.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'Please enter your phone number.',
            'phone.regex' => 'Please enter a valid phone number (digits only, 8–12 digits).',
            'phone_country_code.required' => 'Please select your country code.',
            'phone_country_code.in' => 'Please select a valid country code.',
            'avatar.image' => 'The avatar must be an image file.',
            'avatar.mimes' => 'The avatar must be a JPG, PNG, or WEBP image.',
            'avatar.max' => 'The avatar image must be 2MB or smaller.',
        ];
    }
}
