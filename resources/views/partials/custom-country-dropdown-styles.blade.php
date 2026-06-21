@once
<style>
    .menu-country-select__label {
        display: block;
        margin-bottom: 0.75rem;
        color: var(--elx-light);
        font-size: 0.9rem;
        font-weight: 600;
        text-align: center;
    }
    .menu-country-select__flag {
        width: 28px;
        height: 18px;
        border-radius: 3px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .custom-dropdown {
        position: relative;
        text-align: start;
        cursor: pointer;
        user-select: none;
    }
    .custom-dropdown__trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.65rem 1.25rem;
        border-radius: 50px;
        background: var(--elx-glass);
        border: 1px solid var(--elx-border);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: var(--elx-white);
        font-family: 'Istok Web', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        touch-action: manipulation;
        -webkit-tap-highlight-color: transparent;
    }
    .custom-dropdown__trigger:hover,
    .custom-dropdown.open .custom-dropdown__trigger {
        border-color: rgba(74, 200, 246, 0.5);
        box-shadow: 0 0 10px rgba(74, 200, 246, 0.15);
    }
    .custom-dropdown__options {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        inset-inline-start: 0;
        inset-inline-end: 0;
        background: #13252d;
        border: 1px solid rgba(74, 200, 246, 0.3);
        border-radius: 16px;
        overflow: hidden;
        z-index: 1000;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }
    .custom-dropdown.open .custom-dropdown__options {
        display: block;
    }
    .custom-dropdown__option {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.25rem;
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
        transition: background 0.2s, color 0.2s;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        touch-action: manipulation;
    }
    .custom-dropdown__option:last-child {
        border-bottom: none;
    }
    .custom-dropdown__option:hover,
    .custom-dropdown__option.active {
        background: rgba(74, 200, 246, 0.12);
        color: #4ac8f6;
    }
    .custom-dropdown.open .dropdown-arrow-icon {
        transform: rotate(180deg);
    }
    .dropdown-arrow-icon {
        transition: transform 0.3s ease;
    }
</style>
@endonce
