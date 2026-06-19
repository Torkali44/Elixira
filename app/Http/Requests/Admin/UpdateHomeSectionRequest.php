<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:2000',
            'subtitle' => 'required|string|max:5000',
            'body' => 'nullable|string|max:20000',
            'button_label' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:500',
            'image' => 'nullable|image|max:4096',
        ];
    }
}
