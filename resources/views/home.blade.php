{{--
    Home page. Data from HomeController@index (unchanged):
    $homeSections, $services, $aboutSections, $projects, $heroSlides, $countries, $contactInfo, $stats
--}}
@use('App\Support\Localized')
@php
    $slide = ($heroSlides ?? collect())->first();
    $heroSection = $homeSections['hero'] ?? $homeSections['home'] ?? null;

    if ($slide) {
        $heroTitle = Localized::get($slide, 'title');
        $heroText = Localized::get($slide, 'subtitle');
        $heroImage = Localized::media($slide->image);
        $heroButton = Localized::get($slide, 'button_text');
        $heroLink = Localized::link($slide->button_link, route('contact'));
    } else {
        $heroTitle = Localized::get($heroSection, 'title');
        $heroText = Localized::get($heroSection, 'content');
        $heroImage = Localized::media($heroSection->background_image ?? (($heroSection->images ?? [])[0] ?? null));
        $heroButton = Localized::get($heroSection, 'button_text');
        $heroLink = Localized::link($heroSection->button_link ?? null, route('contact'));
    }
    $heroTitle = $heroTitle ?: __('site.home.hero_fallback_title');
    $heroLines = Localized::paragraphs($heroText ?: __('site.home.hero_fallback_text'));

    $about = $aboutSections['main'] ?? $aboutSections['about'] ?? null;
    $featured = ($projects ?? collect())->take(6);
    $servicesIntro = $homeSections['services'] ?? null;
@endphp

<x-layouts.site :image="$heroImage" :firebase="true">
    {{-- Company schema (GeneralContractor) is in the site layout, on every page --}}

    {{-- ============================================================ Hero --}}
    <section class="relative overflow-hidden" aria-labelledby="hero-title">
        <div class="lg:grid lg:min-h-[calc(100dvh-5.5rem)] lg:grid-cols-12">
            {{-- Photograph: bleeds to the inline-end edge on desktop --}}
            <div class="relative aspect-[4/5] sm:aspect-[16/11] lg:order-2 lg:col-span-7 lg:aspect-auto">
                @if ($heroImage)
                    <img src="{{ $heroImage }}" alt="{{ $heroTitle }} — {{ config('site.name') }}" class="reveal-image absolute inset-0 h-full w-full object-cover" fetchpriority="high" decoding="async">
                @else
                    <div class="absolute inset-0 bg-slate"></div>
                @endif
            </div>

            {{-- Statement --}}
            <div class="shell flex flex-col justify-end py-12 lg:order-1 lg:col-span-5 lg:max-w-none lg:py-20 lg:pe-12 xl:pe-16">
                <span class="reveal-line mb-10 block h-px w-24 bg-slate" aria-hidden="true"></span>
                <h1 id="hero-title" class="display-xl reveal-text">{{ $heroTitle }}</h1>
                <div class="reveal-text mt-8 max-w-md space-y-1 text-lg text-charcoal-soft">
                    @foreach ($heroLines as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>
                <div class="reveal-text mt-10 flex flex-wrap gap-3">
                    <x-ui.button :href="$heroButton ? $heroLink : route('contact')" variant="primary">{{ $heroButton ?: __('site.cta.consult') }}</x-ui.button>
                    <x-ui.button :href="route('projects')" variant="outline">{{ __('site.cta.view_projects') }}</x-ui.button>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ Selected projects --}}
    @if ($featured->isNotEmpty())
        <section id="portfolio" class="py-20 md:py-32" aria-labelledby="projects-title">
            <div class="shell">
                <div class="flex flex-wrap items-end justify-between gap-6 border-b border-stone pb-8">
                    <div>
                        <h2 id="projects-title" class="display-lg">{{ __('site.home.projects_title') }}</h2>
                        <p class="mt-3 text-charcoal-soft">{{ __('site.home.projects_text') }}</p>
                    </div>
                    <a href="{{ route('projects') }}" class="link">{{ __('site.cta.all_projects') }}</a>
                </div>

                {{-- Asymmetric pairs: wide + narrow, alternating, narrow column offset down --}}
                <div class="mt-14 grid gap-x-8 gap-y-16 md:grid-cols-12 md:gap-y-24">
                    @foreach ($featured as $i => $project)
                        @php
                            $pair = intdiv($i, 2) % 2;
                            $first = $i % 2 === 0;
                            $wide = $first === ($pair === 0);
                        @endphp
                        <x-project.card :project="$project"
                            :ratio="$wide ? '5/4' : '4/5'"
                            :sizes="$wide ? '(min-width: 768px) 58vw, 100vw' : '(min-width: 768px) 42vw, 100vw'"
                            :class="\Illuminate\Support\Arr::toCssClasses([
                                'md:col-span-7' => $wide,
                                'md:col-span-5' => ! $wide,
                                'md:mt-32' => ! $first,
                            ])" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================ Services --}}
    @if ($services->isNotEmpty())
        <section id="services" class="border-t border-stone bg-white py-20 md:py-32" aria-labelledby="services-title">
            <div class="shell grid gap-12 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <div class="lg:sticky lg:top-32">
                        <h2 id="services-title" class="display-lg">{{ Localized::get($servicesIntro, 'title', __('site.home.services_title')) }}</h2>
                        <p class="mt-5 max-w-sm text-charcoal-soft">{{ __('site.services.intro') }}</p>
                        <a href="{{ route('services') }}" class="link mt-8 inline-block">{{ __('site.home.more_services') }}</a>
                    </div>
                </div>
                <ul class="border-t border-stone lg:col-span-8">
                    @foreach ($services as $service)
                        <li class="grid gap-4 border-b border-stone py-8 sm:grid-cols-[1fr_9rem] sm:gap-8 md:py-10">
                            <div>
                                <h3 class="display-md">{{ Localized::get($service, 'name') }}</h3>
                                @if ($desc = Localized::get($service, 'description'))
                                    <p class="measure mt-3 text-charcoal-soft">{{ $desc }}</p>
                                @endif
                            </div>
                            @if ($service->image)
                                <x-ui.image :src="Localized::media($service->image)" :alt="Localized::get($service, 'name')" ratio="4/3" sizes="9rem" class="hidden sm:block" />
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- ============================================================ About --}}
    @if ($about)
        <section id="about" class="py-20 md:py-32" aria-labelledby="about-title">
            <div class="shell grid items-center gap-12 lg:grid-cols-12 lg:gap-8">
                @if ($about->main_image)
                    <div class="lg:col-span-6">
                        <x-ui.image :src="Localized::media($about->main_image)" :alt="Localized::get($about, 'title', __('site.home.about_title'))" ratio="auto" sizes="(min-width: 1024px) 50vw, 100vw" />
                    </div>
                @endif
                <div @class(['lg:col-span-5 lg:col-start-8' => $about->main_image, 'lg:col-span-8' => ! $about->main_image])>
                    <h2 id="about-title" class="display-lg">{{ Localized::get($about, 'title', __('site.home.about_title')) }}</h2>
                    <div class="measure mt-8 space-y-4 text-lg text-charcoal-soft">
                        @foreach (Localized::paragraphs(Localized::get($about, 'content')) as $p)
                            <p>{{ $p }}</p>
                        @endforeach
                    </div>
                    <a href="{{ route('about') }}" class="link mt-10 inline-block">{{ __('site.home.more_about') }}</a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================ Process (a real sequence) --}}
    @include('partials.site.process')

    {{-- ============================================================ Stats (only real values from the dashboard) --}}
    @include('partials.site.stats', ['stats' => $stats])

    <x-site.contact-band />

</x-layouts.site>
