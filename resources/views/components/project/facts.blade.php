@props(['project', 'columns' => 1])
@use('App\Support\Localized')
@php
    $rows = array_filter([
        __('site.project.area') => $project->area ? ['value' => $project->area, 'ltr' => true, 'dim' => true] : null,
        __('site.project.rooms') => $project->rooms_count ? ['value' => $project->rooms_count, 'ltr' => true] : null,
        __('site.project.type') => ($v = Localized::get($project, 'project_type')) ? ['value' => $v] : null,
        __('site.project.scope') => ($v = Localized::get($project, 'engineering_function')) ? ['value' => $v] : null,
        __('site.project.category') => ($v = Localized::get($project, 'category')) ? ['value' => $v] : null,
        __('site.project.location') => ($v = Localized::get($project, 'location')) ? ['value' => $v] : null,
        __('site.project.duration') => filled($project->duration ?? null) ? ['value' => $project->duration] : null,
    ]);
    if ($project->country ?? null) {
        $rows[__('site.project.location')] ??= ['value' => Localized::get($project->country, 'name')];
    }
@endphp
@if ($rows)
    <dl {{ $attributes->class([
        'border-t border-stone',
        'md:grid md:grid-cols-2 md:gap-x-10 lg:grid-cols-3' => $columns > 1,
    ]) }}>
        @foreach ($rows as $label => $row)
            <div class="grid grid-cols-[8rem_1fr] items-center gap-4 border-b border-stone py-4 sm:grid-cols-[10rem_1fr]">
                <dt class="text-sm text-charcoal-soft">{{ $label }}</dt>
                <dd class="text-base text-charcoal">
                    @if (! empty($row['dim']))
                        <x-ui.dimension :value="$row['value']" class="max-w-72 text-slate-deep" />
                    @else
                        <span @if (! empty($row['ltr'])) dir="ltr" @endif>{{ $row['value'] }}</span>
                    @endif
                </dd>
            </div>
        @endforeach
    </dl>
@endif
