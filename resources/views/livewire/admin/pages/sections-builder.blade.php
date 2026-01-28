<div class="space-y-6">

    {{-- SUCCESS --}}
    @if (session()->has('success'))
        <div class="rounded-lg border border-emerald-600/30 bg-emerald-600/10 px-4 py-3 text-sm text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- ADD SECTION --}}
    <div class="rounded-xl border border-zinc-700 bg-zinc-900 p-4">
        <div class="flex gap-3">
            <select wire:model="newSectionType"
                    class="flex-1 rounded-md border border-zinc-700 bg-zinc-800 px-3 py-2 text-sm text-white">
                <option value="">Add section...</option>
                @foreach ($sectionRegistry as $type => $meta)
                    <option value="{{ $type }}">{{ $meta['label'] }}</option>
                @endforeach
            </select>

            <button type="button"
                    wire:click="addSection"
                    class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-black">
                Add
            </button>
        </div>
    </div>

    {{-- SECTIONS --}}
    <div class="space-y-3">

        @foreach ($sections as $index => $section)
            <div wire:key="section-{{ $index }}"
                 class="rounded-xl border border-zinc-700 bg-zinc-900 overflow-hidden">

                {{-- HEADER --}}
                <div class="flex justify-between items-center px-4 py-3 border-b border-zinc-700">

                    <div>
                        <div class="text-white font-medium">
                            {{ $sectionRegistry[$section['type']]['label'] ?? $section['type'] }}
                        </div>
                        <div class="text-xs text-zinc-400">{{ $section['type'] }}</div>
                    </div>

                    <div class="flex gap-2 items-center">
                        <button type="button"
                                wire:click="toggleCollapse({{ $index }})"
                                class="px-2 py-1 text-xs rounded-md
                                {{ ($collapsed[$index] ?? true)
                                    ? 'bg-zinc-700 text-zinc-300'
                                    : 'bg-emerald-600 text-white' }}">
                            {{ ($collapsed[$index] ?? true) ? 'Open' : 'Close' }}
                        </button>

                        <button type="button" wire:click="moveUp({{ $index }})"
                                class="px-2 py-1 bg-zinc-700 text-white rounded">
                            &uarr;
                        </button>

                        <button type="button" wire:click="moveDown({{ $index }})"
                                class="px-2 py-1 bg-zinc-700 text-white rounded">
                            &darr;
                        </button>

                        <button type="button"
                                wire:click="toggleActive({{ $index }})"
                                class="px-3 py-1 text-xs rounded-md
                                {{ $section['is_active'] ? 'bg-emerald-600' : 'bg-zinc-700' }} text-white">
                            {{ $section['is_active'] ? 'Visible' : 'Hidden' }}
                        </button>

                        <button type="button"
                                wire:click="duplicateSection({{ $index }})"
                                class="px-3 py-1 text-xs bg-zinc-700 text-white rounded">
                            Duplicate
                        </button>

                        <button type="button"
                                wire:click="deleteSection({{ $index }})"
                                class="px-3 py-1 text-xs bg-red-600 text-white rounded">
                            Delete
                        </button>
                    </div>
                </div>

                {{-- BODY (always rendered, hidden via CSS to preserve Livewire state) --}}
                <div class="p-4 bg-zinc-950 {{ ($collapsed[$index] ?? true) ? 'hidden' : '' }}">
                    @include('livewire.admin.pages.sections.generic', [
                        'section' => $section,
                        'index' => $index,
                    ])
                </div>

            </div>
        @endforeach
    </div>

    {{-- SAVE --}}
    @if (count($sections))
        <div class="flex justify-end pt-4">
            <button type="button"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    wire:target="save"
                    class="rounded-md bg-white px-6 py-2 text-sm font-semibold text-black disabled:opacity-50">
                <span wire:loading.remove wire:target="save">Save sections</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    @endif

    {{-- MEDIA PICKER MODAL --}}
    @if ($showMediaModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" wire:click.self="closeMediaPicker">
            <div class="w-full max-w-3xl max-h-[80vh] rounded-xl bg-zinc-900 border border-zinc-700 shadow-2xl flex flex-col overflow-hidden">

                {{-- Modal header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-zinc-700">
                    <h3 class="text-lg font-semibold text-white">Select Image</h3>
                    <button type="button" wire:click="closeMediaPicker" class="text-zinc-400 hover:text-white text-xl leading-none">&times;</button>
                </div>

                {{-- Search --}}
                <div class="px-5 py-3 border-b border-zinc-800">
                    <input type="text"
                           wire:model.live.debounce.300ms="mediaSearch"
                           placeholder="Search media..."
                           class="w-full rounded-md border border-zinc-700 bg-zinc-800 px-3 py-2 text-sm text-white placeholder-zinc-500" />
                </div>

                {{-- Grid --}}
                <div class="flex-1 overflow-y-auto p-5">
                    @if ($mediaItems->count())
                        <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-3">
                            @foreach ($mediaItems as $media)
                                <button type="button"
                                        wire:click="selectMedia({{ $media->id }})"
                                        class="group relative aspect-square rounded-lg overflow-hidden border-2 border-transparent hover:border-emerald-500 focus:border-emerald-500 bg-zinc-800">
                                    <img src="{{ $media->url }}"
                                         alt="{{ $media->alt_text ?? $media->file_name }}"
                                         class="w-full h-full object-cover" />
                                    <div class="absolute inset-x-0 bottom-0 bg-black/70 px-1 py-0.5 text-[10px] text-zinc-300 truncate opacity-0 group-hover:opacity-100 transition">
                                        {{ $media->file_name }}
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-sm text-zinc-500 py-12">No images found.</div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
