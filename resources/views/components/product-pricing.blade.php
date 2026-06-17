@php
    $pricing = app(\App\Support\ItemPricingService::class)->getPriceBreakdown($item, auth()->user());
    $flag = app(\App\Support\ItemPricingService::class)->countryFlag($pricing['country_code']);
@endphp

<div {{ $attributes->merge(['class' => 'elx-product-pricing']) }}>
    @if($flag)
        <div style="display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 0.35rem;">
            <img src="{{ $flag }}" alt="" style="width: 20px; height: 14px; border-radius: 2px; object-fit: cover;">
            <span style="font-size: 0.75rem; color: rgba(255,255,255,0.55);">{{ match($pricing['country_code']) { 'KSA' => __('shop.country_ksa'), 'UAE' => __('shop.country_uae'), default => $pricing['country_code'] } }}</span>
        </div>
    @endif

    <div style="display: flex; flex-direction: column; align-items: {{ $align ?? 'flex-end' }}; gap: 0.15rem;">
        <div style="font-size: {{ $size ?? 'inherit' }}; font-weight: 700; color: var(--elx-cyan, #4ac8f6); line-height: 1.1;">
            ﷼ {{ number_format($pricing['member_price'], 2) }}
        </div>
        @if($pricing['guest_price'] > $pricing['member_price'])
            <div style="font-size: {{ $smallSize ?? '0.85rem' }}; color: rgba(255,255,255,0.45); text-decoration: line-through; line-height: 1.1;">
                ﷼ {{ number_format($pricing['guest_price'], 2) }}
            </div>
        @endif
    </div>
</div>
