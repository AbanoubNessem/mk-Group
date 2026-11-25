@props(['before', 'after', 'name' => ''])
{{-- Before/after slider. Renders only when both images exist. --}}
@if ($before && $after)
    <figure {{ $attributes }}>
        <div class="compare aspect-[16/10]" data-compare style="--pos: 50%">
            <img src="{{ $before }}" alt="{{ $name }} — {{ __('site.project.before') }}" loading="lazy" decoding="async">
            <div class="compare-after">
                <img src="{{ $after }}" alt="{{ $name }} — {{ __('site.project.after') }}" loading="lazy" decoding="async">
            </div>
            <input type="range" min="0" max="100" value="50" class="compare-range" aria-label="{{ __('site.project.compare_label') }}">
            <div class="compare-handle" aria-hidden="true">
                <span class="compare-grip"><x-ui.icon name="compare" :size="20" /></span>
            </div>
            <span class="compare-tag start-4" aria-hidden="true">{{ __('site.project.before') }}</span>
            <span class="compare-tag end-4" aria-hidden="true">{{ __('site.project.after') }}</span>
        </div>
    </figure>
@endif
