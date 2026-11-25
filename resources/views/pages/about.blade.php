{{--
    About / company profile. Data from SiteController@about.
    Content blocks come from Dashboard → About sections, by section_key:
      about | main  → company story (title, content, main_image, images)
      vision, mission, expertise, methodology → shown when they exist
    Nothing is shown for blocks the admin has not created.
--}}
@use('App\Support\Localized')
@php
    $story = $sections['main'] ?? $sections['about'] ?? null;
    $vision = $sections['vision'] ?? null;
    $mission = $sections['mission'] ?? null;
    $extra = collect(['expertise', 'methodology'])->map(fn ($k) => $sections[$k] ?? null)->filter();
    $storyImages = array_values(array_filter((array) ($story->images ?? [])));
    $areas = config('site.areas.'.app()->getLocale(), []);
    $profile = config('site.company_profile_pdf');
@endphp
<x-layouts.site :title="__('site.seo.about_title')" :description="Localized::paragraphs(Localized::get($story, 'content'))[0] ?? __('site.seo.about_description')" :image="Localized::media($story->main_image ?? null)">

    {{-- Story --}}
    <section class="shell grid gap-12 pt-14 pb-20 md:pt-24 md:pb-32 lg:grid-cols-12 lg:gap-8" aria-labelledby="story-title">
        <div class="lg:col-span-6">
            <h1 id="story-title" class="display-xl">{{ Localized::get($story, 'title', config('site.name')) }}</h1>
            <div class="measure mt-10 space-y-5 text-lg text-charcoal-soft">
                @foreach (Localized::paragraphs(Localized::get($story, 'content')) as $p)
                    <p>{{ $p }}</p>
                @endforeach
            </div>
            @if ($profile)
                <x-ui.button :href="str_starts_with($profile, 'http') ? $profile : asset($profile)" variant="outline" icon="download" :icon-flip="false" class="mt-10" download>{{ __('site.cta.profile') }}</x-ui.button>
            @endif
        </div>
        @if ($story?->main_image)
            <div class="lg:col-span-5 lg:col-start-8">
                <x-ui.image :src="Localized::media($story->main_image)" alt="" ratio="auto" eager sizes="(min-width: 1024px) 42vw, 100vw" />
            </div>
        @endif
    </section>

    @if ($storyImages)
        <div class="shell grid grid-cols-2 items-start gap-3 pb-20 md:grid-cols-3 md:gap-4 md:pb-32">
            @foreach (array_slice($storyImages, 0, 3) as $img)
                <x-ui.image :src="Localized::media($img)" alt="" ratio="auto" sizes="33vw" />
            @endforeach
        </div>
    @endif

    {{-- Vision & mission --}}
    @if ($vision || $mission)
        <section class="border-y border-stone bg-white" aria-label="{{ __('site.about.vision') }} / {{ __('site.about.mission') }}">
            <div class="shell grid md:grid-cols-2">
                @foreach (array_filter(['vision' => $vision, 'mission' => $mission]) as $key => $block)
                    <div class="border-stone py-14 md:py-20 md:px-10 md:first:ps-0 md:nth-2:border-s">
                        <h2 class="text-sm font-medium text-slate-deep">{{ Localized::get($block, 'title', __('site.about.'.$key)) }}</h2>
                        <div class="mt-5 space-y-4 font-display text-xl font-light leading-snug md:text-2xl">
                            @foreach (Localized::paragraphs(Localized::get($block, 'content')) as $p)
                                <p>{{ $p }}</p>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Why MK Group --}}
    <section class="py-20 md:py-32" aria-labelledby="why-title">
        <div class="shell grid gap-12 lg:grid-cols-12">
            <h2 id="why-title" class="display-lg lg:col-span-4">{{ __('site.why.title') }}</h2>
            <ul class="grid gap-10 md:grid-cols-3 lg:col-span-8">
                @foreach (__('site.why.items') as $item)
                    <li class="border-t border-charcoal pt-6">
                        <h3 class="font-display text-lg">{{ $item['title'] }}</h3>
                        <p class="mt-3 text-charcoal-soft">{{ $item['text'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Optional admin blocks: expertise, methodology --}}
    @foreach ($extra as $key => $block)
        <section class="border-t border-stone py-16 md:py-24">
            <div class="shell grid gap-8 lg:grid-cols-12">
                <h2 class="display-md lg:col-span-4">{{ Localized::get($block, 'title') }}</h2>
                <div class="measure space-y-4 text-lg text-charcoal-soft lg:col-span-7 lg:col-start-6">
                    @foreach (Localized::paragraphs(Localized::get($block, 'content')) as $p)
                        <p>{{ $p }}</p>
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach

    {{-- Services summary --}}
    @if ($services->isNotEmpty())
        <section class="border-t border-stone bg-white py-20 md:py-28" aria-labelledby="about-services-title">
            <div class="shell grid gap-10 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <h2 id="about-services-title" class="display-md">{{ __('site.home.services_title') }}</h2>
                    <a href="{{ route('services') }}" class="link mt-6 inline-block">{{ __('site.home.more_services') }}</a>
                </div>
                <ul class="grid gap-x-10 sm:grid-cols-2 lg:col-span-8">
                    @foreach ($services as $service)
                        <li class="border-b border-stone py-5 font-display text-lg">
                            <a href="{{ route('services') }}#{{ $service->slug ?: 'service-'.$service->id }}" class="hover:underline">{{ Localized::get($service, 'name') }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @include('partials.site.process')
    @include('partials.site.stats', ['stats' => $stats])

    {{-- Areas served --}}
    @if ($areas)
        <section class="py-16 md:py-24" aria-labelledby="areas-title">
            <div class="shell flex flex-wrap items-baseline gap-x-12 gap-y-6">
                <h2 id="areas-title" class="text-sm font-medium text-charcoal-soft">{{ __('site.about.areas') }}</h2>
                <ul class="flex flex-wrap gap-x-10 gap-y-2">
                    @foreach ($areas as $area)
                        <li class="display-md text-slate">{{ $area }}</li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <x-site.contact-band />
</x-layouts.site>
