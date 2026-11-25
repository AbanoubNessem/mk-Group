@php
    $other = app()->getLocale() === 'ar' ? 'en' : 'ar';
    $links = [
        ['route' => 'projects', 'match' => ['projects', 'project'], 'label' => __('site.nav.projects')],
        ['route' => 'services', 'match' => ['services'], 'label' => __('site.nav.services')],
        ['route' => 'about', 'match' => ['about'], 'label' => __('site.nav.about')],
        ['route' => 'contact', 'match' => ['contact'], 'label' => __('site.nav.contact')],
    ];
@endphp
<header class="sticky top-0 z-40 border-b border-transparent bg-ivory/95 backdrop-blur-sm transition-[border-color] duration-300 data-[scrolled]:border-stone"
    data-site-header>
    <div class="shell flex h-18 items-center gap-8 lg:h-22">
        <a href="{{ route('home') }}" class="-m-2 shrink-0 p-2" aria-label="{{ config('site.name') }} — {{ __('site.nav.home') }}">
            <img src="{{ asset('assets/images/mk-logo-slate.png') }}" alt="" width="1127" height="582" class="h-11 w-auto lg:h-14">
        </a>

        <nav class="ms-auto hidden lg:block" aria-label="{{ __('site.nav.primary') }}">
            <ul class="flex items-center gap-9">
                @foreach ($links as $link)
                    @php $active = request()->routeIs(...$link['match']); @endphp
                    <li>
                        <a href="{{ route($link['route']) }}"
                            @class([
                                'relative py-2 text-sm font-medium transition-colors',
                                'text-charcoal after:absolute after:inset-x-0 after:-bottom-0.5 after:h-px after:bg-charcoal' => $active,
                                'text-charcoal-soft hover:text-charcoal' => ! $active,
                            ])
                            @if ($active) aria-current="page" @endif>{{ $link['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="ms-auto flex items-center gap-3 lg:ms-0">
            <a href="{{ request()->fullUrlWithQuery(['lang' => $other]) }}" hreflang="{{ $other }}" lang="{{ $other }}"
                class="inline-flex min-h-11 min-w-11 items-center justify-center px-2 text-sm font-medium text-charcoal-soft hover:text-charcoal"
                aria-label="{{ __('site.lang.switch_label') }}">{{ __('site.lang.switch_to') }}</a>

            <x-ui.button :href="route('contact')" variant="primary" class="hidden md:inline-flex">{{ __('site.cta.consult') }}</x-ui.button>

            <button type="button" class="-me-2 inline-flex size-11 items-center justify-center text-charcoal lg:hidden"
                aria-expanded="false" aria-controls="mobile-nav" data-nav-toggle>
                <span class="sr-only" data-nav-label data-open="{{ __('site.nav.menu') }}" data-close="{{ __('site.nav.close') }}">{{ __('site.nav.menu') }}</span>
                <x-ui.icon name="menu" :size="24" data-icon-open />
                <x-ui.icon name="close" :size="24" data-icon-close class="hidden" />
            </button>
        </div>
    </div>

</header>

{{-- Mobile navigation — outside <header>: its backdrop-filter would trap position:fixed --}}
<div id="mobile-nav" class="fixed inset-x-0 top-18 bottom-0 z-40 overflow-y-auto bg-ivory lg:hidden" hidden data-nav-panel>
    <nav class="shell flex min-h-full flex-col pt-6 pb-10" aria-label="{{ __('site.nav.primary') }}">
        <ul class="border-t border-stone">
            <li><a href="{{ route('home') }}" class="flex items-center justify-between border-b border-stone py-5 font-display text-2xl font-light" @if (request()->routeIs('home')) aria-current="page" @endif>{{ __('site.nav.home') }}<x-ui.icon name="chevron" :size="20" flip class="text-slate" /></a></li>
            @foreach ($links as $link)
                <li>
                    <a href="{{ route($link['route']) }}" class="flex items-center justify-between border-b border-stone py-5 font-display text-2xl font-light"
                        @if (request()->routeIs(...$link['match'])) aria-current="page" @endif>
                        {{ $link['label'] }}
                        <x-ui.icon name="chevron" :size="20" flip class="text-slate" />
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="mt-auto pt-10">
            <x-ui.button :href="route('contact')" variant="primary" class="w-full">{{ __('site.cta.consult') }}</x-ui.button>
        </div>
    </nav>
</div>

