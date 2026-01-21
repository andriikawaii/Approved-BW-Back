<div class="w-full px-4 sm:px-6 py-6 bg-gray-50 dark:bg-zinc-900">
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                Services Management
            </h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                Manage services used across pages and locations
            </p>
        </div>

        <flux:button
            href="{{ route('admin.services.create') }}"
            wire:navigate
            variant="primary"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200"
        >
            <svg class="h-4 w-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Service
        </flux:button>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @php
            $stats = [
                [
                    'label' => 'Total Services',
                    'value' => method_exists($services, 'total') ? $services->total() : $services->count(),
                    'color' => 'text-blue-600 dark:text-blue-400',
                    'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2z',
                ],
                [
                    'label' => 'Active',
                    'value' => $services->where('status','active')->count(),
                    'color' => 'text-emerald-600 dark:text-emerald-400',
                    'icon' => 'M9 12l2 2 4-4',
                ],
                [
                    'label' => 'Inactive',
                    'value' => $services->where('status','inactive')->count(),
                    'color' => 'text-amber-600 dark:text-amber-400',
                    'icon' => 'M10 14l2-2',
                ],
                [
                    'label' => 'Last Updated',
                    'value' => $services->count()
                        ? $services->sortByDesc('updated_at')->first()->updated_at->diffForHumans()
                        : 'Never',
                    'color' => 'text-purple-600 dark:text-purple-400',
                    'icon' => 'M12 8v4l3 3',
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

    {{-- FILTERS --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 p-5 mb-8 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            {{-- STATUS --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    Status
                </label>
                <select
                    wire:model="statusFilter"
                    class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">All statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            {{-- SORT --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    Sort by
                </label>
                <select
                    wire:model="sortBy"
                    class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="name">Name (A–Z)</option>
                    <option value="created_at">Newest</option>
                    <option value="updated_at">Recently updated</option>
                </select>
            </div>

            {{-- SEARCH --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    Search
                </label>
                <div class="relative rounded-lg">
                    <input
                        type="text"
                        wire:model.debounce.300ms="search"
                        placeholder="Search by name or slug"
                        class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-4 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE / LIST --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden shadow-sm">
        @if($services->count() > 0)
            {{-- DESKTOP TABLE --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 dark:bg-zinc-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Service</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Slug</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Updated</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600 dark:text-zinc-300">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @foreach($services as $service)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $service->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400 text-sm">
                                /{{ $service->slug }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $service->status === 'active'
                                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'
                                            : 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-300'
                                        }}">
                                        {{ ucfirst($service->status) }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400 text-sm">
                                {{ $service->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <flux:button
                                    href="{{ route('admin.services.edit', $service) }}"
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

            {{-- MOBILE CARDS --}}
            <div class="lg:hidden divide-y divide-gray-200 dark:divide-zinc-700">
                @foreach($services as $service)
                    <div class="p-5 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">{{ $service->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">
                                    /{{ $service->slug }}
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ $service->status === 'active'
                                    ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'
                                    : 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-300'
                                }}">
                                {{ ucfirst($service->status) }}
                            </span>
                        </div>

                        <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                            <div class="text-sm text-gray-500 dark:text-zinc-400">
                                Updated {{ $service->updated_at->diffForHumans() }}
                            </div>
                            <div class="mt-3 flex items-center sm:mt-0">
                                <flux:button
                                    href="{{ route('admin.services.edit', $service) }}"
                                    wire:navigate
                                    variant="ghost"
                                    size="sm"
                                    class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                >
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Edit
                                </flux:button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- EMPTY STATE --}}
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center h-14 w-14 rounded-xl bg-blue-50 dark:bg-blue-900/30 mb-4 mx-auto">
                    <svg class="h-7 w-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">No services found</h3>
                <p class="text-gray-500 dark:text-zinc-400 mb-6 max-w-md mx-auto">
                    {{ $search || $statusFilter || $sortBy
                        ? 'Try adjusting your filters or search terms'
                        : 'Create your first service to get started managing your content' }}
                </p>
                <flux:button
                    href="{{ route('admin.services.create') }}"
                    wire:navigate
                    variant="primary"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200"
                >
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ $search || $statusFilter || $sortBy ? 'Create Service' : 'Add First Service' }}
                </flux:button>
            </div>
        @endif

        {{-- PAGINATION --}}
        @if(method_exists($services, 'hasPages') && $services->hasPages())
            <div class="border-t border-gray-200 dark:border-zinc-700 px-5 py-4 bg-gray-50 dark:bg-zinc-700/30">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600 dark:text-zinc-300">
                        Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} results
                    </div>
                    <div>
                        {{ $services->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
