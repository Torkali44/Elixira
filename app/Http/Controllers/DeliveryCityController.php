<?php

namespace App\Http\Controllers;

use App\Models\DeliveryCity;
use App\Models\DeliveryCountry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeliveryCityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $phoneCountry = (string) $request->query('phone_country', '+966');
        $countryCode = match ($phoneCountry) {
            '+971' => 'UAE',
            '+966' => 'KSA',
            default => null,
        };

        if ($countryCode === null) {
            return response()->json(['cities' => [], 'currency' => null]);
        }

        $country = DeliveryCountry::query()
            ->where('code', $countryCode)
            ->where('is_active', true)
            ->first();

        if (! $country) {
            return response()->json(['cities' => [], 'currency' => null]);
        }

        $locale = app()->getLocale();
        $currencyLabel = $country->local_currency_label;
        $cities = $country->activeCities->map(function (DeliveryCity $city) use ($country, $locale, $currencyLabel) {
            $name = $locale === 'ar'
                ? ($city->name_ar ?: $city->name_en)
                : ($city->name_en ?: $city->name_ar);
            $fee = number_format((float) $city->delivery_fee, 0);

            return [
                'id' => $city->id,
                'name' => $name,
                'delivery_fee' => (float) $city->delivery_fee,
                'currency' => $country->currency_code,
                'label' => "{$name} — {$fee} {$currencyLabel}",
            ];
        })->values();

        return response()->json([
            'country' => $country->code,
            'currency' => $country->currency_code,
            'currency_label' => $currencyLabel,
            'cities' => $cities,
        ]);
    }
}
