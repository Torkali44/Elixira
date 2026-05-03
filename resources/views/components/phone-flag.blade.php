@props([
    'phone' => '',
    'showPhone' => true,
])

@php
    $phoneRaw = trim((string) $phone);
    $digitsOnly = preg_replace('/\D+/', '', $phoneRaw) ?: '';
    $flag = null;
    $alt = '';
    if (str_starts_with($phoneRaw, '+971') || str_starts_with($digitsOnly, '971')) {
        $flag = 'images/AE.png';
        $alt = 'United Arab Emirates';
    } elseif (str_starts_with($phoneRaw, '+966') || str_starts_with($digitsOnly, '966')) {
        $flag = 'images/sa.png';
        $alt = 'Saudi Arabia';
    }
@endphp

@if($flag)
    <span style="display: inline-flex; align-items: center; gap: 0.4rem;">
        <img src="{{ asset($flag) }}" alt="{{ $alt }}" width="22" height="16" style="flex-shrink: 0; object-fit: cover; border-radius: 2px; box-shadow: 0 0 0 1px rgba(0,0,0,.08);">
        @if($showPhone && $phoneRaw !== '')
            <span>{{ $phoneRaw }}</span>
        @endif
    </span>
@else
    @if($showPhone)
        {{ $phoneRaw !== '' ? $phoneRaw : '—' }}
    @else
        <span style="opacity: 0.55;">—</span>
    @endif
@endif
