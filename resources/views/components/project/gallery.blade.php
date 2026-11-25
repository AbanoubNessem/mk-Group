@props(['images' => [], 'name' => ''])
@use('App\Support\Localized')
{{--
    Editorial gallery rhythm: a full-width frame, then a pair, repeating.
    Every photo opens the fullscreen viewer.
--}}
@php
    $images = array_values(array_filter((array) $images));
    $total = count($images);
@endphp
@if ($total)
    <div {{ $attributes->class('grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-4') }} data-gallery>
        @foreach ($images as $i => $path)
            @php
                // Rhythm of 3: one wide frame, then a pair. A photo left without a partner goes wide.
                $wide = $i % 3 === 0 || ($i % 3 === 1 && $i === $total - 1);
                $src = Localized::media($path);
                $alt = $name.' — '.($i + 1).' / '.$total;
            @endphp
            <button type="button" data-gallery-item data-index="{{ $i }}" data-src="{{ $src }}" data-alt="{{ $alt }}"
                @class(['group relative block cursor-zoom-in overflow-hidden bg-stone text-start', 'md:col-span-2' => $wide])
                aria-label="{{ __('site.project.open_gallery', ['n' => $i + 1, 'total' => $total]) }}">
                <x-ui.image :src="$src" :alt="$alt" :ratio="$wide ? '16/9' : '4/3'"
                    :sizes="$wide ? '100vw' : '(min-width: 768px) 50vw, 100vw'"
                    class="transition-transform duration-700 ease-architect group-hover:scale-[1.015] motion-reduce:transition-none" />
                <span class="absolute end-3 bottom-3 grid size-10 place-items-center bg-white/90 text-charcoal opacity-0 transition-opacity group-hover:opacity-100 group-focus-visible:opacity-100" aria-hidden="true">
                    <x-ui.icon name="expand" :size="18" />
                </span>
            </button>
        @endforeach
    </div>
@endif
