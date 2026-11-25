@props([
    'project',
    'ratio' => '4/3',
    'sizes' => '(min-width: 1024px) 50vw, 100vw',
    'eager' => false,
    'headingLevel' => 'h3',
])
@use('App\Support\Localized')
@php
    $name = Localized::get($project, 'name', config('site.name'));
    $cover = Localized::media($project->thumbnail ?: (($project->images ?? [])[0] ?? null));
    $category = Localized::first($project, ['category', 'project_type', 'engineering_function']);
    $location = Localized::get($project, 'location');
    $url = route('project', $project->id);
@endphp
<article {{ $attributes->class('group relative') }}>
    <div class="overflow-hidden bg-stone" style="aspect-ratio: {{ $ratio }}">
        <x-ui.image :src="$cover" :alt="$name" :sizes="$sizes" :eager="$eager"
            class="transition-transform duration-700 ease-architect group-hover:scale-[1.02] motion-reduce:transition-none" />
    </div>
    <div class="mt-5 grid gap-1">
        <{{ $headingLevel }} class="font-display text-xl font-normal">
            {{-- Stretched link: whole card is clickable, one tab stop --}}
            <a href="{{ $url }}" class="after:absolute after:inset-0 group-hover:underline decoration-1 underline-offset-[0.25em]">{{ $name }}</a>
        </{{ $headingLevel }}>
        @if ($category || $location)
            <p class="text-sm text-charcoal-soft">
                {{ $category }}@if ($category && $location), @endif{{ $location }}
            </p>
        @endif
        <x-ui.dimension :value="$project->area" class="mt-3 max-w-64 text-sm text-slate-deep" />
    </div>
</article>
