<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Contracts\Validation\Validator;

trait ValidatesCountryPrices
{
    protected function validateCountryPrices(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $countryPrices = $this->input('country_prices', []);

            if (! is_array($countryPrices)) {
                $validator->errors()->add('country_prices', __('admin.validation.country_price_required'));

                return;
            }

            foreach (['KSA', 'UAE'] as $code) {
                $country = $countryPrices[$code] ?? null;

                if (! is_array($country) || empty($country['enabled'])) {
                    continue;
                }

                if (filled($country['member_price'] ?? null)) {
                    return;
                }

                $validator->errors()->add(
                    "country_prices.{$code}.member_price",
                    __('admin.validation.member_price_required', ['country' => $code])
                );

                return;
            }

            $validator->errors()->add('country_prices', __('admin.validation.country_price_required'));
        });
    }
}
