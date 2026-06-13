@props(['inline' => false])

<button type="button"
    class="theme-toggle {{ $inline ? 'theme-toggle--inline' : '' }}"
    id="themeToggle"
    title="{{ __('app.theme_toggle') }}"
    aria-label="{{ __('app.theme_toggle') }}"
    data-theme-light-url="{{ route('theme.switch', 'light') }}"
    data-theme-dark-url="{{ route('theme.switch', 'dark') }}"
    data-current-theme="{{ $userTheme ?? 'dark' }}">
    <i class="fas fa-{{ ($userTheme ?? 'dark') === 'light' ? 'sun' : 'moon' }}"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) {
        return;
    }

    const body = document.body;

    function applyTheme(theme) {
        if (theme === 'light') {
            body.classList.add('light-mode');
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        } else {
            body.classList.remove('light-mode');
            themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        }
        themeToggle.dataset.currentTheme = theme;
    }

    applyTheme(themeToggle.dataset.currentTheme || 'dark');

    themeToggle.addEventListener('click', function () {
        const nextTheme = body.classList.contains('light-mode') ? 'dark' : 'light';
        const url = nextTheme === 'light'
            ? themeToggle.dataset.themeLightUrl
            : themeToggle.dataset.themeDarkUrl;

        fetch(url, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        }).finally(function () {
            applyTheme(nextTheme);
            localStorage.setItem('theme', nextTheme);
        });
    });
});
</script>

<style>
.theme-toggle {
    background: var(--primary-color, #b7d7d0);
    color: var(--secondary-color, #13252d);
    border: none;
    border-radius: 50%;
    width: 42px;
    height: 42px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    box-shadow: 0 2px 10px var(--theme-shadow, rgba(0,0,0,.3));
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.theme-toggle--inline {
    position: static;
}

.theme-toggle:hover {
    transform: scale(1.05);
}
</style>
