<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $phoneNumber = $this->filled('phone_number')
            ? preg_replace('/\D+/', '', (string) $this->input('phone_number'))
            : null;

        $userCode = $this->filled('user_code')
            ? Str::upper(trim((string) $this->input('user_code')))
            : null;

        $this->merge([
            'phone_number' => $phoneNumber,
            'user_code' => $userCode,
        ]);
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s.\'-]+$/u'],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{7,15}$/'],
            'country_code' => ['required', 'string', 'in:+966,+971'],
            'user_code' => ['nullable', 'string', 'max:100', 'regex:/^[A-Z0-9_-]+$/'],
            'address' => ['required', 'string', 'min:10', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Please enter your full name.',
            'customer_name.min' => 'Your name should be at least 2 characters.',
            'customer_name.max' => 'Your name may not be longer than 255 characters.',
            'customer_name.regex' => 'Your name may only contain letters, spaces, apostrophes, dots, and dashes.',
            'phone_number.required' => 'A phone number is required for order updates.',
            'phone_number.regex' => 'Enter a valid phone number using 7 to 15 digits.',
            'country_code.required' => 'Please choose your country code.',
            'country_code.in' => 'Please choose a valid country code.',
            'user_code.regex' => 'Member codes may only contain letters, numbers, underscores, and dashes.',
            'address.required' => 'A delivery address is required.',
            'address.min' => 'Please enter a more detailed address (at least 10 characters).',
            'address.max' => 'The address may not exceed 500 characters.',
            'notes.max' => 'Notes may not exceed 1000 characters.',
        ];
    }
}
