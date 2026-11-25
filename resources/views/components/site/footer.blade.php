@use('App\Support\Localized')
@php
    $locale = app()->getLocale();
    $phones = array_filter((array) ($siteContact->phone_numbers ?? []));
    $email = $siteContact->email ?? null;
    $areas = config('site.areas.'.$locale, []);
@endphp
<footer class="on-dark bg-charcoal-deep text-white">
    <div class="shell grid gap-12 py-16 md:grid-cols-12 md:py-20">
        <div class="md:col-span-5">
            <a href="{{ route('home') }}" class="inline-block" aria-label="{{ config('site.name') }} — {{ __('site.nav.home') }}">
                <img src="{{ asset('assets/images/mk-logo-light.png') }}" alt="" width="1127" height="582" class="h-16 w-auto" loading="lazy">
            </a>
            <p class="mt-6 max-w-sm text-white/75">{{ __('site.tagline') }}.</p>
            @if ($areas)
                <p class="mt-2 max-w-sm text-white/75">{{ __('site.footer.areas') }}</p>
            @endif
        </div>

        <nav class="md:col-span-3" aria-label="{{ __('site.footer.explore') }}">
            <h2 class="text-sm font-medium text-white/60">{{ __('site.footer.explore') }}</h2>
            <ul class="mt-4 space-y-2">
                <li><a class="hover:underline" href="{{ route('projects') }}">{{ __('site.nav.projects') }}</a></li>
                <li><a class="hover:underline" href="{{ route('services') }}">{{ __('site.nav.services') }}</a></li>
                <li><a class="hover:underline" href="{{ route('about') }}">{{ __('site.nav.about') }}</a></li>
                <li><a class="hover:underline" href="{{ route('contact') }}">{{ __('site.nav.contact') }}</a></li>
            </ul>
        </nav>

        <div class="md:col-span-4">
            <h2 class="text-sm font-medium text-white/60">{{ __('site.footer.get_in_touch') }}</h2>
            <ul class="mt-4 space-y-2">
                @foreach ($phones as $phone)
                    <li><a class="hover:underline" href="{{ Localized::tel($phone) }}" dir="ltr">{{ $phone }}</a></li>
                @endforeach
                @if ($email)
                    <li><a class="break-all hover:underline" href="mailto:{{ $email }}">{{ $email }}</a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="border-t border-white/15">
        <div class="shell flex flex-wrap items-center justify-between gap-4 py-6 text-sm text-white/60">
            <p>&copy; {{ now()->year }} {{ config('site.name') }}. {{ __('site.footer.rights') }}</p>
            <a href="{{ request()->fullUrlWithQuery(['lang' => $locale === 'ar' ? 'en' : 'ar']) }}" hreflang="{{ $locale === 'ar' ? 'en' : 'ar' }}" lang="{{ $locale === 'ar' ? 'en' : 'ar' }}" class="hover:text-white">{{ __('site.lang.switch_to') }}</a>
        </div>
    </div>
</footer>
