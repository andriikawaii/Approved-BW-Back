@php
    $items = $section['data']['items'] ?? [];
    $iconOptions = [
        'shield','star','clock','map-pin','award','check-circle','badge-check','home','wrench','hammer','sparkles'
    ];
@endphp

<div class="space-y-6">
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Trust Bar</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                    Displays 1–6 trust items under the hero. Order is preserved.
                </p>
            </div>
            <button
                type="button"
                wire:click="addTrustItem({{ $index }})"
                class="text-xs px-3 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700"
            >
                + Add Item
            </button>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 space-y-4">
        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Items</h4>

        <div class="space-y-3">
            @forelse($items as $i => $item)
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/40 p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-900 dark:text-white">
                            Item #{{ $i + 1 }}
                        </p>
                        <button
                            type="button"
                            wire:click="removeTrustItem({{ $index }}, {{ $i }})"
                            class="text-xs px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700"
                        >
                            Remove
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 dark:text-zinc-400">Icon (Lucide name)</label>
                            <select
                                wire:model.defer="sections.{{ $index }}.data.items.{{ $i }}.icon"
                                class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                            >
                                @foreach($iconOptions as $ico)
                                    <option value="{{ $ico }}">{{ $ico }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs text-gray-500 dark:text-zinc-400">Label</label>
                            <input
                                type="text"
                                wire:model.defer="sections.{{ $index }}.data.items.{{ $i }}.label"
                                class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                                placeholder="CT HIC License #HIC.0652847"
                            >
                        </div>
                    </div>

                    <div class="text-[11px] text-gray-500 dark:text-zinc-400">
                        Tip: keep labels short for best layout on mobile.
                    </div>
                </div>
            @empty
                <div class="text-sm text-amber-600 dark:text-amber-400">
                    No trust items yet. Add at least one.
                </div>
            @endforelse
        </div>
    </div>
</div>
