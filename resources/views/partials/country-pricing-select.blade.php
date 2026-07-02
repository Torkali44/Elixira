@props([
    'availableCountries' => [],
    'selectedCountry' => 'KSA',
    'flags' => [],
    'labels' => [],
    'countryInputId' => null,
    'hideLabel' => false,
])

<form method="GET" action="{{ url()->current() }}" class="elx-country-pricing-form">
    @unless($hideLabel)
        <label class="elx-country-pricing-form__label">{{ __('shop.available_in') }}</label>
    @endunless

    <div class="elx-country-select-custom" data-country-select-custom data-country-input="{{ $countryInputId }}">
        <input type="hidden" name="country" value="{{ $selectedCountry }}" data-country-select-value>

        <button type="button" class="elx-country-select-custom__toggle" data-country-select-toggle aria-expanded="false">
            <img src="{{ $flags[$selectedCountry] ?? '' }}" alt="" class="elx-country-select-custom__flag" data-country-select-flag>
            <span class="elx-country-select-custom__label" data-country-select-label>{{ $labels[$selectedCountry] ?? $selectedCountry }}</span>
            <i class="fas fa-chevron-down elx-country-select-custom__icon" aria-hidden="true"></i>
        </button>

        <ul class="elx-country-select-custom__menu" data-country-select-menu hidden>
            @foreach($availableCountries as $code)
                <li>
                    <button type="button"
                        class="elx-country-select-custom__option {{ $selectedCountry === $code ? 'is-active' : '' }}"
                        data-country-value="{{ $code }}"
                        data-country-flag="{{ $flags[$code] ?? '' }}"
                        data-country-label="{{ $labels[$code] ?? $code }}">
                        @if($flags[$code] ?? null)
                            <img src="{{ $flags[$code] }}" alt="">
                        @endif
                        <span>{{ $labels[$code] ?? $code }}</span>
                    </button>
                </li>
            @endforeach
        </ul>
    </div>
</form>
