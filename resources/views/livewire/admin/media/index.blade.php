<div class="w-full px-4 sm:px-6 py-6 bg-gray-50 dark:bg-zinc-900">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                Media Library
            </h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1 flex items-center gap-2">
                <svg class="h-4 w-4 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Manage and organize your uploaded media assets
            </p>
        </div>

        <flux:button
            href="{{ route('admin.media.create') }}"
            wire:navigate
            variant="primary"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200 flex items-center gap-2"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Upload New Media
        </flux:button>
    </div>

    {{-- Gallery Grid --}}
    @if($media->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5 mb-8">
            @foreach($media as $item)
                <div class="group relative rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                    {{-- Media Preview --}}
                    <div class="aspect-square bg-gray-100 dark:bg-zinc-700/50 flex items-center justify-center overflow-hidden relative">
                        @if($item->isImage())
                            <img
                                src="{{ $item->url }}"
                                alt="{{ $item->alt_text ?? $item->file_name }}"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                loading="lazy"
                            >
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center rounded-full bg-white/80 dark:bg-zinc-800 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/30">
                                    <svg class="h-3 w-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Image
                                </span>
                            </div>
                        @else
                            <div class="p-4 text-center">
                                <div class="inline-flex items-center justify-center h-16 w-16 rounded-xl bg-gray-200 dark:bg-zinc-700 mb-3 mx-auto">
                                    <svg class="h-8 w-8 text-gray-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-xs font-medium text-gray-600 dark:text-zinc-300 truncate px-1">
                                    {{ pathinfo($item->file_name, PATHINFO_EXTENSION) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                                    {{ number_format($item->size / 1024, 1) }} KB
                                </p>
                            </div>
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center rounded-full bg-white/80 dark:bg-zinc-800 px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-900/30">
                                    <svg class="h-3 w-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    File
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Metadata --}}
                    <div class="p-3 border-t border-gray-200 dark:border-zinc-700">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate" title="{{ $item->file_name }}">
                                    {{ $item->file_name }}
                                </p>
                                <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-gray-500 dark:text-zinc-400">
                                    <span class="flex items-center">
                                        <svg class="h-3 w-3 mr-0.5 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $item->created_at->format('M d, Y') }}
                                    </span>
                                    @if($item->isImage())
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-0.5 text-gray-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            {{ $item->width }}x{{ $item->height }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col justify-end p-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-6 w-6 rounded-full bg-white/90 flex items-center justify-center">
                                    <svg class="h-3 w-3 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-white">
                                    {{ $item->views_count }} views
                                </span>
                            </div>
                            <div class="flex gap-1.5">
                                <flux:button
                                    href="{{ route('admin.media.edit', $item) }}"
                                    wire:navigate
                                    size="sm"
                                    variant="ghost"
                                    class="text-white hover:bg-white/20 p-1.5"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </flux:button>
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    class="text-white hover:bg-white/20 p-1.5"
                                    wire:click="delete({{ $item->id }})"
                                    wire:confirm="Are you sure you want to delete this media file? This action cannot be undone."
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            <div class="border-t border-gray-200 dark:border-zinc-700 pt-6">
                {{ $media->links() }}
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="border-2 border-dashed rounded-xl border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-12 text-center">
            <div class="mx-auto h-16 w-16 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                No media files yet
            </h3>
            <p class="text-gray-500 dark:text-zinc-400 mb-6 max-w-md mx-auto">
                Upload your first image or file to start building your media library. Supported formats include JPG, PNG, GIF, PDF, and more.
            </p>
            <flux:button
                href="{{ route('admin.media.create') }}"
                wire:navigate
                variant="primary"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200 inline-flex items-center gap-2"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Upload Your First File
            </flux:button>
            <p class="mt-4 text-xs text-gray-400 dark:text-zinc-500">
                Max file size: 10MB • Supported formats: JPG, PNG, GIF, PDF, DOCX
            </p>
        </div>
    @endif
</div>
