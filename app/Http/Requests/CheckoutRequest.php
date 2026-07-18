<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
            'user_code' => ['required', 'string', 'max:100', 'regex:/^[A-Z0-9_-]+$/'],
            'address' => ['required', 'string', 'min:10', 'max:500'],
            'delivery_city_id' => ['nullable', 'integer', Rule::exists('delivery_cities', 'id')->where('is_active', true)],
            'shared_shipping_order_id' => ['nullable', 'integer', 'exists:orders,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => __('cart_page.validation.customer_name_required'),
            'customer_name.min' => __('cart_page.validation.customer_name_min'),
            'customer_name.max' => __('cart_page.validation.customer_name_max'),
            'customer_name.regex' => __('cart_page.validation.customer_name_regex'),
            'phone_number.required' => __('cart_page.validation.phone_required'),
            'phone_number.regex' => __('cart_page.validation.phone_regex'),
            'country_code.required' => __('cart_page.validation.country_code_required'),
            'country_code.in' => __('cart_page.validation.country_code_invalid'),
            'user_code.required' => __('cart_page.code_required'),
            'user_code.regex' => __('cart_page.validation.user_code_regex'),
            'address.required' => __('cart_page.validation.address_required'),
            'address.min' => __('cart_page.validation.address_min'),
            'address.max' => __('cart_page.validation.address_max'),
            'delivery_city_id.exists' => __('cart_page.invalid_delivery_city'),
            'shared_shipping_order_id.exists' => __('cart_page.shared_shipping_not_found'),
            'notes.max' => __('cart_page.validation.notes_max'),
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_name' => __('cart_page.full_name'),
            'phone_number' => __('cart_page.phone'),
            'country_code' => __('cart_page.delivery_city_label'),
            'user_code' => __('cart_page.enter_code'),
            'address' => __('cart_page.shipping_address'),
            'delivery_city_id' => __('cart_page.delivery_city_label'),
            'notes' => __('cart_page.notes'),
        ];
    }
}
