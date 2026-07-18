@props([
    'package',
    'selectedCountry' => null,
    'countryPriceId' => null,
    'align' => 'flex-end',
    'size' => 'inherit',
    'smallSize' => '0.85rem',
    'showSelector' => true,
    'showPrice' => true,
    'hideCountryName' => false,
    'countrySelector' => 'buttons',
    'countryInputId' => null,
])

@php
    $pricingService = app(\App\Support\PackagePricingService::class);
    $itemPricing = app(\App\Support\ItemPricingService::class);
    $selectedCountry = $itemPricing->resolveCountryCode($selectedCountry);
    $pricing = $pricingService->getPriceBreakdown($package, auth()->user(), $selectedCountry, $countryPriceId);
    $availableCountries = $pricingService->availableCountryCodes($package);
    $flags = $itemPricing->countryFlags();
    $labels = $itemPricing->supportedCountries();
    $isRtl = app()->getLocale() === 'ar';
@endphp

<div {{ $attributes->merge(['class' => 'elx-product-pricing']) }} data-pricing-country="{{ $selectedCountry }}">
    @if($showSelector && count($availableCountries) > 0)
        @if($countrySelector === 'dropdown')
            @include('partials.country-pricing-select', [
                'availableCountries' => $availableCountries,
                'selectedCountry' => $selectedCountry,
                'flags' => $flags,
                'labels' => $labels,
                'countryInputId' => $countryInputId,
                'hideLabel' => $hideCountryName,
            ])
        @else
            <form method="GET" action="{{ url()->current() }}" class="elx-country-pricing-form">
                @if(!$hideCountryName)
                    <label class="elx-country-pricing-form__label">{{ __('shop.available_in') }}</label>
                @endif
                <div class="elx-country-pricing-form__buttons">
                    @foreach($availableCountries as $code)
                        <button type="submit" name="country" value="{{ $code }}" class="elx-country-pricing-form__button {{ $selectedCountry === $code ? 'is-active' : '' }}">
                            @if($flags[$code] ?? null)
                                <img src="{{ $flags[$code] }}" alt="">
                            @endif
                            <span>{{ $hideCountryName ? $code : ($labels[$code] ?? $code) }}</span>
                        </button>
                    @endforeach
                </div>
            </form>
        @endif
    @endif

    @if($showPrice)
        <div class="elx-product-pricing__prices">
            <div class="elx-product-pricing__price-line" style="font-size: {{ $size }};">
                @if($isRtl)
                    <span class="elx-product-pricing__amount">{{ number_format($pricing['member_price'], 2) }}</span>
                    <span class="elx-product-pricing__currency">{{ $itemPricing->currencySymbol($selectedCountry) }}</span>
                @else
                    <span class="elx-product-pricing__currency">{{ $itemPricing->currencySymbol($selectedCountry) }}</span>
                    <span class="elx-product-pricing__amount">{{ number_format($pricing['member_price'], 2) }}</span>
                @endif
            </div>
            @if(! empty($pricing['has_higher_guest_price']))
                <div class="elx-product-pricing__price-line elx-product-pricing__price-line--guest" style="font-size: {{ $smallSize }};">
                    @if($isRtl)
                        <span class="elx-product-pricing__amount">{{ number_format($pricing['guest_price'], 2) }}</span>
                        <span class="elx-product-pricing__currency">{{ $itemPricing->currencySymbol($selectedCountry) }}</span>
                    @else
                        <span class="elx-product-pricing__currency">{{ $itemPricing->currencySymbol($selectedCountry) }}</span>
                        <span class="elx-product-pricing__amount">{{ number_format($pricing['guest_price'], 2) }}</span>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
