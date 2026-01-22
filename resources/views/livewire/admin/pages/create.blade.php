<div class="w-full bg-gray-50 dark:bg-zinc-900 min-h-screen">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">

        <div class="mb-10 text-center">
            <div class="inline-flex items-center justify-center h-12 w-12 rounded-xl
                        bg-indigo-100 dark:bg-indigo-900/30 mb-4 mx-auto">
                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                             a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19
                             a2 2 0 01-2 2z" />
                </svg>
            </div>

            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                Create Page
            </h1>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-2">
                Define path, template and publishing rules.
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-2xl border
                    border-gray-200 dark:border-zinc-700 shadow-sm overflow-hidden">

            <form wire:submit.prevent="save" class="p-6 space-y-8">

                {{-- PATH --}}
                <div>
                    <label class="text-sm font-medium">Full path</label>
                    <input wire:model.defer="full_path"
                           class="w-full rounded-lg border px-4 py-2.5 text-sm"
                           placeholder="services/kitchen-remodeling">
                </div>

                {{-- TEMPLATE --}}
                <div>
                    <label class="text-sm font-medium">Template</label>
                    <select
                        wire:model.defer="template_key"
                        class="w-full rounded-lg border
           border-gray-300 dark:border-zinc-600
           bg-white dark:bg-zinc-700
           px-4 py-2.5 text-sm
           text-gray-900 dark:text-white
           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
           appearance-none">
                        <option value="" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">Select template</option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl }}" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">
                                {{ Str::headline($tpl) }}
                            </option>
                        @endforeach
                    </select>

                </div>

                {{-- STATUS --}}
                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select
                        wire:model.defer="status"
                        class="w-full rounded-lg border
           border-gray-300 dark:border-zinc-600
           bg-white dark:bg-zinc-700
           px-4 py-2.5 text-sm
           text-gray-900 dark:text-white
           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
           appearance-none">
                        <option value="draft" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">Draft</option>
                        <option value="published" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">Published</option>
                    </select>

                </div>

                {{-- RELATIONS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t">
                    <select
                        wire:model.defer="service_id"
                        class="rounded-lg border
           border-gray-300 dark:border-zinc-600
           bg-white dark:bg-zinc-700
           px-3 py-2 text-sm
           text-gray-900 dark:text-white
           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
           appearance-none">
                        <option value="" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">Service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>


                    <select
                        wire:model.defer="county_id"
                        class="rounded-lg border
           border-gray-300 dark:border-zinc-600
           bg-white dark:bg-zinc-700
           px-3 py-2 text-sm
           text-gray-900 dark:text-white
           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
           appearance-none">
                        <option value="" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">County</option>
                        @foreach($counties as $county)
                            <option value="{{ $county->id }}" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">
                                {{ $county->name }}
                            </option>
                        @endforeach
                    </select>


                    <select
                        wire:model.defer="town_id"
                        class="rounded-lg border
           border-gray-300 dark:border-zinc-600
           bg-white dark:bg-zinc-700
           px-3 py-2 text-sm
           text-gray-900 dark:text-white
           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
           appearance-none">
                        <option value="" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">Town</option>
                        @foreach($towns as $town)
                            <option value="{{ $town->id }}" class="bg-white text-gray-900 dark:bg-zinc-800 dark:text-white">
                                {{ $town->name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                {{-- ACTIONS --}}
                <div class="pt-6 border-t flex justify-end gap-3">
                    <flux:button href="{{ route('admin.pages.index') }}" wire:navigate variant="outline">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary" class="bg-indigo-600 hover:bg-indigo-700">
                        Create Page
                    </flux:button>
                </div>

            </form>
        </div>
    </div>
</div>
