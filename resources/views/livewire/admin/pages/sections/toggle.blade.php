<label class="inline-flex items-center gap-2">
    <input
        type="checkbox"
        wire:model.live="sections.{{ $index }}.data.{{ $field }}"
        class="rounded border-zinc-600 bg-zinc-800 text-emerald-500"
    >
    <span class="text-sm text-zinc-300">Enabled</span>
</label>
