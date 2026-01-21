<div class="space-y-6">

    @if (session()->has('success'))
        <div class="rounded-lg bg-emerald-100 text-emerald-800
                    dark:bg-emerald-900/30 dark:text-emerald-300
                    px-4 py-3 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- SEO FORM --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- LEFT: INPUTS --}}
        <div class="space-y-5">

            <h3 class="text-sm font-semibold text-gray-700 dark:text-zinc-300">
                SEO Settings
            </h3>

            {{-- SEO Title --}}
            <div>
                <label class="text-xs text-gray-500 dark:text-zinc-400">
                    SEO Title (max 60 chars)
                </label>

                <input
                    type="text"
                    wire:model.defer="seo_title"
                    class="w-full mt-1 rounded-lg border
                           border-gray-300 dark:border-zinc-600
                           bg-white dark:bg-zinc-700
                           px-3 py-2 text-sm
                           text-gray-900 dark:text-white"
                >

                <p class="text-xs text-gray-400 mt-1">
                    {{ strlen($seo_title) }}/60 characters
                </p>
            </div>

            {{-- Meta Description --}}
            <div>
                <label class="text-xs text-gray-500 dark:text-zinc-400">
                    Meta Description (max 160 chars)
                </label>

                <textarea
                    rows="3"
                    wire:model.defer="seo_description"
                    class="w-full mt-1 rounded-lg border
                           border-gray-300 dark:border-zinc-600
                           bg-white dark:bg-zinc-700
                           px-3 py-2 text-sm
                           text-gray-900 dark:text-white"
                ></textarea>

                <p class="text-xs text-gray-400 mt-1">
                    {{ strlen($seo_description) }}/160 characters
                </p>
            </div>

            {{-- Canonical --}}
            <div>
                <label class="text-xs text-gray-500 dark:text-zinc-400">
                    Canonical URL (optional)
                </label>

                <input
                    type="text"
                    wire:model.defer="canonical_url"
                    placeholder="https://example.com/page"
                    class="w-full mt-1 rounded-lg border
                           border-gray-300 dark:border-zinc-600
                           bg-white dark:bg-zinc-700
                           px-3 py-2 text-sm
                           text-gray-900 dark:text-white"
                >
            </div>

            <div class="flex justify-end">
                <button
                    wire:click="save"
                    class="px-5 py-2 rounded-lg
                           bg-purple-600 text-white hover:bg-purple-700"
                >
                    Save SEO
                </button>
            </div>
        </div>

        {{-- RIGHT: GOOGLE PREVIEW --}}
        <div class="space-y-4">

            <h3 class="text-sm font-semibold text-gray-700 dark:text-zinc-300">
                Google Preview
            </h3>

            <div class="rounded-xl border
                        border-gray-200 dark:border-zinc-700
                        bg-white dark:bg-zinc-900
                        p-4">

                <p class="text-xs text-green-700 dark:text-green-400 mb-1">
                    {{ $canonical_url ?: url($page->full_path) }}
                </p>

                <h4 class="text-lg font-medium text-blue-700 dark:text-blue-400 leading-snug">
                    {{ $seo_title ?: 'Page title will appear here' }}
                </h4>

                <p class="text-sm text-gray-700 dark:text-zinc-400 mt-1">
                    {{ $seo_description ?: 'Meta description will appear here. Keep it concise and compelling to improve click-through rate.' }}
                </p>
            </div>

            <p class="text-xs text-gray-400">
                Preview simulates Google desktop search results.
            </p>
        </div>
    </div>
</div>
