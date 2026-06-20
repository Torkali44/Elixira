@props([
    'countryCode' => '+966',
    'phone' => '',
    'inputClass' => 'form-input',
    'placeholder' => '',
    'required' => true,
    'inputStyle' => '',
])

@php
    $pricingService = app(\App\Support\ItemPricingService::class);
    $phoneCountryOptions = [
        '+966' => ['flag' => $pricingService->countryFlag('KSA'), 'label' => __('shop.country_ksa')],
        '+971' => ['flag' => $pricingService->countryFlag('UAE'), 'label' => __('shop.country_uae')],
    ];
    $selectedCode = old('phone_country_code', $countryCode);
    $selectedOption = $phoneCountryOptions[$selectedCode] ?? $phoneCountryOptions['+966'];
    $phoneValue = old('phone', $phone);
@endphp

@once
    @push('head')
    <style>
        .elx-phone-input__row {
            display: flex;
            gap: 0.75rem;
            align-items: stretch;
        }
        .elx-phone-code-picker {
            position: relative;
            flex-shrink: 0;
        }
        .elx-phone-code-picker__trigger {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 118px;
            height: 100%;
            min-height: 48px;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            border: 1px solid var(--elx-border, rgba(255, 255, 255, 0.12));
            background: rgba(255, 255, 255, 0.05);
            color: var(--elx-white, #fff);
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .elx-phone-code-picker.is-open .elx-phone-code-picker__trigger,
        .elx-phone-code-picker__trigger:hover {
            border-color: var(--elx-cyan, #4ac8f6);
        }
        .elx-phone-code-picker.is-open .elx-phone-code-picker__trigger {
            box-shadow: 0 0 0 2px rgba(74, 200, 246, 0.12);
        }
        .elx-phone-code-picker__trigger img {
            width: 24px;
            height: 16px;
            border-radius: 2px;
            object-fit: cover;
            flex-shrink: 0;
        }
        .elx-phone-code-picker__trigger-code {
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1;
        }
        .elx-phone-code-picker__chevron {
            margin-inline-start: auto;
            color: var(--elx-cyan, #4ac8f6);
            font-size: 0.7rem;
            transition: transform 0.2s ease;
        }
        .elx-phone-code-picker.is-open .elx-phone-code-picker__chevron {
            transform: rotate(180deg);
        }
        .elx-phone-code-picker__menu {
            display: none;
            position: absolute;
            top: calc(100% + 0.45rem);
            inset-inline-start: 0;
            z-index: 40;
            min-width: 230px;
            padding: 0.35rem;
            border-radius: 12px;
            border: 1px solid rgba(74, 200, 246, 0.25);
            background: #13252d;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
        }
        .elx-phone-code-picker.is-open .elx-phone-code-picker__menu {
            display: block;
        }
        .elx-phone-code-picker__option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.7rem 0.75rem;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: #fff;
            text-align: start;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .elx-phone-code-picker__option:hover,
        .elx-phone-code-picker__option.is-selected {
            background: rgba(74, 200, 246, 0.12);
        }
        .elx-phone-code-picker__option img {
            width: 24px;
            height: 16px;
            border-radius: 2px;
            object-fit: cover;
            flex-shrink: 0;
        }
        .elx-phone-code-picker__option-meta {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
            line-height: 1.2;
        }
        .elx-phone-code-picker__option-code {
            font-size: 0.95rem;
            font-weight: 700;
            color: #fff;
        }
        .elx-phone-code-picker__option-label {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.65);
        }
        .elx-phone-input__number {
            flex: 1;
            min-width: 0;
        }
        .elx-phone-input .vendor-input.elx-phone-input__number,
        .elx-phone-input .form-input.elx-phone-input__number {
            margin-bottom: 0;
        }
    </style>
    @endpush
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-elx-phone-picker]').forEach((picker) => {
                const trigger = picker.querySelector('[data-picker-trigger]');
                const hidden = picker.querySelector('input[name="phone_country_code"]');
                const triggerFlag = picker.querySelector('[data-picker-flag]');
                const triggerCode = picker.querySelector('[data-picker-code]');

                trigger?.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();

                    document.querySelectorAll('[data-elx-phone-picker].is-open').forEach((openPicker) => {
                        if (openPicker !== picker) {
                            openPicker.classList.remove('is-open');
                        }
                    });

                    picker.classList.toggle('is-open');
                });

                picker.querySelectorAll('[data-picker-option]').forEach((option) => {
                    option.addEventListener('click', (event) => {
                        event.preventDefault();

                        const code = option.dataset.code;
                        const flag = option.dataset.flag;

                        hidden.value = code;
                        triggerFlag.src = flag;
                        triggerCode.textContent = code;

                        picker.querySelectorAll('[data-picker-option]').forEach((item) => {
                            item.classList.toggle('is-selected', item === option);
                        });

                        picker.classList.remove('is-open');
                    });
                });
            });

            document.addEventListener('click', (event) => {
                if (! event.target.closest('[data-elx-phone-picker]')) {
                    document.querySelectorAll('[data-elx-phone-picker].is-open').forEach((picker) => {
                        picker.classList.remove('is-open');
                    });
                }
            });
        });
    </script>
    @endpush
@endonce

<div class="elx-phone-input">
    <div class="elx-phone-input__row">
        <div class="elx-phone-code-picker" data-elx-phone-picker>
            <button type="button" class="elx-phone-code-picker__trigger" data-picker-trigger aria-haspopup="listbox" aria-expanded="false">
                @if($selectedOption['flag'])
                    <img src="{{ $selectedOption['flag'] }}" alt="" data-picker-flag>
                @endif
                <span class="elx-phone-code-picker__trigger-code" data-picker-code>{{ $selectedCode }}</span>
                <i class="fas fa-chevron-down elx-phone-code-picker__chevron" aria-hidden="true"></i>
            </button>
            <input type="hidden" name="phone_country_code" value="{{ $selectedCode }}">
            <div class="elx-phone-code-picker__menu" role="listbox">
                @foreach($phoneCountryOptions as $code => $option)
                    <button
                        type="button"
                        class="elx-phone-code-picker__option {{ $selectedCode === $code ? 'is-selected' : '' }}"
                        data-picker-option
                        data-code="{{ $code }}"
                        data-flag="{{ $option['flag'] }}"
                        role="option"
                    >
                        @if($option['flag'])
                            <img src="{{ $option['flag'] }}" alt="">
                        @endif
                        <span class="elx-phone-code-picker__option-meta">
                            <span class="elx-phone-code-picker__option-code">{{ $code }}</span>
                            <span class="elx-phone-code-picker__option-label">{{ $option['label'] }}</span>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
        <input
            type="text"
            name="phone"
            class="{{ $inputClass }} elx-phone-input__number"
            value="{{ $phoneValue }}"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif
            @if($inputStyle) style="{{ $inputStyle }}" @endif
        >
    </div>
</div>
