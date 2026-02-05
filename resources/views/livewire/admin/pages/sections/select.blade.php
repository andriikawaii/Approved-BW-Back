@php
    preg_match('/in:([^|]+)/', $rules, $m);
    $options = explode(',', $m[1] ?? '');
@endphp

<select
    wire:model.live="sections.{{ $index }}.data.{{ $field }}"
    class="w-full rounded-lg border border-zinc-700/60 bg-zinc-800/40 px-3 py-2 text-sm text-white transition focus:border-emerald-500/50 focus:outline-none focus:ring-1 focus:ring-emerald-500/20"
>
    <option value="">-- Select --</option>
    @foreach ($options as $opt)
        <option value="{{ $opt }}">{{ ucfirst(str_replace('_', ' ', $opt)) }}</option>
    @endforeach
</select>
