@php
    $isLong = str_contains($field, 'content') || str_contains($field, 'description') || str_contains($field, 'body');
    $inputClasses = 'w-full rounded-lg border border-zinc-700/60 bg-zinc-800/40 px-3 py-2 text-sm text-white placeholder-zinc-600 transition focus:border-emerald-500/50 focus:outline-none focus:ring-1 focus:ring-emerald-500/20';
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
