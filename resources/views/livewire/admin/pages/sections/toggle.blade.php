<label class="group relative inline-flex cursor-pointer items-center gap-3">
    <input
        type="checkbox"
        wire:model.live="sections.{{ $index }}.data.{{ $field }}"
        class="peer sr-only"
    >
    <div class="h-5 w-9 rounded-full bg-edge-strong transition-colors after:absolute after:left-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-ink-faint after:transition-all peer-checked:bg-tint peer-checked:after:translate-x-full peer-checked:after:bg-tint-on peer-focus-visible:ring-2 peer-focus-visible:ring-tint/40"></div>
    <span class="text-sm text-ink-muted transition peer-checked:text-ink">Enabled</span>
</label>
