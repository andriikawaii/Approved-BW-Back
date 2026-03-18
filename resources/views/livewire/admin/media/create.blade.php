<div class="w-full max-w-4xl mx-auto px-4 sm:px-6 py-6 bg-gray-50 dark:bg-zinc-900">
    {{-- Header --}}
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 mb-4 mx-auto">
            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
            Upload Media
        </h1>
        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2 max-w-md mx-auto">
            Upload one or more files to your media library. Alt text is auto-generated from filenames and can be edited before saving.
        </p>
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">
        {{-- File Upload Zone --}}
        <div class="border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50 p-6">
            <div class="text-center">
                <input
                    id="file-upload"
                    type="file"
                    wire:model="files"
                    class="hidden"
                    accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.mp4,.mov,.avi,.webm"
                    multiple
                >
                <div
                    class="relative mx-auto mb-4"
                    x-data="{ dragging: false }"
                    @dragover.prevent="dragging = true"
                    @dragleave.prevent="dragging = false"
                    @drop.prevent="dragging = false"
                >
                    <label for="file-upload" class="block cursor-pointer">
                        <div class="w-full py-10 rounded-2xl border-2 border-dashed transition-all duration-300 border-gray-300 dark:border-zinc-600 bg-gray-50 dark:bg-zinc-700/50 hover:border-blue-400 dark:hover:border-blue-500"
                             :class="{ 'border-blue-500 bg-blue-50 dark:bg-blue-900/30': dragging }">
                            <div class="flex flex-col items-center justify-center pointer-events-none">
                                <svg class="h-10 w-10 text-gray-400 dark:text-zinc-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <div class="text-sm font-medium text-gray-700 dark:text-zinc-300">
                                    <span x-text="dragging ? 'Drop files here' : 'Drag & drop or click to select files'"></span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-zinc-400">
                                    PNG, JPG, GIF, PDF up to 10MB each &bull; Multiple files supported
                                </p>
                            </div>
                        </div>
                    </label>
                </div>

                @error('files')
                    <div class="mt-2 text-sm text-rose-600 dark:text-rose-400 font-medium">{{ $message }}</div>
                @enderror
                @error('files.*')
                    <div class="mt-2 text-sm text-rose-600 dark:text-rose-400 font-medium">{{ $message }}</div>
                @enderror

                {{-- Loading indicator --}}
                <div wire:loading wire:target="files" class="mt-3">
                    <div class="flex items-center justify-center gap-2 text-sm text-blue-600 dark:text-blue-400">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Processing files...
                    </div>
                </div>
            </div>
        </div>

        {{-- File List with Alt Text Editing --}}
        @if(count($files))
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ count($files) }} {{ count($files) === 1 ? 'file' : 'files' }} selected
                    </h2>
                </div>

                <div class="space-y-4">
                    @foreach($files as $index => $file)
                        <div class="flex gap-4 p-4 bg-gray-50 dark:bg-zinc-700/50 rounded-lg border border-gray-200 dark:border-zinc-600">
                            {{-- Preview --}}
                            <div class="flex-shrink-0">
                                @if($this->isImageFile($index))
                                    <div class="h-20 w-20 rounded-lg overflow-hidden bg-gray-200 dark:bg-zinc-600">
                                        <img
                                            src="{{ $file->temporaryUrl() }}"
                                            alt="Preview"
                                            class="h-full w-full object-cover"
                                            onerror="this.style.display='none'"
                                        >
                                    </div>
                                @else
                                    <div class="h-20 w-20 rounded-lg bg-gray-200 dark:bg-zinc-600 flex items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-400 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Info + Alt input --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $file->getClientOriginalName() }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-zinc-400 mt-0.5">
                                            {{ number_format($file->getSize() / 1024, 1) }} KB &bull; {{ strtoupper(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)) }}
                                        </p>
                                    </div>
                                    <button
                                        type="button"
                                        wire:click="removeFile({{ $index }})"
                                        class="flex-shrink-0 p-1 text-gray-400 hover:text-rose-500 dark:hover:text-rose-400 transition-colors"
                                        title="Remove file"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <label class="block text-xs font-medium text-gray-600 dark:text-zinc-400 mb-1">Alt Text</label>
                                    <input
                                        type="text"
                                        wire:model.defer="altTexts.{{ $index }}"
                                        class="w-full rounded-md border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-1.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                        placeholder="Describe this image for accessibility"
                                    >
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

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
                    :disabled="!count($files)"
                >
                    <span wire:loading.remove wire:target="save">
                        <svg class="h-4 w-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0L7 8m4-4v12" />
                        </svg>
                        Upload {{ count($files) }} {{ count($files) === 1 ? 'File' : 'Files' }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="animate-spin h-4 w-4 mr-1.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Uploading...
                    </span>
                </flux:button>
            </div>
        </div>
    </div>
</div>
