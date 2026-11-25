@props([
    'href' => null,
    'variant' => 'primary', // primary | brand | outline | light | outline-light
    'icon' => null,
    'iconFlip' => true,
    'type' => 'button',
])
@php
    $classes = 'btn btn-'.$variant;
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        <span>{{ $slot }}</span>
        @if ($icon)<x-ui.icon :name="$icon" :size="18" :flip="$iconFlip" />@endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        <span>{{ $slot }}</span>
        @if ($icon)<x-ui.icon :name="$icon" :size="18" :flip="$iconFlip" />@endif
    </button>
@endif
