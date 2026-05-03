@props([
    'name' => 'country_code',
    'value' => '+966',
    'variant' => 'account',
])

@php
    $selected = in_array((string) $value, ['+966', '+971'], true) ? (string) $value : '+966';
    $options = [
        ['code' => '+966', 'flag' => asset('images/sa.png'), 'label' => 'Saudi Arabia'],
        ['code' => '+971', 'flag' => asset('images/AE.png'), 'label' => 'United Arab Emirates'],
    ];
    $current = collect($options)->firstWhere('code', $selected) ?? $options[0];
@endphp

@once
<style id="elx-country-code-picker-styles">
    .elx-cc-picker { position: relative; width: 100%; }
    .elx-cc-picker__trigger {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.4rem;
        width: 100%;
        margin: 0;
        cursor: pointer;
        font-family: var(--elx-font, 'Istok Web', sans-serif);
        color: var(--elx-white, #fff);
        outline: none;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }
    .elx-cc-picker__trigger:focus {
        border-color: var(--elx-cyan, #4ac8f6) !important;
        box-shadow: 0 0 0 3px rgba(74, 200, 246, 0.12);
    }
    .elx-cc-picker__trigger-inner {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        min-width: 0;
    }
    .elx-cc-picker__chev {
        font-size: 0.65rem;
        opacity: 0.65;
        flex-shrink: 0;
    }
    .elx-cc-picker--account .elx-cc-picker__trigger {
        padding: 0.95rem 1rem;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.09);
        background: rgba(255, 255, 255, 0.04);
    }
    .elx-cc-picker--cart .elx-cc-picker__trigger {
        padding: 0.8rem 1.2rem;
        border-radius: 100px;
        border: 1px solid var(--elx-border, rgba(30, 103, 130, 0.3));
        background: rgba(255, 255, 255, 0.05);
        margin-bottom: 0;
    }
    .elx-cc-picker__panel {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        z-index: 80;
        background: var(--elx-dark, #0a1a22);
        border: 1px solid var(--elx-border, rgba(30, 103, 130, 0.3));
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.4);
    }
    .elx-cc-picker__option {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        width: 100%;
        padding: 0.72rem 1rem;
        border: none;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        background: transparent;
        color: var(--elx-white, #fff);
        cursor: pointer;
        font-family: inherit;
        font-size: 0.95rem;
        text-align: left;
        transition: background 0.2s ease;
    }
    .elx-cc-picker__option:last-child { border-bottom: none; }
    .elx-cc-picker__option:hover { background: rgba(74, 200, 246, 0.12); }
    .elx-cc-picker__option small {
        margin-left: auto;
        opacity: 0.55;
        font-size: 0.72rem;
        font-weight: 500;
    }
</style>
@endonce

<div {{ $attributes->class(['elx-cc-picker', 'elx-cc-picker--' . $variant]) }} data-elx-country-picker>
    <input type="hidden" name="{{ $name }}" value="{{ $selected }}" data-elx-cc-input autocomplete="off">
    <button
        type="button"
        class="elx-cc-picker__trigger"
        data-elx-cc-trigger
        aria-haspopup="listbox"
        aria-expanded="false"
    >
        <span class="elx-cc-picker__trigger-inner">
            <img src="{{ $current['flag'] }}" alt="" width="22" height="16" data-elx-cc-flag style="object-fit: cover; border-radius: 2px; box-shadow: 0 0 0 1px rgba(0,0,0,.12); flex-shrink: 0;">
            <span data-elx-cc-code>{{ $selected }}</span>
        </span>
        <i class="fas fa-chevron-down elx-cc-picker__chev" aria-hidden="true"></i>
    </button>
    <div class="elx-cc-picker__panel" data-elx-cc-panel hidden role="listbox">
        @foreach($options as $opt)
            <button
                type="button"
                class="elx-cc-picker__option"
                data-elx-cc-option
                data-code="{{ $opt['code'] }}"
                data-flag="{{ $opt['flag'] }}"
                role="option"
            >
                <img src="{{ $opt['flag'] }}" alt="" width="22" height="16" style="object-fit: cover; border-radius: 2px; box-shadow: 0 0 0 1px rgba(0,0,0,.12); flex-shrink: 0;">
                <span>{{ $opt['code'] }}</span>
                <small>{{ $opt['label'] }}</small>
            </button>
        @endforeach
    </div>
</div>

@once
<script>
(function () {
    if (window.__elxCountryCodePicker) return;
    window.__elxCountryCodePicker = true;

    function closeAll() {
        document.querySelectorAll('[data-elx-country-picker]').forEach(function (root) {
            var panel = root.querySelector('[data-elx-cc-panel]');
            var trigger = root.querySelector('[data-elx-cc-trigger]');
            if (panel) panel.hidden = true;
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
        });
    }

    function bindRoot(root) {
        if (root.dataset.elxCcBound) return;
        root.dataset.elxCcBound = '1';

        var input = root.querySelector('[data-elx-cc-input]');
        var trigger = root.querySelector('[data-elx-cc-trigger]');
        var panel = root.querySelector('[data-elx-cc-panel]');
        var flagImg = root.querySelector('[data-elx-cc-flag]');
        var codeEl = root.querySelector('[data-elx-cc-code]');
        if (!input || !trigger || !panel || !flagImg || !codeEl) return;

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            var willOpen = panel.hidden;
            closeAll();
            if (willOpen) {
                panel.hidden = false;
                trigger.setAttribute('aria-expanded', 'true');
            }
        });

        root.querySelectorAll('[data-elx-cc-option]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                input.value = btn.getAttribute('data-code') || '+966';
                flagImg.src = btn.getAttribute('data-flag') || flagImg.src;
                codeEl.textContent = input.value;
                closeAll();
            });
        });
    }

    function init() {
        document.querySelectorAll('[data-elx-country-picker]').forEach(bindRoot);
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('[data-elx-country-picker]')) {
            closeAll();
        }
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endonce
