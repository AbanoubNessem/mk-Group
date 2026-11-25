{{-- Project details. Data from HomeController@project (unchanged): $project, or nothing when no id is given. --}}
@use('App\Support\Localized')
@use('App\Http\Controllers\SiteController')
@php
    if (isset($project)) {
        $name = Localized::get($project, 'name', config('site.name'));
        $description = Localized::get($project, 'description');
        $category = Localized::first($project, ['category', 'project_type', 'engineering_function']);
        $location = Localized::get($project, 'location');

        $images = array_values(array_filter((array) ($project->images ?? [])));
        $leadPath = $project->thumbnail ?: ($images[0] ?? null);
        $lead = Localized::media($leadPath);
        // Gallery: every project photo, without repeating the lead image
        $gallery = array_values(array_filter($images, fn ($p) => $p !== $leadPath));
        $allPhotos = $leadPath ? array_merge([$leadPath], $gallery) : $gallery;

        // Optional media — rendered only once these fields exist and are filled
        $before = Localized::media($project->before_image ?? null);
        $after = Localized::media($project->after_image ?? null);
        $video = $project->video_url ?? null;

        // Related: same category first, then the rest (active only)
        $key = SiteController::categoryOf($project)['key'] ?? null;
        $related = \App\Models\Project::active()->ordered()->where('id', '!=', $project->id)->get()
            ->sortBy(fn ($p) => (SiteController::categoryOf($p)['key'] ?? null) === $key ? 0 : 1)
            ->take(3)->values();

        $schema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $name,
            'description' => $description,
            'image' => $lead,
            'url' => url()->current(),
            'creator' => ['@type' => 'Organization', 'name' => config('site.name')],
            'locationCreated' => $location ? ['@type' => 'Place', 'name' => $location] : null,
            'dateModified' => optional($project->updated_at)->toIso8601String(),
        ]);
    }
@endphp

@isset($project)
<x-layouts.site :title="$name" :description="$description ?: ($category ? $category.($location ? ', '.$location : '') : null)" :image="$lead" type="article">
    <x-slot:head>
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
    </x-slot:head>

    {{-- Title --}}
    <header class="shell pt-10 pb-10 md:pt-16 md:pb-14">
        <a href="{{ route('projects') }}" class="inline-flex min-h-11 items-center gap-2 text-sm text-charcoal-soft hover:text-charcoal">
            <x-ui.icon name="chevron" :size="16" class="rotate-180 rtl:rotate-0" />
            {{ __('site.projects.back') }}
        </a>
        <div class="mt-6 grid gap-6 lg:grid-cols-12 lg:items-end">
            <h1 class="display-xl lg:col-span-8">{{ $name }}</h1>
            @if ($category || $location)
                <p class="text-lg text-charcoal-soft lg:col-span-4 lg:text-end">
                    {{ $category }}@if ($category && $location)<br class="hidden lg:block"><span class="lg:hidden">, </span>@endif{{ $location }}
                </p>
            @endif
        </div>
    </header>

    {{-- Lead photograph --}}
    @if ($lead)
        <div class="shell" data-gallery>
            <button type="button" class="group relative block w-full cursor-zoom-in overflow-hidden bg-stone" data-gallery-item data-index="0"
                data-src="{{ $lead }}" data-alt="{{ $name }}" aria-label="{{ __('site.project.open_gallery', ['n' => 1, 'total' => count($allPhotos)]) }}">
                <img src="{{ $lead }}" alt="{{ $name }}" class="aspect-[4/3] w-full object-cover md:aspect-[21/10]" fetchpriority="high" decoding="async">
                @if (count($allPhotos) > 1)
                    <span class="absolute end-4 bottom-4 inline-flex items-center gap-2 bg-white px-4 py-2.5 text-sm text-charcoal">
                        <x-ui.icon name="expand" :size="16" />
                        {{ __('site.project.view_all', ['count' => count($allPhotos)]) }}
                    </span>
                @endif
            </button>
            {{-- Hidden siblings so the lead opens the full sequence in the viewer --}}
            @foreach ($gallery as $i => $path)
                <span hidden data-gallery-item data-index="{{ $i + 1 }}" data-src="{{ Localized::media($path) }}" data-alt="{{ $name }} — {{ $i + 2 }} / {{ count($allPhotos) }}"></span>
            @endforeach
        </div>
    @endif

    {{-- Facts and description --}}
    <section class="shell grid gap-12 py-16 md:py-24 lg:grid-cols-12" aria-labelledby="facts-title">
        <div @class(['lg:col-span-5' => $description, 'lg:col-span-12' => ! $description])>
            <h2 id="facts-title" class="mb-6 font-display text-xl">{{ __('site.project.facts') }}</h2>
            <x-project.facts :project="$project" :columns="$description ? 1 : 3" />
        </div>
        @if ($description)
            <div class="measure space-y-5 text-lg text-charcoal-soft lg:col-span-6 lg:col-start-7 lg:pt-14">
                @foreach (Localized::paragraphs($description) as $p)
                    <p>{{ $p }}</p>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Before / after --}}
    @if ($before && $after)
        <section class="shell pb-16 md:pb-24" aria-labelledby="compare-title">
            <h2 id="compare-title" class="mb-8 display-md">{{ __('site.project.before_after') }}</h2>
            <x-project.compare :before="$before" :after="$after" :name="$name" />
        </section>
    @endif

    {{-- Video --}}
    @if ($video)
        <section class="shell pb-16 md:pb-24" aria-labelledby="video-title">
            <h2 id="video-title" class="mb-8 display-md">{{ __('site.project.video') }}</h2>
            <x-project.video :url="$video" :title="$name" />
        </section>
    @endif

    {{-- Gallery --}}
    @if ($gallery)
        <section class="shell pb-24 md:pb-36" aria-labelledby="gallery-title">
            <div class="mb-8 flex items-baseline justify-between gap-4 border-b border-stone pb-6">
                <h2 id="gallery-title" class="display-md">{{ __('site.project.gallery') }}</h2>
                <p class="text-sm text-charcoal-soft">{{ trans_choice('site.projects.photos', count($gallery)) }}</p>
            </div>
            <x-project.gallery :images="$gallery" :name="$name" />
        </section>
    @endif

    {{-- Related --}}
    @if ($related->isNotEmpty())
        <section class="border-t border-stone bg-white py-20 md:py-28" aria-labelledby="related-title">
            <div class="shell">
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <h2 id="related-title" class="display-lg">{{ __('site.projects.related') }}</h2>
                    <a href="{{ route('projects') }}" class="link">{{ __('site.cta.all_projects') }}</a>
                </div>
                <div class="mt-12 grid gap-x-8 gap-y-14 md:grid-cols-3">
                    @foreach ($related as $item)
                        <x-project.card :project="$item" ratio="4/3" sizes="(min-width: 768px) 33vw, 100vw" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-site.contact-band :title="__('site.project.similar')" :text="__('site.project.similar_text')" />

    <x-project.lightbox />
</x-layouts.site>
@else
<x-layouts.site :title="__('site.projects.not_found')">
    <section class="shell py-32 md:py-44">
        <h1 class="display-lg">{{ __('site.projects.not_found') }}</h1>
        <x-ui.button :href="route('projects')" variant="primary" class="mt-10">{{ __('site.projects.back') }}</x-ui.button>
    </section>
</x-layouts.site>
@endisset
