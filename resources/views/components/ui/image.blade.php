@props([
    'src',
    'alt' => '',
    'sizes' => '100vw',
    'eager' => false,
    'ratio' => null, // e.g. "4/3" — reserves space to avoid layout shift
    'fit' => 'cover',
])
{{-- Image with lazy loading, async decoding and an optional reserved aspect ratio. --}}
@if ($src)
    <img src="{{ $src }}" alt="{{ $alt }}" sizes="{{ $sizes }}"
        loading="{{ $eager ? 'eager' : 'lazy' }}" decoding="async"
        @if ($eager) fetchpriority="high" @endif
        @if ($ratio) style="aspect-ratio: {{ $ratio }}" @endif
        {{ $attributes->class(['block w-full bg-stone', 'object-cover' => $fit === 'cover', 'object-contain' => $fit === 'contain', 'h-full' => ! $ratio]) }}>
@else
    <div {{ $attributes->class('block h-full w-full bg-stone') }} @if ($ratio) style="aspect-ratio: {{ $ratio }}" @endif aria-hidden="true"></div>
@endif
