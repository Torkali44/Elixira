<button type="button"
    class="dashboard-theme-toggle"
    id="dashboardThemeToggle"
    title="{{ __('app.theme_toggle') }}"
    aria-label="{{ __('app.theme_toggle') }}"
    data-theme-light-url="{{ route('theme.switch', 'light') }}"
    data-theme-dark-url="{{ route('theme.switch', 'dark') }}"
    data-current-theme="{{ ($userTheme ?? 'light') === 'dark' ? 'dark' : 'light' }}">
    <i class="fas fa-{{ ($userTheme ?? 'light') === 'dark' ? 'sun' : 'moon' }}"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('dashboardThemeToggle');
    if (!toggle) {
        return;
    }

    function applyDashboardTheme(theme) {
        document.body.classList.toggle('dashboard-dark', theme === 'dark');
        toggle.innerHTML = theme === 'dark'
            ? '<i class="fas fa-sun"></i>'
            : '<i class="fas fa-moon"></i>';
        toggle.dataset.currentTheme = theme;
    }

    applyDashboardTheme(toggle.dataset.currentTheme || 'light');

    toggle.addEventListener('click', function () {
        const nextTheme = document.body.classList.contains('dashboard-dark') ? 'light' : 'dark';
        const url = nextTheme === 'light'
            ? toggle.dataset.themeLightUrl
            : toggle.dataset.themeDarkUrl;

        fetch(url, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        }).finally(function () {
            applyDashboardTheme(nextTheme);
            localStorage.setItem('dashboard-theme', nextTheme);
        });
    });
});
</script>
