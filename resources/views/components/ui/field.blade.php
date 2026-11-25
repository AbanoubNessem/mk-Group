@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'hint' => null,
    'rows' => 5,
    'autocomplete' => null,
])
@php
    $id = $attributes->get('id', 'field-'.$name);
    $errorId = $id.'-error';
    $hintId = $hint ? $id.'-hint' : null;
    $describedBy = trim(($hintId ?? '').' '.$errorId);
    $message = $errors->first($name);
@endphp
<div {{ $attributes->only('class') }} data-field="{{ $name }}">
    <label for="{{ $id }}" class="field-label">
        {{ $label }}
        @unless ($required)
            <span class="font-normal text-charcoal-soft">({{ __('site.contact.optional') }})</span>
        @endunless
    </label>
    @if ($hint)
        <p id="{{ $hintId }}" class="-mt-1 mb-2 text-sm text-charcoal-soft">{{ $hint }}</p>
    @endif
    @if ($type === 'textarea')
        <textarea {{ $attributes->except(['class', 'id']) }} id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" class="field-input resize-y"
            aria-describedby="{{ $describedBy }}" @if ($message) aria-invalid="true" @endif
            @required($required)>{{ old($name) }}</textarea>
    @else
        <input {{ $attributes->except(['class', 'id']) }} id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name) }}" class="field-input"
            aria-describedby="{{ $describedBy }}" @if ($message) aria-invalid="true" @endif
            @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if ($type === 'tel') dir="ltr" inputmode="tel" @endif @if ($type === 'email') dir="ltr" @endif
            @required($required)>
    @endif
    <p id="{{ $errorId }}" class="field-error" @if (! $message) hidden @endif>{{ $message }}</p>
</div>
