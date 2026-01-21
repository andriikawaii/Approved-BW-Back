<div class="w-full max-w-3xl mx-auto px-4 sm:px-6 py-6 bg-gray-50 dark:bg-zinc-900">
    {{-- Header --}}
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 mb-4 mx-auto">
            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
            Create New Page
        </h1>
        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2 max-w-md mx-auto">
            Build your page structure with the right template and settings. Pages will be organized within your site architecture.
        </p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">
        <form wire:submit.prevent="save" class="p-6 space-y-6">
            {{-- Full Path Field --}}
            <div class="space-y-2">
                <label for="full_path" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    Full Path <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <span class="text-gray-400 dark:text-zinc-500 text-sm">/</span>
                    </div>
                    <input
                        type="text"
                        wire:model.defer="full_path"
                        id="full_path"
                        class="w-full pl-7 rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                        placeholder="kitchen-remodeling/greenwich-ct"
                        aria-describedby="path-helper"
                    >
                </div>
                <div id="path-helper" class="flex items-start gap-2">
                    <svg class="h-4 w-4 mt-0.5 text-blue-500 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs text-gray-500 dark:text-zinc-400">
                        Enter path without leading slash. Use kebab-case for URLs. This will determine where the page lives in your site structure.
                    </p>
                </div>
                @error('full_path')
                <p class="text-xs text-rose-600 dark:text-rose-400 font-medium mt-1 flex items-center">
                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Template Selection --}}
            <div class="space-y-2">
                <label for="template_key" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    Template <span class="text-rose-500">*</span>
                </label>
                <select
                    wire:model.defer="template_key"
                    id="template_key"
                    class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none"
                >
                    <option value="">Select a template</option>
                    @foreach($templates as $template)
                        <option value="{{ $template }}" class="capitalize">
                            {{ Str::headline($template) }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400 dark:text-zinc-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                    Choose the appropriate template for this page's layout and functionality.
                </p>
                @error('template_key')
                <p class="text-xs text-rose-600 dark:text-rose-400 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status Selection --}}
            <div class="space-y-2">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    Status
                </label>
                <select
                    wire:model.defer="status"
                    id="status"
                    class="w-full rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none"
                >
                    <option value="draft">Draft - Not visible to visitors</option>
                    <option value="published">Published - Visible to all visitors</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400 dark:text-zinc-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Draft
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Published
                        </span>
                    </div>
                </div>
                @error('status')
                <p class="text-xs text-rose-600 dark:text-rose-400 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- County Assignment --}}
            <div class="space-y-2 pt-2 border-t border-gray-200 dark:border-zinc-700">
                <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    Counties <span class="text-gray-400 dark:text-zinc-500">(optional)</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach($counties as $county)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <div class="relative">
                                <input
                                    type="checkbox"
                                    wire:model="selectedCounties"
                                    value="{{ $county->id }}"
                                    class="sr-only"
                                >
                                <div class="w-5 h-5 rounded border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-700 flex items-center justify-center transition-all group-hover:border-indigo-400">
                                    <div class="w-3 h-3 rounded-sm bg-indigo-600 hidden group-has-[:checked]:block transition-all"></div>
                                </div>
                            </div>
                            <span class="text-sm text-gray-700 dark:text-zinc-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $county->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">
                    Assign counties to make this page visible only in specific geographic locations. Leave unassigned for global visibility.
                </p>
            </div>

            {{-- Action Footer --}}
            <div class="pt-4 border-t border-gray-200 dark:border-zinc-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-sm text-gray-500 dark:text-zinc-400 flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Fields marked with <span class="text-rose-500">*</span> are required
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                    <flux:button
                        href="{{ route('admin.pages.index') }}"
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
                        type="submit"
                        variant="primary"
                        class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>
                            <svg class="h-4 w-4 mr-1.5 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Create Page
                        </span>
                        <span wire:loading class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-1.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Creating...
                        </span>
                    </flux:button>
                </div>
            </div>
        </form>
    </div>

    {{-- Helpful Tips --}}
    <div class="mt-8">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-5">
            <div class="flex items-start gap-3">
                <div class="mt-1 flex-shrink-0 h-5 w-5 rounded-full bg-blue-500"></div>
                <div class="text-sm">
                    <p class="font-medium text-blue-800 dark:text-blue-300">
                        Best Practices for Page Paths
                    </p>
                    <ul class="mt-2 space-y-1 text-blue-700 dark:text-blue-300 list-disc pl-5">
                        <li>Use kebab-case for readability (e.g., <code class="px-1 py-0.5 rounded bg-blue-100 dark:bg-blue-800/30 text-xs">kitchen-remodeling</code>)</li>
                        <li>Keep paths concise but descriptive</li>
                        <li>Nest pages logically (e.g., <code class="px-1 py-0.5 rounded bg-blue-100 dark:bg-blue-800/30 text-xs">/services/kitchen-remodeling</code>)</li>
                        <li>Avoid special characters and spaces</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
