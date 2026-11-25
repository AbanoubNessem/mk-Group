@props(['type' => 'success', 'title' => null])
{{-- Inline notification. Announced to screen readers. --}}
<div role="{{ $type === 'error' ? 'alert' : 'status' }}"
    {{ $attributes->class([
        'flex gap-3 border-s-2 px-4 py-3 text-sm',
        'border-success bg-white text-charcoal' => $type === 'success',
        'border-error bg-white text-charcoal' => $type === 'error',
    ]) }}>
    <x-ui.icon :name="$type === 'error' ? 'alert' : 'check'" :size="20" @class(['mt-0.5', 'text-success' => $type === 'success', 'text-error' => $type === 'error']) />
    <div>
        <p class="font-medium" data-notice-title @if (! $title) hidden @endif>{{ $title }}</p>
        <div data-notice-body>{{ $slot }}</div>
    </div>
</div>
