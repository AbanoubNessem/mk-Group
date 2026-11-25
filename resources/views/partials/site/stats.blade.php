{{-- Stats from the dashboard only. Nothing renders when there are none — no placeholder numbers. --}}
@use('App\Support\Localized')
@if (isset($stats) && $stats->isNotEmpty())
    <section class="border-b border-stone py-16 md:py-24" aria-labelledby="stats-title">
        <div class="shell">
            <h2 id="stats-title" class="sr-only">{{ __('site.home.stats_title') }}</h2>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-12 {{ [1 => 'md:grid-cols-1', 2 => 'md:grid-cols-2', 3 => 'md:grid-cols-3'][$stats->count()] ?? 'md:grid-cols-4' }}">
                @foreach ($stats as $stat)
                    <div class="flex flex-col border-s border-stone ps-6">
                        <dt class="text-sm text-charcoal-soft">{{ Localized::get($stat, 'label') }}</dt>
                        <dd class="order-first font-display text-5xl font-light text-slate" dir="ltr">{{ $stat->value }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>
@endif
