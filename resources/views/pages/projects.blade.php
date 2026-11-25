{{-- Portfolio index. Data from SiteController@projects. --}}
@use('App\Support\Localized')
@php
    $query = request()->except('category');
    $filterUrl = fn ($key) => route('projects', array_filter($query + ['category' => $key]));
    $activeLabel = $active ? optional($categories->firstWhere('key', $active))['label'] : null;
@endphp
<x-layouts.site :title="$activeLabel ? __('site.projects.title').' — '.$activeLabel : __('site.seo.projects_title')"
    :description="__('site.seo.projects_description')"
    :image="Localized::media(optional($projects->first())->thumbnail)">

    <header class="shell pt-14 pb-10 md:pt-24 md:pb-14">
        <h1 class="display-xl">{{ __('site.projects.title') }}</h1>
        <p class="measure mt-6 text-lg text-charcoal-soft">{{ __('site.projects.intro') }}</p>
    </header>

    {{-- Category filter: plain links, works without JavaScript --}}
    @if ($categories->count() > 1 || $countries->isNotEmpty())
        <nav class="shell" aria-label="{{ __('site.projects.filter_label') }}">
            <div class="flex flex-wrap items-center gap-x-8 gap-y-3 border-y border-stone py-4">
                <ul class="-mx-1 flex flex-wrap gap-1">
                    @foreach ([['key' => null, 'label' => __('site.projects.all')], ...$categories] as $cat)
                        @php $isActive = $active === $cat['key']; @endphp
                        <li>
                            <a href="{{ $filterUrl($cat['key']) }}" @if ($isActive) aria-current="page" @endif
                                @class([
                                    'inline-flex min-h-11 items-center px-3 text-sm transition-colors',
                                    'bg-charcoal text-white' => $isActive,
                                    'text-charcoal-soft hover:bg-stone-soft hover:text-charcoal' => ! $isActive,
                                ])>{{ $cat['label'] }}</a>
                        </li>
                    @endforeach
                </ul>

                @if ($countries->isNotEmpty())
                    <ul class="flex flex-wrap gap-1 md:ms-auto">
                        @foreach ([null, ...$countries] as $country)
                            @php $isActive = (string) request('country') === (string) ($country->id ?? ''); @endphp
                            <li>
                                <a href="{{ route('projects', array_filter(['category' => $active, 'country' => $country->id ?? null])) }}"
                                    @if ($isActive) aria-current="true" @endif
                                    @class(['inline-flex min-h-11 items-center gap-2 px-3 text-sm', 'text-charcoal underline underline-offset-4' => $isActive, 'text-charcoal-soft hover:text-charcoal' => ! $isActive])>
                                    @if ($country) <span aria-hidden="true">{{ $country->flag_emoji }}</span> {{ Localized::get($country, 'name') }} @else {{ __('site.projects.all') }} @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <p class="text-sm text-charcoal-soft md:ms-auto" aria-live="polite">{{ trans_choice('site.projects.count', $projects->count()) }}</p>
            </div>
        </nav>
    @endif

    <section class="shell pt-12 pb-24 md:pt-16 md:pb-36" aria-label="{{ $activeLabel ?? __('site.projects.title') }}">
        @if ($projects->isEmpty())
            <div class="border border-dashed border-slate px-6 py-20 text-center">
                <p class="text-lg">{{ $all->isEmpty() ? __('site.projects.empty_all') : __('site.projects.empty') }}</p>
                @if ($active)
                    <a href="{{ route('projects') }}" class="link mt-6 inline-block">{{ __('site.projects.all') }}</a>
                @endif
            </div>
        @else
            <div class="grid gap-x-8 gap-y-16 md:grid-cols-12 md:gap-y-24">
                @foreach ($projects as $i => $project)
                    @php
                        $pair = intdiv($i, 2) % 2;
                        $first = $i % 2 === 0;
                        $wide = $first === ($pair === 0);
                    @endphp
                    <x-project.card :project="$project" heading-level="h2"
                        :eager="$i < 2"
                        :ratio="$wide ? '5/4' : '4/5'"
                        :sizes="$wide ? '(min-width: 768px) 58vw, 100vw' : '(min-width: 768px) 42vw, 100vw'"
                        :class="\Illuminate\Support\Arr::toCssClasses(['md:col-span-7' => $wide, 'md:col-span-5' => ! $wide, 'md:mt-32' => ! $first])" />
                @endforeach
            </div>
        @endif
    </section>

    <x-site.contact-band :title="__('site.project.similar')" :text="__('site.project.similar_text')" />
</x-layouts.site>
