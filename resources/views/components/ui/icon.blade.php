@props(['name', 'size' => 20, 'flip' => false])
{{-- Minimal line icon set, 1.5px stroke on a 24 grid. Decorative by default. --}}
@php
    $paths = [
        'arrow' => '<path d="M4 12h15M13 6l6 6-6 6"/>',
        'chevron' => '<path d="M9 5l7 7-7 7"/>',
        'close' => '<path d="M5 5l14 14M19 5L5 19"/>',
        'menu' => '<path d="M3 7h18M3 12h18M3 17h18"/>',
        'expand' => '<path d="M4 9V4h5M20 9V4h-5M4 15v5h5M20 15v5h-5"/>',
        'phone' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a1 1 0 01-1 1A16 16 0 014 5a1 1 0 011-1z"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="1"/><path d="M3 7l9 6 9-6"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'pin' => '<path d="M12 21s-7-6.2-7-11.5a7 7 0 0114 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'whatsapp' => '<path d="M4 20l1.3-3.9A8 8 0 1112 20a8 8 0 01-4.1-1.1L4 20z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 01-1.8-1.8l.8-1-1-2L9 9.5z"/>',
        'play' => '<path d="M8 5v14l11-7z"/>',
        'compare' => '<path d="M9 7l-5 5 5 5M15 7l5 5-5 5"/>',
        'download' => '<path d="M12 4v11M7 10l5 5 5-5M5 20h14"/>',
        'check' => '<path d="M5 12.5l4.5 4.5L19 7"/>',
        'alert' => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5v5.5M12 16.5v.01"/>',
    ];
@endphp
<svg {{ $attributes->class(['shrink-0', 'rtl-flip' => $flip]) }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">{!! $paths[$name] ?? '' !!}</svg>
