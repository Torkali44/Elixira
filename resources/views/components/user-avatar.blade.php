@props([
    'user' => null,
    'size' => 44,
])

@php
    $sizeValue = is_numeric($size) ? $size . 'px' : $size;
    $fontSize = is_numeric($size) ? max(12, (int) round($size / 2.5)) . 'px' : '1rem';
    $name = $user?->name ?? 'User';
@endphp

<div {{ $attributes->merge([
    'style' => 'width:' . $sizeValue . ';height:' . $sizeValue . ';border-radius:50%;overflow:hidden;display:inline-flex;align-items:center;justify-content:center;background:linear-gradient(135deg, rgb(74, 200, 246), rgb(183, 215, 208));color:#0a1a22;font-weight:700;font-size:' . $fontSize . ';flex-shrink:0;',
]) }}>
    @if($user?->avatar_url)
        <img src="{{ $user->avatar_url }}" alt="{{ $name }}" style="width:100%;height:100%;object-fit:cover;display:block;">
    @else
        <span>{{ $user?->avatar_initials ?? 'U' }}</span>
    @endif
</div>
