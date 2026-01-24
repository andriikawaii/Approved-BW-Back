<label class="inline-flex items-center gap-2">
    <input
        type="checkbox"
        wire:model.defer="sections.{{ $index }}.data.{{ $field }}"
    >
    <span class="text-sm">Enabled</span>
</label>
