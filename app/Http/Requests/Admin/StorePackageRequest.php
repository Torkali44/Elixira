<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
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
}
