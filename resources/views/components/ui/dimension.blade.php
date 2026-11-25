@props(['value'])
{{-- Architectural dimension line: |──── 350 m² ────| --}}
@if (filled($value))
    <div {{ $attributes->class('dimension') }}>
        <span dir="ltr">{{ $value }}</span>
    </div>
@endif
