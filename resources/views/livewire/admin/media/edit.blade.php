<div class="w-full max-w-2xl mx-auto px-4 sm:px-6 py-6 bg-gray-50 dark:bg-zinc-900">
    {{-- Header --}}
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-purple-50 dark:bg-purple-900/30 mb-4 mx-auto">
            <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
            Edit Media Details
        </h1>
        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2 max-w-md mx-auto">
            Update the metadata and properties for this media asset. Changes will be reflected across all pages where this media is used.
        </p>
    </div>

    {{-- Edit Card --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">
        {{-- Media Preview Section --}}
        <div class="border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50 p-6">
            <div class="text-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                    Media Preview
                </h2>
                <p class="text-sm text-gray-500 dark:text-zinc-400">
                    Current file: {{ $mediaAsset->file_name }}
                </p>
            </div>

            <div class="flex flex-col items-center">
                @if($mediaAsset->isImage())
                    <div class="relative group max-w-full">
                        <div class="overflow-hidden rounded-2xl border border-gray-300 dark:border-zinc-600 shadow-md">
                            <img
                                src="{{ $mediaAsset->url }}"
                                alt="{{ $mediaAsset->alt_text ?? 'Media preview' }}"
                                class="w-full max-h-[300px] object-contain bg-gray-50 dark:bg-zinc-700/50 transition-transform duration-300 group-hover:scale-105"
                            >
                        </div>
                        <div class="absolute top-2 right-2 flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-white/80 dark:bg-zinc-800 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/30">
                                <svg class="h-3 w-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $mediaAsset->width }}x{{ $mediaAsset->height }}px
                            </span>
                            <span class="inline-flex items-center rounded-full bg-white/80 dark:bg-zinc-800 px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-900/30">
                                <svg class="h-3 w-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ number_format($mediaAsset->size / 1024, 1) }}KB
                            </span>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center w-full max-w-sm">
                        <div class="h-48 w-48 rounded-2xl bg-gray-100 dark:bg-zinc-700/50 flex items-center justify-center border border-dashed border-gray-300 dark:border-zinc-600 mb-4">
                            <div class="text-center px-4">
                                <div class="inline-flex items-center justify-center h-16 w-16 rounded-xl bg-gray-200 dark:bg-zinc-700 mx-auto mb-3">
                                    <svg class="h-8 w-8 text-gray-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-700 dark:text-zinc-300 truncate px-1">
                                    {{ pathinfo($mediaAsset->file_name, PATHINFO_EXTENSION) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                                    {{ number_format($mediaAsset->size / 1024, 1) }} KB
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-zinc-700 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:text-zinc-300">
                                Document
                            </span>
                            <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300">
                                {{ pathinfo($mediaAsset->file_name, PATHINFO_EXTENSION) }}
                            </span>
                        </div>
                    </div>
                @endif

                <div class="mt-6 w-full max-w-md mx-auto">
                    <div class="flex items-center justify-center gap-3 text-sm text-gray-500 dark:text-zinc-400">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-1 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $mediaAsset->created_at->format('M d, Y') }}
                        </div>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-zinc-600"></span>
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-1 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $mediaAsset->views_count }} views
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Fields Section --}}
        <div class="p-6">
            <div class="space-y-6">
                {{-- Title Field --}}
                <div class="space-y-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                        Title <span class="text-gray-400 dark:text-zinc-500">(optional)</span>
                    </label>
                    <input
                        type="text"
                        wire:model.defer="title"
                        id="title"
                        class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                        placeholder="Descriptive title for this media"
                    >
                    <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                        This appears in media library listings and helps with organization. Leave empty to use filename.
                    </p>
                    @error('title')
                    <p class="text-xs text-rose-600 dark:text-rose-400 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alt Text Field --}}
                @if($mediaAsset->isImage())
                    <div class="space-y-2">
                        <label for="alt_text" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                            Alt Text <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            wire:model.defer="alt_text"
                            id="alt_text"
                            rows="3"
                            class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all resize-none"
                            placeholder="Describe this image for accessibility and SEO"
                        ></textarea>
                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                            Describe the image content for screen readers and search engines. Required for accessibility compliance.
                        </p>
                        @error('alt_text')
                        <p class="text-xs text-rose-600 dark:text-rose-400 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                {{-- Usage Information --}}
                <div class="mt-2 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 flex-shrink-0 h-5 w-5 rounded-full bg-purple-500"></div>
                        <div class="text-sm">
                            <p class="font-medium text-purple-800 dark:text-purple-300">
                                Usage Information
                            </p>
                            <p class="text-purple-700 dark:text-purple-400 mt-1">
                                This media is currently used in <span class="font-semibold">{{ $mediaAsset->pages_count }} pages</span>.
                                Changes to alt text will update across all pages where this image is used.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Footer --}}
        <div class="border-t border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="text-sm text-gray-500 dark:text-zinc-400">
                <svg class="h-4 w-4 inline-block mr-1 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Last modified: {{ $mediaAsset->updated_at->format('M d, Y H:i') }}
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                <flux:button
                    href="{{ route('admin.media.index') }}"
                    wire:navigate
                    variant="outline"
                    class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium rounded-lg border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors"
                >
                    <svg class="h-4 w-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Cancel
                </flux:button>

                <flux:button
                    wire:click="save"
                    variant="primary"
                    class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium rounded-lg bg-purple-600 hover:bg-purple-700 text-white shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>
                        <svg class="h-4 w-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Changes
                    </span>
                    <span wire:loading class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-1.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Saving...
                    </span>
                </flux:button>
            </div>
        </div>
    </div>
</div>
