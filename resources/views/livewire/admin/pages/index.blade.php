<div class="w-full px-4 sm:px-6 py-6 bg-gray-50 dark:bg-zinc-900">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                Pages Management
            </h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                Manage all CMS pages and templates
            </p>
        </div>

        <flux:button
            href="{{ route('admin.pages.create') }}"
            wire:navigate
            variant="primary"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200"
        >
            <svg class="h-4 w-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create New Page
        </flux:button>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        @php
            $stats = [
                [
                    'label' => 'Total Pages',
                    'value' => $pages->count(),
                    'color' => 'text-blue-600 dark:text-blue-400',
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                ],
                [
                    'label' => 'Published',
                    'value' => $pages->where('status', 'published')->count(),
                    'color' => 'text-emerald-600 dark:text-emerald-400',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                ],
                [
                    'label' => 'Drafts',
                    'value' => $pages->where('status', 'draft')->count(),
                    'color' => 'text-amber-600 dark:text-amber-400',
                    'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                ],
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="relative bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 p-5 overflow-hidden group shadow-sm hover:shadow-md transition-shadow">
                <div class="absolute top-3 right-3 opacity-10 dark:opacity-15 group-hover:opacity-20 transition-opacity">
                    <svg class="h-8 w-8 {{ $stat['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}" />
                    </svg>
                </div>

                <div class="relative z-10">
                    <p class="text-sm text-gray-500 dark:text-zinc-400 font-medium">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-bold mt-1 {{ $stat['color'] }}">
                        {{ $stat['value'] }}
                    </p>
                </div>

                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r {{ str_replace('text-', 'from-', $stat['color']) }} to-transparent"></div>
            </div>
        @endforeach
    </div>

    {{-- Table Container --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden shadow-sm">
        @if($pages->isEmpty())
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center h-14 w-14 rounded-xl bg-blue-50 dark:bg-blue-900/30 mb-4 mx-auto">
                    <svg class="h-7 w-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">No pages created yet</h3>
                <p class="text-gray-500 dark:text-zinc-400 mb-6 max-w-md mx-auto">
                    Create your first page to start building your content ecosystem. Pages can be organized into paths and assigned different templates.
                </p>
                <flux:button
                    href="{{ route('admin.pages.create') }}"
                    wire:navigate
                    variant="primary"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200"
                >
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create First Page
                </flux:button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 dark:bg-zinc-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Path</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Template</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Last Updated</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600 dark:text-zinc-300">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @foreach($pages as $page)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-mono font-medium text-gray-900 dark:text-white">
                                    {{ $page->full_path }}
                                </div>
                                <div class="mt-1 flex flex-wrap gap-1.5">
                                    @if($page->county)
                                        <span class="inline-flex items-center rounded-md bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 px-2 py-0.5 text-xs font-medium">
        {{ $page->county->name }}
    </span>
                                    @endif
                                        <span class="text-xs px-2 py-0.5 rounded bg-gray-100 dark:bg-zinc-700 text-gray-600 dark:text-zinc-400">
                                                No counties assigned
                                            </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-zinc-700 text-gray-800 dark:text-zinc-300">
                                        {{ $page->template_key }}
                                    </span>
                            </td>
                            <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $page->status === 'published'
                                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'
                                            : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300' }}">
                                        {{ ucfirst($page->status) }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-zinc-400 text-sm">
                                {{ $page->updated_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <flux:button
                                    href="{{ route('admin.pages.edit', $page) }}"
                                    wire:navigate
                                    variant="ghost"
                                    size="sm"
                                    class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </flux:button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($pages, 'hasPages') && $pages->hasPages())
                <div class="border-t border-gray-200 dark:border-zinc-700 px-5 py-4 bg-gray-50 dark:bg-zinc-700/30">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-gray-600 dark:text-zinc-300">
                            Showing {{ $pages->firstItem() }} to {{ $pages->lastItem() }} of {{ $pages->total() }} pages
                        </div>
                        <div>
                            {{ $pages->links() }}
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
