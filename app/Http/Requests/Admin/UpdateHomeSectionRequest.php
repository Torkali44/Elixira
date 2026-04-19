<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHomeSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'admin_label' => 'nullable|string|max:255',
            'template' => ['required', 'string', 'max:64', Rule::in([
                'hero', 'heading', 'featured_products', 'split', 'newsletter', 'icon_cards', 'paragraph', 'cta',
            ])],
            'title' => 'nullable|string|max:2000',
            'subtitle' => 'nullable|string|max:5000',
            'body' => 'nullable|string|max:20000',
            'button_label' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:0|max:65535',
            'is_active' => 'nullable|in:0,1',
            'image' => 'nullable|image|max:4096',
        ];
    }
}
