{{-- Project execution process. Numbered because it is a true sequence. --}}
@php $steps = __('site.process'); @endphp
<section class="bg-charcoal-deep py-20 text-white md:py-32 on-dark" aria-labelledby="process-title">
    <div class="shell">
        <h2 id="process-title" class="display-lg text-white">{{ __('site.home.process_title') }}</h2>
        <ol class="mt-14 grid gap-10 md:mt-20 md:grid-cols-5 md:gap-6">
            @foreach ($steps as $i => $step)
                <li class="relative border-t border-white/25 pt-6">
                    <span class="absolute -top-px start-0 h-px w-12 bg-white" aria-hidden="true"></span>
                    <span class="font-display text-4xl font-light text-slate-mist/70" aria-hidden="true">{{ $i + 1 }}</span>
                    <h3 class="mt-4 font-display text-lg font-normal text-white">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm text-white/70">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
