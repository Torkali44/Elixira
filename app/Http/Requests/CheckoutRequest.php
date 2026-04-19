<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|min:2|max:255',
            'customer_phone' => 'required|string|min:8|max:20',
            'address' => 'required|string|min:5|max:500',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Please enter your full name.',
            'customer_name.min' => 'Your name should be at least 3 characters.',
            'customer_name.max' => 'Your name may not be longer than 255 characters.',
            'customer_name.regex' => 'Your name may only contain letters, spaces, and simple punctuation.',
            'customer_phone.required' => 'A phone number is required for order updates.',
            'customer_phone.regex' => 'Enter a valid phone number (10–15 digits, optional + prefix).',
            'address.required' => 'A delivery address is required.',
            'address.min' => 'Please enter a more detailed address (at least 10 characters).',
            'address.max' => 'The address may not exceed 500 characters.',
            'notes.max' => 'Notes may not exceed 1000 characters.',
        ];
    }
}
