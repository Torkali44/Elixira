@props([
    'item',
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
    $pricingService = app(\App\Support\ItemPricingService::class);
    $selectedCountry = $pricingService->resolveCountryCode($selectedCountry);
    $pricing = $pricingService->getPriceBreakdown($item, auth()->user(), $selectedCountry, $countryPriceId);
    $availableCountries = $pricingService->availableCountryCodes($item);
    $flags = $pricingService->countryFlags();
    $labels = $pricingService->supportedCountries();
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
            <div class="elx-product-pricing__price-line" id="product-member-price-line" style="font-size: {{ $size }};">
                @if($isRtl)
                    <span class="elx-product-pricing__amount" id="product-member-price">{{ number_format($pricing['member_price'], 2) }}</span>
                    <span class="elx-product-pricing__currency" id="product-member-currency">{{ $pricingService->currencySymbol($selectedCountry) }}</span>
                @else
                    <span class="elx-product-pricing__currency" id="product-member-currency">{{ $pricingService->currencySymbol($selectedCountry) }}</span>
                    <span class="elx-product-pricing__amount" id="product-member-price">{{ number_format($pricing['member_price'], 2) }}</span>
                @endif
            </div>
            @php
                $showGuestPrice = ! empty($pricing['has_higher_guest_price'])
                    || (! empty($pricing['original_member_price']) && $pricing['original_member_price'] > $pricing['member_price']);
                $guestDisplayPrice = ! empty($pricing['original_member_price']) && $pricing['original_member_price'] > $pricing['member_price']
                    ? $pricing['original_member_price']
                    : ($pricing['guest_price'] ?? $pricing['member_price']);
            @endphp
            <div class="elx-product-pricing__price-line elx-product-pricing__price-line--guest" id="product-guest-price-wrap" style="font-size: {{ $smallSize }}; {{ $showGuestPrice ? '' : 'display: none;' }}">
                @if($isRtl)
                    <span class="elx-product-pricing__amount" id="product-guest-price">{{ number_format($guestDisplayPrice, 2) }}</span>
                    <span class="elx-product-pricing__currency" id="product-guest-currency">{{ $pricingService->currencySymbol($selectedCountry) }}</span>
                @else
                    <span class="elx-product-pricing__currency" id="product-guest-currency">{{ $pricingService->currencySymbol($selectedCountry) }}</span>
                    <span class="elx-product-pricing__amount" id="product-guest-price">{{ number_format($guestDisplayPrice, 2) }}</span>
                @endif
            </div>
        </div>
    @endif
</div>
