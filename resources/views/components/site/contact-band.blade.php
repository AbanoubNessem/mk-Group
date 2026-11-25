@props([
    'title' => null,
    'text' => null,
])
@use('App\Support\Localized')
@php
    $phones = array_values(array_filter((array) ($siteContact->phone_numbers ?? [])));
    $whatsapp = config('site.whatsapp');
@endphp
{{-- Consultation call-to-action: brand slate field with a white contact panel --}}
<section {{ $attributes->class('bg-slate') }} aria-labelledby="contact-band-title">
    <div class="shell grid items-end gap-10 py-16 md:py-24 lg:grid-cols-12">
        <h2 id="contact-band-title" class="display-lg text-white lg:col-span-6">{{ $title ?? __('site.home.contact_title') }}</h2>
        <div class="bg-white p-8 md:p-10 lg:col-span-6 lg:col-start-7">
            <p class="measure text-charcoal">{{ $text ?? __('site.home.contact_text') }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <x-ui.button :href="route('contact')" variant="primary">{{ __('site.cta.consult') }}</x-ui.button>
                @if ($whatsapp)
                    <x-ui.button href="https://wa.me/{{ $whatsapp }}" variant="outline" icon="whatsapp" :icon-flip="false" target="_blank" rel="noopener">{{ __('site.cta.whatsapp') }}</x-ui.button>
                @elseif ($phones)
                    <a href="{{ Localized::tel($phones[0]) }}" class="btn btn-outline">
                        <x-ui.icon name="phone" :size="18" />
                        <span dir="ltr">{{ $phones[0] }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
