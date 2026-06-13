<div class="language-selector-wrapper">
    <div class="btn-group" role="group" aria-label="Language selector">
        <a href="{{ route('lang.switch', 'en') }}" 
           class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary active' : 'btn-outline-secondary' }}" 
           title="Switch to English">
            <i class="fas fa-us-flag"></i> EN
        </a>
        <a href="{{ route('lang.switch', 'ar') }}" 
           class="btn btn-sm {{ app()->getLocale() === 'ar' ? 'btn-primary active' : 'btn-outline-secondary' }}" 
           title="التبديل إلى العربية">
            <i class="fas fa-sa-flag"></i> AR
        </a>
    </div>
</div>

<style>
.language-selector-wrapper {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 999;
}

.language-selector-wrapper .btn-group {
    display: flex;
    gap: 5px;
    background-color: var(--theme-bg-secondary);
    padding: 8px;
    border-radius: 8px;
    box-shadow: 0 2px 10px var(--theme-shadow);
}

.language-selector-wrapper .btn {
    padding: 6px 12px;
    font-size: 0.85rem;
    font-weight: 600;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.language-selector-wrapper .btn-primary {
    background-color: var(--primary-color) !important;
    color: var(--secondary-color) !important;
    border-color: var(--primary-color) !important;
}

.language-selector-wrapper .btn-outline-secondary {
    color: var(--theme-text) !important;
    border-color: var(--theme-border) !important;
    background-color: transparent;
}

.language-selector-wrapper .btn-outline-secondary:hover {
    background-color: var(--theme-hover-bg) !important;
    color: var(--primary-color) !important;
}

@media (max-width: 768px) {
    .language-selector-wrapper {
        top: auto;
        bottom: 20px;
        left: 20px;
    }
}
</style>
