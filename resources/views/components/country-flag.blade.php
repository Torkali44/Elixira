@props(['country' => 'KSA', 'size' => 20, 'showLabel' => true])

@php
    $countryCode = strtoupper((string) $country);
    $flag = app(\App\Support\ItemPricingService::class)->countryFlag($countryCode);
    $label = match ($countryCode) {
        'KSA' => __('shop.country_ksa'),
        'UAE' => __('shop.country_uae'),
        default => $countryCode,
    };
@endphp

@if($flag)
    <span {{ $attributes->merge(['class' => 'd-inline-flex align-items-center gap-1']) }}>
        <img src="{{ $flag }}" alt="{{ $label }}" style="width: {{ $size }}px; height: {{ round($size * 0.7) }}px; border-radius: 2px; object-fit: cover;">
        @if($showLabel)
            <span>{{ $label }}</span>
        @endif
    </span>
@elseif($showLabel)
    <span {{ $attributes }}>{{ $label }}</span>
@endif
