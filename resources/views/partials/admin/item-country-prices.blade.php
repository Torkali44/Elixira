@php
    $countryPrices = $item->countryPrices ?? collect();
    $ksa = $countryPrices->firstWhere('country_code', 'KSA');
    $uae = $countryPrices->firstWhere('country_code', 'UAE');
@endphp

<div class="mb-4">
    <label class="form-label fw-semibold">{{ __('admin.items_page.country_pricing') }} <span class="text-danger">*</span></label>
    <p class="text-muted small">{{ __('admin.items_page.country_pricing_hint') }}</p>
    @error('country_prices')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

    <div class="row g-3">
        @foreach(['KSA' => __('shop.country_ksa'), 'UAE' => __('shop.country_uae')] as $code => $label)
            @php
                $row = $code === 'KSA' ? $ksa : $uae;
            @endphp
            <div class="col-md-6">
                <div class="border rounded p-3 h-100">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="country_prices[{{ $code }}][enabled]" value="1" id="country_{{ $code }}"
                            @checked(old("country_prices.{$code}.enabled", $row !== null))>
                        <label class="form-check-label fw-semibold" for="country_{{ $code }}">
                            @if($code === 'KSA')
                                🇸🇦
                            @else
                                🇦🇪
                            @endif
                            {{ $label }}
                        </label>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">{{ __('admin.items_page.member_price') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control @error("country_prices.{$code}.member_price") is-invalid @enderror"
                            name="country_prices[{{ $code }}][member_price]"
                            value="{{ old("country_prices.{$code}.member_price", $row?->member_price) }}">
                        @error("country_prices.{$code}.member_price")<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label small">{{ __('admin.items_page.guest_price') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control"
                            name="country_prices[{{ $code }}][guest_price]"
                            value="{{ old("country_prices.{$code}.guest_price", $row?->guest_price) }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
