<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => 'required|exists:items,id',
            'quantity' => 'nullable|integer|min:1|max:50'
        ];
    }

    public function messages(): array
    {
        return [
            'item_id.required' => 'Product id is required.',
            'item_id.exists' => 'That product no longer exists.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity may not exceed 50.',
        ];
    }
}
