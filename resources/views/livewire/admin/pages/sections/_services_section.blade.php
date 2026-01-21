@php
    $data = $section['data'] ?? [];
    $items = $data['items'] ?? [];
@endphp

<div class="space-y-6">
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Services Section</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                    Section title + CTA + service cards. Each card needs image/title/description.
                </p>
            </div>

            <button
                type="button"
                wire:click="addServiceItem({{ $index }})"
                class="text-xs px-3 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700"
            >
                + Add Service
            </button>
        </div>
    </div>

    {{-- Header fields --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 space-y-4">
        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Section Header</h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-gray-500 dark:text-zinc-400">Title <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    wire:model.defer="sections.{{ $index }}.data.title"
                    class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                    placeholder="Our Services"
                >
            </div>

            <div>
                <label class="text-xs text-gray-500 dark:text-zinc-400">Subtitle</label>
                <input
                    type="text"
                    wire:model.defer="sections.{{ $index }}.data.subtitle"
                    class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                    placeholder="High-end remodeling services built to last"
                >
            </div>
        </div>

        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/40 p-4">
            <h5 class="text-xs font-semibold text-gray-900 dark:text-white mb-3">Section CTA (optional)</h5>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-gray-500 dark:text-zinc-400">CTA Label</label>
                    <input
                        type="text"
                        wire:model.defer="sections.{{ $index }}.data.cta.label"
                        class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                        placeholder="BOOK A FREE CONSULTATION"
                    >
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-zinc-400">CTA URL</label>
                    <input
                        type="text"
                        wire:model.defer="sections.{{ $index }}.data.cta.url"
                        class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                        placeholder="/contact"
                    >
                </div>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 space-y-4">
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Service Cards</h4>
            <p class="text-xs text-gray-500 dark:text-zinc-400">Min 1 item</p>
        </div>

        <div class="space-y-4">
            @forelse($items as $i => $item)
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/40 p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-900 dark:text-white">
                            Card #{{ $i + 1 }}
                        </p>
                        <button
                            type="button"
                            wire:click="removeServiceItem({{ $index }}, {{ $i }})"
                            class="text-xs px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700"
                        >
                            Remove
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 dark:text-zinc-400">Title <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                wire:model.defer="sections.{{ $index }}.data.items.{{ $i }}.title"
                                class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                                placeholder="Kitchen Remodeling"
                            >
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 dark:text-zinc-400">Image URL <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                wire:model.defer="sections.{{ $index }}.data.items.{{ $i }}.image"
                                class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                                placeholder="/images/services/kitchen.jpg"
                            >
                        </div>
                    </div>

                    {{-- Add from media --}}
                    <div x-data class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-500 dark:text-zinc-400">Or pick from Media Library</label>
                            <select
                                class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                                @change="
                                    if($event.target.value){
                                      $wire.setServiceItemImageFromMedia({{ $index }}, {{ $i }}, $event.target.value);
                                      $event.target.value = '';
                                    }
                                "
                            >
                                <option value="">Select media…</option>
                                @foreach($mediaAssets as $m)
                                    <option value="{{ $m->id }}">#{{ $m->id }} — {{ $m->file_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 dark:text-zinc-400">Preview</label>
                            <div class="mt-1 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden">
                                @if(!empty($section['data']['items'][$i]['image']))
                                    <img src="{{ $section['data']['items'][$i]['image'] }}" class="w-full h-28 object-cover" alt="Service image">
                                @else
                                    <div class="h-28 flex items-center justify-center text-xs text-gray-500 dark:text-zinc-400">
                                        No image selected
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-gray-500 dark:text-zinc-400">Description <span class="text-red-500">*</span></label>
                        <textarea
                            rows="4"
                            wire:model.defer="sections.{{ $index }}.data.items.{{ $i }}.description"
                            class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                            placeholder="Custom kitchens built with precision..."
                        ></textarea>
                    </div>
                </div>
            @empty
                <div class="text-sm text-amber-600 dark:text-amber-400">
                    No service items yet. Add at least one.
                </div>
            @endforelse
        </div>
    </div>
</div>
