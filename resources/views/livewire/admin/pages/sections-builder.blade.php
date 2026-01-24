<div class="space-y-8">

    {{-- SUCCESS TOAST --}}
    @if (session()->has('success'))
        <div class="rounded-2xl bg-gradient-to-r from-emerald-500/10 to-emerald-600/10
                    backdrop-blur-sm border border-emerald-500/20
                    text-emerald-300 px-5 py-4 text-sm font-medium
                    shadow-lg shadow-emerald-900/10 animate-pulse-subtle">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- ADD SECTION CARD --}}
    <div class="relative rounded-3xl bg-gradient-to-br from-zinc-900/80 to-zinc-950/90
                border border-zinc-800/50 p-6 space-y-4
                shadow-2xl shadow-purple-900/5 backdrop-blur-sm">

        <!-- Glowing border effect -->
        <div class="absolute inset-0 rounded-3xl bg-gradient-to-r from-purple-500/5 via-transparent to-cyan-500/5 -z-10"></div>

        <!-- Animated gradient ring -->
        <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-purple-600/20 via-transparent to-cyan-600/20 blur-sm animate-gradient-xy -z-20"></div>

        <div class="flex items-center gap-3 mb-2">
            <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-purple-600/20 to-cyan-600/20
                        flex items-center justify-center border border-purple-500/20">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-xl bg-gradient-to-r from-white to-zinc-300 bg-clip-text text-transparent">
                    Add New Section
                </h3>
                <p class="text-sm text-zinc-400">Choose from available content blocks</p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 items-stretch">
            <div class="flex-1 relative group">
                <!-- Gradient border on focus -->
                <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-600 to-cyan-600 rounded-2xl opacity-0
                            group-focus-within:opacity-100 blur transition duration-300"></div>

                <select
                    wire:model="newSectionType"
                    class="relative w-full rounded-2xl bg-zinc-900/90 border-2 border-zinc-700/50
                           text-white px-5 py-4 text-sm focus:outline-none focus:border-transparent
                           backdrop-blur-sm transition-all duration-300
                           hover:border-zinc-600 hover:bg-zinc-900
                           group-focus-within:bg-zinc-950"
                >
                    <option value="" class="bg-zinc-900 text-zinc-400 py-2">✨ Choose a section type...</option>

                    @foreach ($groupedSections as $group => $items)
                        <optgroup label="{{ $group }}" class="bg-zinc-950 text-zinc-300 font-semibold">
                            @foreach ($items as $type => $meta)
                                <option value="{{ $type }}" class="bg-zinc-900 text-white py-3 hover:bg-purple-600">
                                    {{ $meta['label'] }}
                                    @if(isset($meta['description']))
                                        <span class="text-xs text-zinc-400 block">{{ $meta['description'] }}</span>
                                    @endif
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>

                <!-- Arrow icon -->
                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <button
                wire:click="addSection"
                class="relative px-8 py-4 rounded-2xl bg-gradient-to-r from-purple-600 to-cyan-600
                       text-white font-bold text-sm uppercase tracking-wider
                       hover:from-purple-500 hover:to-cyan-500
                       active:scale-[0.98] transition-all duration-300
                       shadow-lg shadow-purple-900/30 hover:shadow-purple-900/50
                       disabled:opacity-50 disabled:cursor-not-allowed
                       min-h-[56px] flex items-center justify-center gap-2"
                @disabled(!$newSectionType)
            >
                <!-- Glow effect -->
                <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-purple-400/20 to-cyan-400/20
                            blur-md opacity-0 hover:opacity-100 transition-opacity duration-300"></div>

                <span class="relative z-10">Add Section</span>
                <svg class="w-4 h-4 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- SECTIONS LIST --}}
    <div class="space-y-4" wire:sortable="reorderSections">

        @foreach ($sections as $index => $section)
            <div
                x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }"
                wire:key="section-{{ $index }}"
                wire:sortable.item="{{ $index }}"
                class="relative rounded-3xl bg-gradient-to-br from-zinc-900/60 to-zinc-950/80
                       border border-zinc-800/50 overflow-hidden
                       transition-all duration-500 hover:border-zinc-700/70 hover:shadow-2xl hover:shadow-purple-900/10
                       group/section"
            >
                <!-- Hover glow effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500/0 via-cyan-500/0 to-purple-500/0
                            group-hover/section:from-purple-500/5 group-hover/section:via-cyan-500/5 group-hover/section:to-purple-500/5
                            transition-all duration-700 -z-10"></div>

                <!-- Index badge -->
                <div class="absolute -left-3 -top-3 h-12 w-12 rounded-2xl bg-gradient-to-br from-purple-600 to-cyan-600
                            flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    {{ $index + 1 }}
                </div>

                {{-- HEADER --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-6 py-5
                            border-b border-zinc-800/50 bg-zinc-900/30">

                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <!-- Drag handle with animation -->
                        <div wire:sortable.handle
                             class="cursor-grab active:cursor-grabbing text-zinc-500 hover:text-zinc-300
                                    transition-all duration-300 hover:scale-110 select-none p-2
                                    rounded-xl hover:bg-zinc-800/50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 8h16M4 16h16"></path>
                            </svg>
                        </div>

                        <!-- Type icon -->
                        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-purple-600/20 to-cyan-600/20
                                    flex items-center justify-center border border-purple-500/20">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="font-bold text-lg text-white truncate">
                                {{ $sectionRegistry[$section['type']]['label'] ?? \Illuminate\Support\Str::headline($section['type']) }}
                            </div>
                            @if($sectionRegistry[$section['type']]['description'] ?? false)
                                <div class="text-sm text-zinc-400 truncate mt-1">
                                    {{ $sectionRegistry[$section['type']]['description'] }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3 flex-wrap">
                        <!-- Visibility toggle -->
                        <button
                            wire:click="toggleActive({{ $index }})"
                            class="relative px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300
                                   {{ $section['is_active']
                                        ? 'bg-gradient-to-r from-emerald-600 to-emerald-700 text-white shadow-lg shadow-emerald-900/30'
                                        : 'bg-gradient-to-r from-zinc-700 to-zinc-800 text-zinc-300' }}
                                   hover:scale-[1.02] active:scale-[0.98] min-w-[100px]"
                        >
                            <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-white/10 to-transparent opacity-0 hover:opacity-100 transition-opacity"></div>
                            <span class="relative flex items-center justify-center gap-2">
                                @if($section['is_active'])
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                @endif
                                {{ $section['is_active'] ? 'Visible' : 'Hidden' }}
                            </span>
                        </button>

                        <!-- Edit toggle -->
                        <button
                            @click="open = !open"
                            class="relative px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600/20 to-cyan-600/20
                                   text-white text-sm font-semibold border border-purple-500/30
                                   hover:from-purple-600/30 hover:to-cyan-600/30
                                   hover:border-purple-500/50 transition-all duration-300
                                   hover:scale-[1.02] active:scale-[0.98] min-w-[80px]"
                        >
                            <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-white/10 to-transparent opacity-0 hover:opacity-100 transition-opacity"></div>
                            <span class="relative flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span x-text="open ? 'Close' : 'Edit'"></span>
                            </span>
                        </button>

                        <!-- Action buttons -->
                        <div class="flex items-center gap-1">
                            <!-- Duplicate -->
                            <button
                                wire:click="duplicateSection({{ $index }})"
                                class="p-3 rounded-xl bg-gradient-to-br from-blue-600/20 to-cyan-600/20
                                       text-blue-400 hover:text-white hover:from-blue-600/30 hover:to-cyan-600/30
                                       border border-blue-500/20 hover:border-blue-500/40
                                       transition-all duration-300 hover:scale-110 active:scale-95"
                                title="Duplicate section"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>

                            <!-- Delete -->
                            <button
                                wire:click="deleteSection({{ $index }})"
                                onclick="return confirm('Are you sure you want to delete this section?')"
                                class="p-3 rounded-xl bg-gradient-to-br from-red-600/20 to-pink-600/20
                                       text-red-400 hover:text-white hover:from-red-600/30 hover:to-pink-600/30
                                       border border-red-500/20 hover:border-red-500/40
                                       transition-all duration-300 hover:scale-110 active:scale-95"
                                title="Delete section"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 011.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- BODY (Edit Panel) --}}
                <div x-show="open" x-collapse
                     class="transition-all duration-500 ease-in-out"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-4">
                    <div class="p-8 bg-gradient-to-b from-zinc-900/50 to-transparent">
                        @include('livewire.admin.pages.sections.generic', [
                            'section' => $section,
                            'index' => $index,
                        ])
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- SAVE BUTTON --}}
    @if(count($sections))
        <div class="relative flex justify-end pt-8">
            <!-- Animated background glow -->
            <div class="absolute inset-0 bg-gradient-to-r from-purple-600/10 via-transparent to-cyan-600/10
                        blur-xl opacity-50 animate-pulse-slow"></div>

            <button
                wire:click="save"
                wire:loading.attr="disabled"
                class="relative px-10 py-5 rounded-2xl bg-gradient-to-r from-purple-600 via-purple-700 to-cyan-600
                       text-white font-bold text-sm uppercase tracking-wider
                       hover:from-purple-500 hover:via-purple-600 hover:to-cyan-500
                       active:scale-[0.98] transition-all duration-300
                       shadow-2xl shadow-purple-900/50 hover:shadow-purple-900/70
                       disabled:opacity-50 disabled:cursor-not-allowed
                       group"
            >
                <!-- Glow effect -->
                <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-purple-400/30 to-cyan-400/30
                            blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                <!-- Shine effect -->
                <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-transparent via-white/10 to-transparent
                            -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>

                <span class="relative z-10 flex items-center gap-3">
                    <span wire:loading.remove wire:target="save">
                        💾 Save All Sections
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-3">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving Changes...
                    </span>
                </span>
            </button>
        </div>
    @endif

</div>
