@php
    $countryPrices = $package->countryPrices ?? collect();
    $ksaVariants = $countryPrices->where('country_code', 'KSA')->values();
    $uaeVariants = $countryPrices->where('country_code', 'UAE')->values();
@endphp

<div class="mb-4">
    <label class="form-label fw-semibold">{{ __('admin.items_page.country_pricing') }} <span class="text-danger">*</span></label>
    <p class="text-muted small">{{ __('admin.items_page.country_pricing_hint') }}</p>
    @error('country_prices')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
    <div class="row g-3">
        @foreach(['KSA' => ['label' => __('shop.country_ksa'), 'variants' => $ksaVariants], 'UAE' => ['label' => __('shop.country_uae'), 'variants' => $uaeVariants]] as $code => $meta)
            @php
                $variants = $meta['variants'];
                $oldVariants = old("country_prices.{$code}.variants");
                if (is_array($oldVariants) && count($oldVariants) > 0) {
                    $rows = collect($oldVariants);
                } elseif ($variants->isNotEmpty()) {
                    $rows = $variants;
                } else {
                    $rows = collect([null]);
                }
            @endphp
            <div class="col-12">
                <div class="border rounded p-3 country-pricing-card">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="country_prices[{{ $code }}][enabled]" value="1" id="pkg_country_{{ $code }}"
                            @checked(old("country_prices.{$code}.enabled", $variants->isNotEmpty()))>
                        <label class="form-check-label fw-semibold" for="pkg_country_{{ $code }}">{{ $meta['label'] }}</label>
                    </div>
                    <div class="country-variants" data-country="{{ $code }}">
                        @foreach($rows as $index => $row)
                            @php
                                $isOldArray = is_array($row);
                                $memberPrice = $isOldArray ? ($row['member_price'] ?? '') : ($row?->member_price ?? '');
                                $guestPrice = $isOldArray ? ($row['guest_price'] ?? '') : ($row?->guest_price ?? '');
                                $sizeEn = $isOldArray ? ($row['size_en'] ?? '') : ($row?->size_en ?? '');
                                $sizeAr = $isOldArray ? ($row['size_ar'] ?? '') : ($row?->size_ar ?? '');
                                $rewardPoints = $isOldArray ? ($row['reward_points'] ?? '') : ($row?->reward_points ?? '');
                                $stock = $isOldArray ? ($row['stock'] ?? '') : ($row?->stock ?? '');
                            @endphp
                            <div class="country-variant-row border rounded p-3 mb-3 country-pricing-variant" data-variant-row>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="small text-muted">{{ __('admin.items_page.size_variant') }} #{{ $loop->iteration }}</strong>
                                    @if($loop->iteration > 1)
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-variant-row">{{ __('admin.items_page.remove_variant') }}</button>
                                    @endif
                                </div>
                                <div class="row g-2 country-variant-fields">
                                    <div class="col-6 col-md-3">
                                        <label class="form-label small">{{ __('admin.items_page.size_en') ?? 'Size (EN)' }}</label>
                                        <input type="text" class="form-control" name="country_prices[{{ $code }}][variants][{{ $index }}][size_en]" value="{{ $sizeEn }}">
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <label class="form-label small">{{ __('admin.items_page.size_ar') ?? 'Size (AR)' }}</label>
                                        <input type="text" class="form-control" name="country_prices[{{ $code }}][variants][{{ $index }}][size_ar]" value="{{ $sizeAr }}">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label small">{{ __('admin.items_page.member_price') }}</label>
                                        <input type="number" step="0.01" min="0" class="form-control" name="country_prices[{{ $code }}][variants][{{ $index }}][member_price]" value="{{ $memberPrice }}">
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <label class="form-label small">{{ __('admin.items_page.guest_price') }}</label>
                                        <input type="number" step="0.01" min="0" class="form-control" name="country_prices[{{ $code }}][variants][{{ $index }}][guest_price]" value="{{ $guestPrice }}">
                                    </div>
                                    <div class="col-6 col-md-1">
                                        <label class="form-label small">{{ __('admin.items_page.stock') ?? 'Stock' }}</label>
                                        <input type="number" min="0" class="form-control" name="country_prices[{{ $code }}][variants][{{ $index }}][stock]" value="{{ $stock }}">
                                    </div>
                                    <div class="col-6 col-md-1">
                                        <label class="form-label small">{{ __('admin.items_page.country_reward_points') }}</label>
                                        <input type="number" min="0" class="form-control" name="country_prices[{{ $code }}][variants][{{ $index }}][reward_points]" value="{{ $rewardPoints }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary add-variant-row" data-country="{{ $code }}">
                        <i class="fas fa-plus me-1"></i> {{ __('admin.items_page.add_size_variant') }}
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-variant-row').forEach(function (button) {
        button.addEventListener('click', function () {
            const country = button.dataset.country;
            const container = document.querySelector('.country-variants[data-country="' + country + '"]');
            const index = container.querySelectorAll('[data-variant-row]').length;
            const row = document.createElement('div');
            row.className = 'country-variant-row border rounded p-3 mb-3 country-pricing-variant';
            row.dataset.variantRow = '';
            row.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="small text-muted">{{ __('admin.items_page.size_variant') }} #${index + 1}</strong>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-variant-row">{{ __('admin.items_page.remove_variant') }}</button>
                </div>
                <div class="row g-2 country-variant-fields">
                    <div class="col-6 col-md-3"><label class="form-label small">{{ __('admin.items_page.size_en') ?? 'Size (EN)' }}</label><input type="text" class="form-control" name="country_prices[${country}][variants][${index}][size_en]"></div>
                    <div class="col-6 col-md-3"><label class="form-label small">{{ __('admin.items_page.size_ar') ?? 'Size (AR)' }}</label><input type="text" class="form-control" name="country_prices[${country}][variants][${index}][size_ar]"></div>
                    <div class="col-6 col-md-2"><label class="form-label small">{{ __('admin.items_page.member_price') }}</label><input type="number" step="0.01" min="0" class="form-control" name="country_prices[${country}][variants][${index}][member_price]"></div>
                    <div class="col-6 col-md-2"><label class="form-label small">{{ __('admin.items_page.guest_price') }}</label><input type="number" step="0.01" min="0" class="form-control" name="country_prices[${country}][variants][${index}][guest_price]"></div>
                    <div class="col-6 col-md-1"><label class="form-label small">{{ __('admin.items_page.stock') ?? 'Stock' }}</label><input type="number" min="0" class="form-control" name="country_prices[${country}][variants][${index}][stock]"></div>
                    <div class="col-6 col-md-1"><label class="form-label small">{{ __('admin.items_page.country_reward_points') }}</label><input type="number" min="0" class="form-control" name="country_prices[${country}][variants][${index}][reward_points]"></div>
                </div>`;
            container.appendChild(row);
        });
    });

    document.addEventListener('click', function (event) {
        const button = event.target.closest('.remove-variant-row');
        if (!button) return;
        const row = button.closest('[data-variant-row]');
        if (row) row.remove();
    });
});
</script>
