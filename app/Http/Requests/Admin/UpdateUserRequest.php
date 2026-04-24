<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $phone = $this->filled('phone')
            ? preg_replace('/[^\d+]/', '', (string) $this->input('phone'))
            : null;

        $email = $this->filled('email')
            ? Str::lower(trim((string) $this->input('email')))
            : null;

        $userCode = $this->filled('user_code')
            ? Str::upper(trim((string) $this->input('user_code')))
            : null;

        $this->merge([
            'phone' => $phone,
            'email' => $email,
            'user_code' => $userCode,
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
                Rule::unique(User::class)->ignore($this->route('user')?->id),
            ],
            'phone' => ['nullable', 'string', 'regex:/^\+?[0-9]{8,20}$/'],
            'user_code' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[A-Z0-9_-]+$/',
                Rule::unique(User::class)->ignore($this->route('user')?->id),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The user name is required.',
            'name.regex' => 'The user name may only contain letters, spaces, apostrophes, dots, and dashes.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already assigned to another user.',
            'phone.regex' => 'Phone numbers must contain 8 to 20 digits and may start with +.',
            'user_code.regex' => 'Member codes may only contain letters, numbers, underscores, and dashes.',
            'user_code.unique' => 'This member code is already assigned to another user.',
            'avatar.image' => 'The avatar must be an image file.',
            'avatar.mimes' => 'The avatar must be a JPG, PNG, or WEBP image.',
            'avatar.max' => 'The avatar image must be 2MB or smaller.',
        ];
    }
}
