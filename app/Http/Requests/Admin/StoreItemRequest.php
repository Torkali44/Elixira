<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\ValidatesCountryPrices;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    use ValidatesCountryPrices;

    public function authorize(): bool
    {
        return true;
    }

    public function withValidator(Validator $validator): void
    {
        $this->validateCountryPrices($validator);
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'size_en' => 'nullable|string|max:120',
            'size_ar' => 'nullable|string|max:120',
            'brand' => 'nullable|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'country_prices' => 'nullable|array',
            'country_prices.KSA' => 'nullable|array',
            'country_prices.KSA.member_price' => 'nullable|numeric|min:0',
            'country_prices.KSA.guest_price' => 'nullable|numeric|min:0',
            'country_prices.UAE' => 'nullable|array',
            'country_prices.UAE.member_price' => 'nullable|numeric|min:0',
            'country_prices.UAE.guest_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'points' => 'nullable|integer|min:0',
            'reward_points' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:10240',
            'images' => 'nullable|array',
            'images.*' => 'image|max:10240',
            'is_featured' => 'boolean',
            'long_description_en' => 'nullable|string',
            'long_description_ar' => 'nullable|string',
            'benefits_en' => 'nullable|string',
            'benefits_ar' => 'nullable|string',
            'ingredients_en' => 'nullable|string',
            'ingredients_ar' => 'nullable|string',
            'usage_instructions_en' => 'nullable|string',
            'usage_instructions_ar' => 'nullable|string',
            'warnings_en' => 'nullable|string',
            'warnings_ar' => 'nullable|string',
            'tags' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please choose a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'name_en.required' => __('admin.validation.name_en_required'),
            'name_ar.required' => __('admin.validation.name_ar_required'),
            'description_en.required' => __('admin.validation.description_en_required'),
            'description_ar.required' => __('admin.validation.description_ar_required'),
            'stock.required' => __('admin.validation.stock_required'),
            'image.image' => 'The file must be an image.',
            'image.max' => 'Image size must be 10MB or less.',
        ];
    }
}
