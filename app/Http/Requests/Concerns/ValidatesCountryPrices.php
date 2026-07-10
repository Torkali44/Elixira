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

                $variants = $country['variants'] ?? null;
                if (is_array($variants)) {
                    foreach ($variants as $variant) {
                        if (is_array($variant) && filled($variant['member_price'] ?? null)) {
                            return;
                        }
                    }
                } elseif (filled($country['member_price'] ?? null)) {
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
