<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\ValidatesCountryPrices;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
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
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'long_description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'reward_points' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:10240',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'country_prices' => 'nullable|array',
            'country_prices.KSA' => 'nullable|array',
            'country_prices.KSA.member_price' => 'nullable|numeric|min:0',
            'country_prices.KSA.guest_price' => 'nullable|numeric|min:0',
            'country_prices.UAE' => 'nullable|array',
            'country_prices.UAE.member_price' => 'nullable|numeric|min:0',
            'country_prices.UAE.guest_price' => 'nullable|numeric|min:0',
            'package_items' => 'nullable|array',
            'package_items.*.selected' => 'nullable|boolean',
            'package_items.*.quantity' => 'nullable|integer|min:1',
            'tags' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name_en.required' => __('admin.validation.name_en_required'),
            'name_ar.required' => __('admin.validation.name_ar_required'),
            'description_en.required' => __('admin.validation.description_en_required'),
            'description_ar.required' => __('admin.validation.description_ar_required'),
            'stock.required' => __('admin.validation.stock_required'),
        ];
    }
}
