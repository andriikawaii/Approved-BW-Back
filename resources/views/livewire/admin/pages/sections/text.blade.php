@php
    $isLong = str_contains($field, 'content') || str_contains($field, 'description') || str_contains($field, 'body');
    $inputClasses = 'w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink placeholder-ink-faint transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30';
@endphp

@if ($isLong)
    <textarea
        wire:model.live="sections.{{ $index }}.data.{{ $field }}"
        rows="4"
        class="{{ $inputClasses }} resize-y"
        placeholder="{{ \Illuminate\Support\Str::headline($field) }}..."
    ></textarea>
@else
    <input
        type="text"
        wire:model.live="sections.{{ $index }}.data.{{ $field }}"
        class="{{ $inputClasses }}"
        placeholder="{{ \Illuminate\Support\Str::headline($field) }}..."
    />
@endif
