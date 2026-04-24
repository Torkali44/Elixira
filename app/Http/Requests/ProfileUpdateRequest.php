<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $phoneNumber = $this->filled('phone_number')
            ? preg_replace('/\D+/', '', (string) $this->input('phone_number'))
            : null;

        $userCode = $this->filled('user_code')
            ? Str::upper(trim((string) $this->input('user_code')))
            : null;

        $email = $this->filled('email')
            ? Str::lower(trim((string) $this->input('email')))
            : null;

        $this->merge([
            'phone_number' => $phoneNumber,
            'user_code' => $userCode,
            'email' => $email,
            'remove_avatar' => $this->boolean('remove_avatar'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s.\'-]+$/u'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'user_code' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[A-Z0-9_-]+$/',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone_number' => ['nullable', 'string', 'regex:/^[0-9]{7,15}$/'],
            'country_code' => ['nullable', 'required_with:phone_number', 'string', 'in:+966,+971'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Your full name is required.',
            'name.regex' => 'Your name may only contain letters, spaces, apostrophes, dots, and dashes.',
            'email.required' => 'Your email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already being used.',
            'user_code.regex' => 'Member codes may only contain letters, numbers, underscores, and dashes.',
            'user_code.unique' => 'This member code is already assigned to another account.',
            'phone_number.regex' => 'Phone numbers must contain 7 to 15 digits.',
            'country_code.required_with' => 'Please choose a country code when adding a phone number.',
            'avatar.image' => 'The avatar must be an image file.',
            'avatar.mimes' => 'The avatar must be a JPG, PNG, or WEBP image.',
            'avatar.max' => 'The avatar image must be 2MB or smaller.',
        ];
    }
}
