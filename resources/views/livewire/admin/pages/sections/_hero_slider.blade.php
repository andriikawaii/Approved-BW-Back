@php
    $data = $section['data'] ?? [];
    $images = $data['images'] ?? [];
@endphp

<div class="space-y-6">
    {{-- Section intro --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Hero Slider</h3>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                    Controls the homepage hero carousel: headline, CTAs and slider images.
                </p>
            </div>
            <span class="text-[11px] px-2 py-1 rounded-full bg-zinc-100 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200">
                type: hero_slider
            </span>
        </div>
    </div>

    {{-- Content --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 space-y-4">
        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Content</h4>

        <div>
            <label class="text-xs text-gray-500 dark:text-zinc-400">Headline <span class="text-red-500">*</span></label>
            <input
                type="text"
                wire:model.defer="sections.{{ $index }}.data.headline"
                class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                placeholder="The Last Contractor You’ll Hire"
            >
        </div>

        <div>
            <label class="text-xs text-gray-500 dark:text-zinc-400">Subheadline</label>
            <input
                type="text"
                wire:model.defer="sections.{{ $index }}.data.subheadline"
                class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                placeholder="From custom kitchens to full home renovations..."
            >
        </div>
    </div>

    {{-- CTA --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 space-y-5">
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Call To Action</h4>
            <p class="text-xs text-gray-500 dark:text-zinc-400">Primary is required</p>
        </div>

        {{-- Primary --}}
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 bg-zinc-50 dark:bg-zinc-900/40">
            <div class="flex items-center justify-between mb-3">
                <h5 class="text-xs font-semibold text-gray-900 dark:text-white">Primary CTA</h5>
                <span class="text-[11px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                    required
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-gray-500 dark:text-zinc-400">Label</label>
                    <input
                        type="text"
                        wire:model.defer="sections.{{ $index }}.data.cta_primary.label"
                        class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                        placeholder="BOOK A FREE CONSULTATION"
                    >
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-zinc-400">URL</label>
                    <input
                        type="text"
                        wire:model.defer="sections.{{ $index }}.data.cta_primary.url"
                        class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                        placeholder="/contact"
                    >
                </div>
            </div>
        </div>

        {{-- Secondary --}}
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 bg-zinc-50 dark:bg-zinc-900/40">
            <div class="flex items-center justify-between mb-3">
                <h5 class="text-xs font-semibold text-gray-900 dark:text-white">Secondary CTA</h5>
                <span class="text-[11px] px-2 py-0.5 rounded-full bg-zinc-200 text-zinc-700 dark:bg-zinc-700 dark:text-zinc-200">
                    optional
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-gray-500 dark:text-zinc-400">Label</label>
                    <input
                        type="text"
                        wire:model.defer="sections.{{ $index }}.data.cta_secondary.label"
                        class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                        placeholder="CALL NOW"
                    >
                </div>
                <div>
                    <label class="text-xs text-gray-500 dark:text-zinc-400">URL</label>
                    <input
                        type="text"
                        wire:model.defer="sections.{{ $index }}.data.cta_secondary.url"
                        class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                        placeholder="tel:+12039199616"
                    >
                </div>
            </div>
        </div>
    </div>

    {{-- Images --}}
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-4 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Slider Images</h4>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                    Must contain at least 1 image. Store final public path/URL as string.
                </p>
            </div>

            <button
                type="button"
                wire:click="addHeroImage({{ $index }})"
                class="text-xs px-3 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700"
            >
                + Add Image
            </button>
        </div>

        {{-- Add from Media --}}
        <div x-data class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <label class="text-xs text-gray-500 dark:text-zinc-400">Quick add from Media Library</label>
                <select
                    class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                    @change="
                        if($event.target.value){
                          $wire.addHeroImageFromMedia({{ $index }}, $event.target.value);
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
            <div class="sm:col-span-1">
                <label class="text-xs text-gray-500 dark:text-zinc-400">Tip</label>
                <div class="mt-1 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/40 p-3 text-xs text-gray-600 dark:text-zinc-300">
                    Use either <code>/images/...</code> or <code>/storage/...</code> URLs.
                </div>
            </div>
        </div>

        {{-- List --}}
        <div class="space-y-3">
            @forelse($images as $imgIndex => $img)
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 rounded-lg border border-zinc-200 dark:border-zinc-700 p-3 bg-zinc-50 dark:bg-zinc-900/40">
                    <div class="flex-1">
                        <label class="text-[11px] text-gray-500 dark:text-zinc-400">Image URL</label>
                        <input
                            type="text"
                            wire:model.defer="sections.{{ $index }}.data.images.{{ $imgIndex }}"
                            class="w-full mt-1 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white"
                            placeholder="/images/hero/hero-carousel-1.jpg"
                        >
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            wire:click="removeHeroImage({{ $index }}, {{ $imgIndex }})"
                            class="text-xs px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700"
                        >
                            Remove
                        </button>
                    </div>
                </div>

                @if(!empty($img))
                    <div class="rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                        <img src="{{ $img }}" alt="Hero image" class="w-full max-h-56 object-cover">
                    </div>
                @endif
            @empty
                <div class="text-sm text-amber-600 dark:text-amber-400">
                    No images yet. Add at least one image URL.
                </div>
            @endforelse
        </div>
    </div>
</div>
