<div class="w-full max-w-2xl mx-auto px-4 sm:px-6 py-6 bg-gray-50 dark:bg-zinc-900">
    {{-- Header --}}
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 mb-4 mx-auto">
            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
            Upload New Media
        </h1>
        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2 max-w-md mx-auto">
            Upload images, documents, and other files to your media library. Supported formats include JPG, PNG, GIF, PDF, and DOCX.
        </p>
    </div>

    {{-- Upload Card --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">
        {{-- File Upload Section --}}
        <div class="border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50 p-6">
            <div class="text-center">
                <input
                    id="file-upload"
                    type="file"
                    wire:model="file"
                    class="hidden"
                    accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx"
                >
                <div
                    class="relative mx-auto mb-4"
                    x-data="{ dragging: false }"
                    @dragover.prevent="dragging = true"
                    @dragleave.prevent="dragging = false"
                    @drop.prevent="dragging = false"
                >
                        <label for="file-upload" class="block cursor-pointer h-full">
                        <div class="h-48 w-48 mx-auto rounded-2xl border-2 border-dashed
                                   transition-all duration-300
                                   @if($file) border-blue-500 bg-blue-50 dark:bg-blue-900/30 @else border-gray-300 dark:border-zinc-600 bg-gray-50 dark:bg-zinc-700/50 @endif
                                   group-hover:border-blue-400
                                   :class='{
                                       "border-blue-500 bg-blue-50 dark:bg-blue-900/30": dragging || @entangle("file"),
                        "border-gray-300 dark:border-zinc-600 bg-gray-50 dark:bg-zinc-700/50": !(dragging || @entangle("file"))
                        }'">
                        <div class="h-full w-full rounded-2xl flex flex-col items-center justify-center p-4 pointer-events-none">
                            <div class="flex flex-col items-center justify-center text-center">
                                <svg class="h-10 w-10 text-gray-400 dark:text-zinc-500 mb-3 transition-colors duration-300
                                               @if($file) text-blue-500 dark:text-blue-400 @endif
                                               :class='{ "text-blue-500 dark:text-blue-400": dragging || @entangle("file"), "text-gray-400 dark:text-zinc-500": !(dragging || @entangle("file")) }'"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <div class="text-sm font-medium text-gray-700 dark:text-zinc-300 transition-colors duration-300
                                               @if($file) text-blue-700 dark:text-blue-300 @endif">
                                    @if($file)
                                        <span x-text="dragging ? 'Drop to upload' : 'File selected'"></span>
                                    @else
                                        <span x-text="dragging ? 'Drop to upload' : 'Drag & drop or click to upload'"></span>
                                    @endif
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400">
                                    PNG, JPG, GIF, PDF up to 10MB
                                </p>
                            </div>
                        </div>
                    </div>
                        </label>
                </div>

            @if($file)
                <div class="mt-4">
                    <div class="flex items-center justify-center gap-3 bg-blue-50 dark:bg-blue-900/30 py-2 px-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <svg class="h-4 w-4 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium text-blue-700 dark:text-blue-300 truncate max-w-xs">
                                    {{ $file->getClientOriginalName() }}
                                </span>
                        <button
                            type="button"
                            wire:click="removeFile"
                            class="ml-auto text-blue-500 hover:text-blue-700 dark:hover:text-blue-300"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

                @error('file')
                <div class="mt-2 text-sm text-rose-600 dark:text-rose-400 font-medium">
                    {{ $message }}
                </div>
                @enderror
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
                    class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    placeholder="Descriptive title for this media"
                >
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                    This appears in media library listings and helps with organization
                </p>
                @error('title')
                <p class="text-xs text-rose-600 dark:text-rose-400 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alt Text Field --}}
            <div class="space-y-2">
                <label for="alt_text" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    Alt Text <span class="text-gray-400 dark:text-zinc-500">(required for images)</span>
                </label>
                <textarea
                    wire:model.defer="alt_text"
                    id="alt_text"
                    rows="3"
                    class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                    placeholder="Describe this image for accessibility and SEO"
                ></textarea>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                    Describe the image content for screen readers and search engines
                </p>
                @error('alt_text')
                <p class="text-xs text-rose-600 dark:text-rose-400 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- File Info Preview --}}
            @if($file)
                <div class="mt-2 p-4 bg-gray-50 dark:bg-zinc-700/50 rounded-lg border border-gray-200 dark:border-zinc-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($this->isImageFile())
                                <div class="h-10 w-10 rounded-lg overflow-hidden bg-gray-200 dark:bg-zinc-600">
                                    <img
                                        src="{{ $file->temporaryUrl() }}"
                                        alt="Preview"
                                        class="h-full w-full object-cover"
                                        onerror="this.parentElement.classList.add('bg-gray-300', 'dark:bg-zinc-600'); this.style.display='none'"
                                    >
                                </div>
                            @else
                                <div class="h-10 w-10 rounded-lg bg-gray-200 dark:bg-zinc-600 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-gray-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $file->getClientOriginalName() }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">
                                    {{ number_format($file->getSize() / 1024, 1) }} KB • {{ pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION) }}
                                </p>
                            </div>
                        </div>
                        @if($this->isImageFile() && ($imageSize = $this->getImageSize()))
                            <div class="text-xs text-gray-500 dark:text-zinc-400">
                                {{ $imageSize['width'] }}x{{ $imageSize['height'] }} px
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Action Footer --}}
    <div class="border-t border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="text-sm text-gray-500 dark:text-zinc-400">
            <svg class="h-4 w-4 inline-block mr-1 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            All uploads are automatically optimized for web delivery
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
                class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center"
                wire:loading.attr="disabled"
            >
                    <span wire:loading.remove>
                        <svg class="h-4 w-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0L7 8m4-4v12" />
                        </svg>
                        Upload Media
                    </span>
                <span wire:loading class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-1.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Uploading...
                    </span>
            </flux:button>
        </div>
    </div>
</div>
</div>
