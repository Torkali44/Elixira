@props([
    'package',
    'selectedCountry' => null,
    'align' => 'flex-end',
    'size' => 'inherit',
    'smallSize' => '0.85rem',
    'showSelector' => true,
    'showPrice' => true,
    'hideCountryName' => false
])

@php
    $pricingService = app(\App\Support\PackagePricingService::class);
    $itemPricing = app(\App\Support\ItemPricingService::class);
    $selectedCountry = $itemPricing->resolveCountryCode($selectedCountry);
    $pricing = $pricingService->getPriceBreakdown($package, auth()->user(), $selectedCountry);
    $availableCountries = $pricingService->availableCountryCodes($package);
    $flags = $itemPricing->countryFlags();
    $labels = $itemPricing->supportedCountries();
@endphp

<div {{ $attributes->merge(['class' => 'elx-product-pricing']) }}>
    @if($showSelector && count($availableCountries) > 0)
        <form method="GET" action="{{ url()->current() }}" style="margin-bottom: 0.5rem;">
            @if(!$hideCountryName)
                <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.55); margin-bottom: 0.35rem;">{{ __('shop.available_in') }}</label>
            @endif
            <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; justify-content: {{ $align === 'flex-end' ? 'flex-end' : 'flex-start' }};">
                @foreach($availableCountries as $code)
                    <button type="submit" name="country" value="{{ $code }}"
                        style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.2rem 0.5rem; border-radius: 999px; border: 1px solid {{ $selectedCountry === $code ? 'rgba(74, 200, 246, 0.8)' : 'rgba(255,255,255,0.15)' }}; background: {{ $selectedCountry === $code ? 'rgba(74, 200, 246, 0.15)' : 'rgba(255,255,255,0.04)' }}; color: #fff; cursor: pointer; transition: all 0.2s ease;">
                        @if($flags[$code] ?? null)
                            <img src="{{ $flags[$code] }}" alt="" style="width: 16px; height: 11px; border-radius: 1px; object-fit: cover;">
                        @endif
                        <span style="font-size: 0.72rem; font-weight: 600;">{{ $hideCountryName ? $code : ($labels[$code] ?? $code) }}</span>
                    </button>
                @endforeach
            </div>
        </form>
    @endif

    @if($showPrice)
        <div style="display: flex; flex-direction: column; align-items: {{ $align }}; gap: 0.15rem;">
            <div style="font-size: {{ $size }}; font-weight: 700; color: var(--elx-cyan, #4ac8f6); line-height: 1.1;">
                ﷼ {{ number_format($pricing['member_price'], 2) }}
            </div>
            @if($pricing['guest_price'] > $pricing['member_price'])
                <div style="font-size: {{ $smallSize }}; color: rgba(255,255,255,0.45); text-decoration: line-through; line-height: 1.1;">
                    ﷼ {{ number_format($pricing['guest_price'], 2) }}
                </div>
            @endif
        </div>
    @endif
</div>
