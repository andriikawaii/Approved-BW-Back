<div class="space-y-6">

    {{-- CONTENT --}}
    <div class="space-y-4">
        <h4 class="text-sm font-semibold text-gray-700 dark:text-zinc-300">
            Content
        </h4>

        {{-- Headline --}}
        <div>
            <label class="text-xs text-gray-500 dark:text-zinc-400">
                Headline <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                wire:model.defer="sections.{{ $index }}.data.headline"
                placeholder="Quality Home Remodeling in Connecticut"
                class="w-full mt-1 rounded-lg border
                       border-gray-300 dark:border-zinc-600
                       bg-white dark:bg-zinc-700
                       px-3 py-2 text-sm
                       text-gray-900 dark:text-white"
            >
        </div>

        {{-- Subheadline --}}
        <div>
            <label class="text-xs text-gray-500 dark:text-zinc-400">
                Subheadline
            </label>
            <input
                type="text"
                wire:model.defer="sections.{{ $index }}.data.subheadline"
                placeholder="Kitchen, bathroom, and whole-home renovations built to last."
                class="w-full mt-1 rounded-lg border
                       border-gray-300 dark:border-zinc-600
                       bg-white dark:bg-zinc-700
                       px-3 py-2 text-sm
                       text-gray-900 dark:text-white"
            >
        </div>
    </div>

    {{-- MEDIA --}}
    <div class="space-y-3">
        <h4 class="text-sm font-semibold text-gray-700 dark:text-zinc-300">
            Media
        </h4>

        <select
            wire:model.defer="sections.{{ $index }}.data.media_asset_id"
            class="w-full rounded-lg border
                   border-gray-300 dark:border-zinc-600
                   bg-white dark:bg-zinc-700
                   px-3 py-2 text-sm
                   text-gray-900 dark:text-white"
        >
            <option value="">— Select image from Media Library —</option>

            @foreach($mediaAssets as $media)
                <option value="{{ $media->id }}">
                    #{{ $media->id }} — {{ $media->file_name }}
                </option>
            @endforeach
        </select>

        {{-- Image preview --}}
        @php
            $selected = !empty($section['data']['media_asset_id'])
                ? $mediaAssets->firstWhere('id', (int) $section['data']['media_asset_id'])
                : null;
        @endphp

        @if($selected)
            <div class="mt-3">
                <img
                    src="{{ $selected->url }}"
                    alt="{{ $selected->file_name }}"
                    class="rounded-lg max-h-48 w-full object-cover border
                           border-gray-200 dark:border-zinc-700"
                >
            </div>
        @endif
    </div>

    {{-- CTA --}}
    <div class="space-y-4">
        <h4 class="text-sm font-semibold text-gray-700 dark:text-zinc-300">
            Call To Action
        </h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- CTA Label --}}
            <div>
                <label class="text-xs text-gray-500 dark:text-zinc-400">
                    Button label
                </label>
                <input
                    type="text"
                    wire:model.defer="sections.{{ $index }}.data.cta_label"
                    placeholder="Get a Free Estimate"
                    class="w-full mt-1 rounded-lg border
                           border-gray-300 dark:border-zinc-600
                           bg-white dark:bg-zinc-700
                           px-3 py-2 text-sm
                           text-gray-900 dark:text-white"
                >
            </div>

            {{-- CTA URL --}}
            <div>
                <label class="text-xs text-gray-500 dark:text-zinc-400">
                    Button URL
                </label>
                <input
                    type="text"
                    wire:model.defer="sections.{{ $index }}.data.cta_url"
                    placeholder="/contact"
                    class="w-full mt-1 rounded-lg border
                           border-gray-300 dark:border-zinc-600
                           bg-white dark:bg-zinc-700
                           px-3 py-2 text-sm
                           text-gray-900 dark:text-white"
                >
            </div>
        </div>
    </div>

</div>
