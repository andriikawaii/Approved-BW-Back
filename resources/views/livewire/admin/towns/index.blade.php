<div class="w-full px-4 sm:px-6 py-6 bg-gray-50 dark:bg-zinc-900">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                Towns Management
            </h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">
                Manage town listings and their properties
            </p>
        </div>

        <flux:button
            href="{{ route('admin.towns.create') }}"
            wire:navigate
            variant="primary"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200"
        >
            <svg class="h-4 w-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Town
        </flux:button>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-5 mb-8">
        @php
            $stats = [
                [
                    'label' => 'Total Towns',
                    'value' => method_exists($towns, 'total') ? $towns->total() : $towns->count(),
                    'color' => 'text-blue-600 dark:text-blue-400',
                    'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                ],
                [
                    'label' => 'Active',
                    'value' => $towns->where('is_active', true)->count(),
                    'color' => 'text-emerald-600 dark:text-emerald-400',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                ],
                [
                    'label' => 'Hub Pages',
                    'value' => $towns->where('has_hub_page', true)->count(),
                    'color' => 'text-purple-600 dark:text-purple-400',
                    'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4',
                ],
                [
                    'label' => 'Avg Tier',
                    'value' => number_format((float) $towns->avg('tier'), 1),
                    'color' => 'text-amber-600 dark:text-amber-400',
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                ]
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

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 p-5 mb-8 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-end gap-4">
            <div class="w-full sm:w-80">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                    County Filter
                </label>
                <select
                    wire:model="countyId"
                    class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-3 py-2 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">All Counties</option>
                    @foreach($counties as $county)
                        <option value="{{ $county->id }}">{{ $county->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1"></div>

            <flux:button
                variant="outline"
                wire:click="resetFilters"
                class="border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-zinc-300 hover:bg-gray-50 dark:hover:bg-zinc-700/50
                       px-4 py-2 text-sm font-medium rounded-lg transition-colors"
            >
                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Reset Filters
            </flux:button>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden shadow-sm">
        @if($towns->count() > 0)
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 dark:bg-zinc-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Town</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">County</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Tier</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Hub</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 dark:text-zinc-300">Updated</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600 dark:text-zinc-300">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @foreach($towns as $town)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $town->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400 text-sm">
                                {{ $town->county->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $town->tier == 1
                                            ? 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-300'
                                            : ($town->tier == 2
                                                ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'
                                                : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300') }}">
                                        Tier {{ $town->tier }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium
                                        {{ $town->has_hub_page
                                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'
                                            : 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-300' }}">
                                        {{ $town->has_hub_page ? 'Yes' : 'No' }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $town->is_active
                                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'
                                            : 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-300' }}">
                                        {{ $town->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-zinc-400 text-sm">
                                {{ $town->updated_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button
                                        href="{{ route('admin.towns.edit', $town) }}"
                                        wire:navigate
                                        variant="ghost"
                                        size="sm"
                                        class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </flux:button>
                                    <flux:button
                                        wire:click="toggleActive({{ $town->id }})"
                                        variant="ghost"
                                        size="sm"
                                        class="text-gray-400 hover:text-{{ $town->is_active ? 'rose-600' : 'emerald-600' }}
                                                   dark:hover:text-{{ $town->is_active ? 'rose-400' : 'emerald-400' }} transition-colors"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="{{ $town->is_active ? 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.98 9.98 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 9v.75m-9.303 5.087A9.959 9.959 0 013 12h.75' : 'M15 12a3 3 0 11-6 0 3 3 0 016 0z' }}" />
                                        </svg>
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile View --}}
            <div class="lg:hidden divide-y divide-gray-200 dark:divide-zinc-700">
                @foreach($towns as $town)
                    <div class="p-5 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-medium text-gray-900 dark:text-white">{{ $town->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-zinc-400">
                                    {{ $town->county->name }}
                                </p>
                            </div>
                            <div class="flex items-start gap-1">
                                <flux:button
                                    href="{{ route('admin.towns.edit', $town) }}"
                                    wire:navigate
                                    variant="ghost"
                                    size="sm"
                                    class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 p-1"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </flux:button>
                                <flux:button
                                    wire:click="toggleActive({{ $town->id }})"
                                    variant="ghost"
                                    size="sm"
                                    class="text-gray-400 hover:text-{{ $town->is_active ? 'rose-600' : 'emerald-600' }}
                                           dark:hover:text-{{ $town->is_active ? 'rose-400' : 'emerald-400' }} p-1"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $town->is_active ? 'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.98 9.98 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 9v.75m-9.303 5.087A9.959 9.959 0 013 12h.75' : 'M15 12a3 3 0 11-6 0 3 3 0 016 0z' }}" />
                                    </svg>
                                </flux:button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">Tier</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1
                                    {{ $town->tier == 1
                                        ? 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-300'
                                        : ($town->tier == 2
                                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300'
                                            : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300') }}">
                                    {{ $town->tier }}
                                </span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-zinc-400">Hub Page</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1
                                    {{ $town->has_hub_page
                                        ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'
                                        : 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-300' }}">
                                    {{ $town->has_hub_page ? 'Yes' : 'No' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between sm:gap-4 pt-2 border-t border-gray-200 dark:border-zinc-700">
                            <div class="mt-3 sm:mt-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium
                                    {{ $town->is_active
                                        ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300'
                                        : 'bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-300' }}">
                                    {{ $town->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-zinc-400">
                                Updated: {{ $town->updated_at->format('M d') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center h-14 w-14 rounded-xl bg-blue-50 dark:bg-blue-900/30 mb-4 mx-auto">
                    <svg class="h-7 w-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M17 20H7m0-2v-2a5 5 0 0110 0v2M7 7a5 5 0 0110 0v2a5 5 0 01-10 0V7z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">No towns found</h3>
                <p class="text-gray-500 dark:text-zinc-400 mb-6 max-w-md mx-auto">
                    {{ $countyId
                        ? 'No towns match the selected county filter'
                        : 'Create your first town to get started managing your locations' }}
                </p>
                <flux:button
                    href="{{ route('admin.towns.create') }}"
                    wire:navigate
                    variant="primary"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 text-sm font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200"
                >
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ $countyId ? 'Create Town' : 'Add First Town' }}
                </flux:button>
            </div>
        @endif

        {{-- Pagination --}}
        @if(method_exists($towns, 'hasPages') && $towns->hasPages())
            <div class="border-t border-gray-200 dark:border-zinc-700 px-5 py-4 bg-gray-50 dark:bg-zinc-700/30">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600 dark:text-zinc-300">
                        Showing {{ $towns->firstItem() }} to {{ $towns->lastItem() }} of {{ $towns->total() }} results
                    </div>
                    <div>
                        {{ $towns->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
