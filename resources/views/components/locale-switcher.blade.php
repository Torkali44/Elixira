@php
    $query = request()->query();
@endphp
<div class="locale-switcher d-flex align-items-center gap-1">
    <a href="{{ route('lang.switch', 'en').(empty($query) ? '' : '?'.http_build_query($query)) }}"
        class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-secondary' }}"
        title="{{ __('app.english') }}">EN</a>
    <a href="{{ route('lang.switch', 'ar').(empty($query) ? '' : '?'.http_build_query($query)) }}"
        class="btn btn-sm {{ app()->getLocale() === 'ar' ? 'btn-primary' : 'btn-outline-secondary' }}"
        title="{{ __('app.arabic') }}">ع</a>
</div>
