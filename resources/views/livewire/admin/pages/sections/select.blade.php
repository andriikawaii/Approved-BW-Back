@php
    preg_match('/in:([^|]+)/', $rules, $m);
    $options = explode(',', $m[1] ?? '');
@endphp

<select
    wire:model.live="sections.{{ $index }}.data.{{ $field }}"
    class="w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30"
>
    <option value="">-- Select --</option>
    @foreach ($options as $opt)
        <option value="{{ $opt }}">{{ ucfirst(str_replace('_', ' ', $opt)) }}</option>
    @endforeach
</select>
