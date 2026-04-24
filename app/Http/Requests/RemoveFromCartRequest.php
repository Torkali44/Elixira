<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoveFromCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:items,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Product id is required.',
            'id.integer' => 'Product id must be a valid number.',
            'id.exists' => 'That product no longer exists.',
        ];
    }
}
