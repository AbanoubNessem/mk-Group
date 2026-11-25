{{-- Services. Data from SiteController@services: $services, $intro (optional home section "services"). --}}
@use('App\Support\Localized')
<x-layouts.site :title="__('site.seo.services_title')" :description="__('site.seo.services_description')">

    <header class="shell pt-14 pb-16 md:pt-24 md:pb-24">
        <h1 class="display-xl">{{ Localized::get($intro, 'title', __('site.services.title')) }}</h1>
        <p class="measure mt-6 text-lg text-charcoal-soft">{{ __('site.services.intro') }}</p>
    </header>

    @if ($services->isEmpty())
        <p class="shell pb-32 text-lg text-charcoal-soft">{{ __('site.services.empty') }}</p>
    @else
        <div class="border-t border-stone">
            @foreach ($services as $i => $service)
                @php
                    $name = Localized::get($service, 'name');
                    $features = Localized::list($service, 'features');
                    $flip = $i % 2 === 1;
                @endphp
                <section id="{{ $service->slug ?: 'service-'.$service->id }}" class="border-b border-stone" aria-labelledby="service-{{ $service->id }}">
                    <div class="shell grid items-center gap-10 py-14 md:py-20 lg:grid-cols-12 lg:gap-8">
                        @if ($service->image)
                            <div @class(['lg:col-span-6', 'lg:order-2 lg:col-start-7' => $flip])>
                                <x-ui.image :src="Localized::media($service->image)" :alt="$name" ratio="3/2" sizes="(min-width: 1024px) 50vw, 100vw" />
                            </div>
                        @endif
                        <div @class([
                            'lg:col-span-5',
                            'lg:col-start-8' => ! $flip && $service->image,
                            'lg:order-1 lg:col-start-1' => $flip,
                        ])>
                            <h2 id="service-{{ $service->id }}" class="display-md">{{ $name }}</h2>
                            @if ($desc = Localized::get($service, 'description'))
                                <p class="measure mt-5 text-lg text-charcoal-soft">{{ $desc }}</p>
                            @endif
                            @if ($features)
                                <h3 class="mt-8 text-sm font-medium text-charcoal">{{ __('site.services.includes') }}</h3>
                                <ul class="mt-3 grid gap-2 sm:grid-cols-2">
                                    @foreach ($features as $feature)
                                        <li class="flex gap-3 text-charcoal-soft">
                                            <x-ui.icon name="check" :size="18" class="mt-1 text-slate-deep" />{{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </section>
            @endforeach
        </div>
    @endif

    @include('partials.site.process')

    <x-site.contact-band />
</x-layouts.site>
