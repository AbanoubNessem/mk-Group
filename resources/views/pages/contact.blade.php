{{-- Contact. Data from SiteController@contact. Form posts to the existing POST /contact (contact.store). --}}
@use('App\Support\Localized')
@php
    $phones = array_values(array_filter((array) ($contactInfo->phone_numbers ?? [])));
    $email = $contactInfo->email ?? null;
    $hours = array_values(array_filter(array_map('trim', preg_split('/<br\s*\/?>|\R/iu', (string) Localized::get($contactInfo, 'hours_text')))));
    $areas = config('site.areas.'.app()->getLocale(), []);
    $whatsapp = config('site.whatsapp');
@endphp
<x-layouts.site :title="__('site.seo.contact_title')" :description="__('site.seo.contact_description')">

    <div class="shell grid gap-16 pt-14 pb-24 md:pt-24 md:pb-36 lg:grid-cols-12 lg:gap-8">
        {{-- Details --}}
        <div class="lg:col-span-5">
            <h1 class="display-xl">{{ Localized::get($contactInfo, 'contact_title', __('site.contact.title')) }}</h1>
            <p class="measure mt-6 text-lg text-charcoal-soft">{{ __('site.contact.intro') }}</p>

            <dl class="mt-12 border-t border-stone">
                @if ($phones)
                    <div class="flex gap-5 border-b border-stone py-6">
                        <x-ui.icon name="phone" :size="22" class="mt-0.5 text-slate-deep" />
                        <div>
                            <dt class="text-sm text-charcoal-soft">{{ __('site.contact.phone') }}</dt>
                            @foreach ($phones as $phone)
                                <dd class="mt-1 text-lg"><a href="{{ Localized::tel($phone) }}" class="hover:underline" dir="ltr">{{ $phone }}</a></dd>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($whatsapp)
                    <div class="flex gap-5 border-b border-stone py-6">
                        <x-ui.icon name="whatsapp" :size="22" class="mt-0.5 text-slate-deep" />
                        <div>
                            <dt class="sr-only">WhatsApp</dt>
                            <dd><a href="https://wa.me/{{ $whatsapp }}" class="link text-lg" target="_blank" rel="noopener">{{ __('site.cta.whatsapp') }}</a></dd>
                        </div>
                    </div>
                @endif
                @if ($email)
                    <div class="flex gap-5 border-b border-stone py-6">
                        <x-ui.icon name="mail" :size="22" class="mt-0.5 text-slate-deep" />
                        <div class="min-w-0">
                            <dt class="text-sm text-charcoal-soft">{{ __('site.contact.email') }}</dt>
                            <dd class="mt-1 text-lg break-all"><a href="mailto:{{ $email }}" class="hover:underline">{{ $email }}</a></dd>
                        </div>
                    </div>
                @endif
                @if ($hours)
                    <div class="flex gap-5 border-b border-stone py-6">
                        <x-ui.icon name="clock" :size="22" class="mt-0.5 text-slate-deep" />
                        <div>
                            <dt class="text-sm text-charcoal-soft">{{ __('site.contact.hours') }}</dt>
                            @foreach ($hours as $line)
                                <dd class="mt-1">{{ $line }}</dd>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($areas)
                    <div class="flex gap-5 border-b border-stone py-6">
                        <x-ui.icon name="pin" :size="22" class="mt-0.5 text-slate-deep" />
                        <div>
                            <dt class="text-sm text-charcoal-soft">{{ __('site.contact.areas') }}</dt>
                            <dd class="mt-1">{{ implode(app()->getLocale() === 'ar' ? '، ' : ', ', $areas) }}</dd>
                        </div>
                    </div>
                @endif
            </dl>
        </div>

        {{-- Form --}}
        <section class="bg-white p-6 sm:p-10 lg:col-span-6 lg:col-start-7 lg:p-12" aria-labelledby="form-title">
            <h2 id="form-title" class="display-md">{{ __('site.contact.form_title') }}</h2>

            <form method="POST" action="{{ route('contact.store') }}" class="mt-10 grid gap-7" novalidate data-contact-form
                data-sending="{{ __('site.contact.sending') }}" data-submit="{{ __('site.contact.submit') }}"
                data-sent-title="{{ __('site.contact.sent_title') }}" data-sent="{{ __('site.contact.sent') }}"
                data-error="{{ __('site.contact.error') }}" data-required="{{ __('site.contact.required') }}" data-email-invalid="{{ __('site.contact.email_invalid') }}" data-fix="{{ __('site.contact.fix') }}">
                @csrf
                <div data-form-status tabindex="-1" class="outline-none" hidden></div>

                <x-ui.field name="name" :label="__('site.contact.name')" required autocomplete="name" maxlength="255" />
                <div class="grid gap-7 sm:grid-cols-2">
                    <x-ui.field name="email" type="email" :label="__('site.contact.email_field')" required autocomplete="email" maxlength="255" />
                    <x-ui.field name="phone" type="tel" :label="__('site.contact.phone_field')" autocomplete="tel" maxlength="255" />
                </div>
                <x-ui.field name="project" :label="__('site.contact.project_field')" :hint="__('site.contact.project_hint')" list="project-types" maxlength="255" />
                <x-ui.field name="message" type="textarea" :label="__('site.contact.message')" :hint="__('site.contact.message_hint')" required />

                @if ($services->isNotEmpty())
                    <datalist id="project-types">
                        @foreach ($services as $service)
                            <option value="{{ Localized::get($service, 'name') }}"></option>
                        @endforeach
                    </datalist>
                @endif

                <div>
                    <x-ui.button type="submit" variant="primary" class="w-full sm:w-auto">{{ __('site.contact.submit') }}</x-ui.button>
                </div>
            </form>

            <template id="notice-success"><x-ui.notice type="success"></x-ui.notice></template>
            <template id="notice-error"><x-ui.notice type="error"></x-ui.notice></template>
        </section>
    </div>
</x-layouts.site>
