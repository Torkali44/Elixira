@props([
    'name',
    'value' => '',
    'required' => false,
    'id' => null,
    'placeholder' => null,
])

@php
    $selectId = $id ?? 'dxn_country_' . preg_replace('/[^a-z0-9_]/i', '_', $name);
    $countries = [
        'KSA' => ['label' => __('shop.country_ksa'), 'flag' => asset('images/sa.png')],
        'UAE' => ['label' => __('shop.country_uae'), 'flag' => asset('images/AE.png')],
    ];
    $fieldName = str_replace(['[', ']'], ['.', ''], $name);
    $selected = old($fieldName, $value);
    $placeholderText = $placeholder ?? __('dxn_team.form_country_placeholder');
    $selectedMeta = $countries[$selected] ?? null;
    $toggleLabel = $selectedMeta['label'] ?? $placeholderText;
    $toggleFlag = $selectedMeta['flag'] ?? null;
@endphp

<div
    class="elx-country-select-custom dxn-country-select-custom"
    data-country-select-custom
    data-country-select-no-submit
    style="width: 100%;"
>
    <input
        type="hidden"
        name="{{ $name }}"
        id="{{ $selectId }}"
        value="{{ $selected }}"
        data-country-select-value
        @required($required)
    >

    <button
        type="button"
        class="elx-country-select-custom__toggle dxn-select"
        data-country-select-toggle
        aria-expanded="false"
        style="width: 100%; margin-bottom: 0;"
    >
        @if($toggleFlag)
            <img
                src="{{ $toggleFlag }}"
                alt=""
                class="elx-country-select-custom__flag"
                data-country-select-flag
            >
        @else
            <img
                alt=""
                class="elx-country-select-custom__flag"
                data-country-select-flag
                hidden
            >
        @endif
        <span class="elx-country-select-custom__label" data-country-select-label>{{ $toggleLabel }}</span>
        <i class="fas fa-chevron-down elx-country-select-custom__icon" aria-hidden="true"></i>
    </button>

    <ul class="elx-country-select-custom__menu" data-country-select-menu hidden>
        <li>
            <button
                type="button"
                class="elx-country-select-custom__option {{ $selected === '' ? 'is-active' : '' }}"
                data-country-value=""
                data-country-flag=""
                data-country-label="{{ $placeholderText }}"
            >
                <span>{{ $placeholderText }}</span>
            </button>
        </li>
        @foreach($countries as $code => $meta)
            <li>
                <button
                    type="button"
                    class="elx-country-select-custom__option {{ $selected === $code ? 'is-active' : '' }}"
                    data-country-value="{{ $code }}"
                    data-country-flag="{{ $meta['flag'] }}"
                    data-country-label="{{ $meta['label'] }}"
                >
                    <img src="{{ $meta['flag'] }}" alt="">
                    <span>{{ $meta['label'] }}</span>
                </button>
            </li>
        @endforeach
    </ul>
</div>

<style>
    .dxn-country-select-custom .elx-country-select-custom__toggle {
        justify-content: flex-start;
        gap: 0.55rem;
        padding: 0.85rem 1.1rem;
        border-radius: 14px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        background: rgba(255, 255, 255, 0.04);
        color: var(--elx-white, #fff);
        font-family: inherit;
    }
    .dxn-country-select-custom .elx-country-select-custom__menu {
        z-index: 40;
    }
</style>
