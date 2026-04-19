<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1|max:50'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Product id is required.',
            'id.exists' => 'That product no longer exists.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity may not exceed 50.',
        ];
    }
}
