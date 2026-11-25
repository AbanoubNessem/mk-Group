@props(['url' => null, 'title' => ''])
{{-- YouTube / Vimeo link → privacy-friendly embed; direct file → <video>. Renders nothing without a URL. --}}
@php
    $embed = null;
    $file = null;
    if (filled($url)) {
        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([\w-]{11})~', $url, $m)) {
            $embed = 'https://www.youtube-nocookie.com/embed/'.$m[1].'?rel=0';
        } elseif (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            $embed = 'https://player.vimeo.com/video/'.$m[1].'?dnt=1';
        } elseif (preg_match('~\.(mp4|webm)(\?.*)?$~i', $url)) {
            $file = str_starts_with($url, 'http') ? $url : asset('storage/'.ltrim($url, '/'));
        }
    }
@endphp
@if ($embed || $file)
    <div {{ $attributes->class('video-frame') }}>
        @if ($embed)
            <iframe src="{{ $embed }}" title="{{ $title }}" loading="lazy"
                allow="accelerometer; encrypted-media; gyroscope; picture-in-picture; fullscreen" allowfullscreen></iframe>
        @else
            <video controls preload="metadata" playsinline>
                <source src="{{ $file }}">
            </video>
        @endif
    </div>
@endif
