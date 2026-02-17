@php
    use Illuminate\Support\Str;
@endphp

<div class="min-h-screen w-full bg-panel text-ink">

    {{-- ===== STICKY HEADER ===== --}}
    <div class="sticky top-0 z-30 border-b border-edge bg-panel/80 backdrop-blur-xl">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Top row: nav + title + actions --}}
            <div class="flex items-center gap-4 py-4">
                <a
                    href="{{ route('admin.pages.index') }}"
                    wire:navigate
                    class="group inline-flex h-9 w-9 items-center justify-center rounded-lg border border-edge bg-surface-alt text-ink-muted transition hover:border-edge-strong hover:bg-surface hover:text-ink focus:outline-none focus:ring-2 focus:ring-tint/40"
                    aria-label="Back to pages"
                >
                    <svg class="h-4 w-4 transition group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-3">
                        <h1 class="truncate text-lg font-semibold text-ink">
                            {{ $page->full_path === '/' ? 'Home Page' : $page->full_path }}
                        </h1>
                        <span class="inline-flex shrink-0 items-center rounded-md px-2 py-0.5 text-[11px] font-semibold {{ $status === 'published' ? 'bg-emerald-500/15 text-emerald-400 ring-1 ring-emerald-500/25' : 'bg-amber-500/15 text-amber-400 ring-1 ring-amber-500/25' }}">
                            {{ $this->publishStatusLabel }}
                        </span>
                    </div>
                    <p class="mt-0.5 text-xs text-ink-faint">
                        ID #{{ $page->id }} &middot; Updated {{ $page->updated_at->diffForHumans() }}
                    </p>
                </div>

                <a
                    href="{{ \App\Support\PreviewUrl::forPage($page) }}"
                    target="_blank"
                    rel="noreferrer"
                    class="hidden items-center gap-2 rounded-lg border border-edge bg-surface-alt px-3.5 py-2 text-sm font-medium text-ink-muted transition hover:border-edge-strong hover:bg-surface hover:text-ink focus:outline-none focus:ring-2 focus:ring-tint/40 sm:inline-flex"
                >
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    Preview
                </a>
            </div>

            {{-- Tab bar --}}
            <nav class="-mb-px flex gap-1" aria-label="Page editor tabs">
                @foreach ([
                    'builder'  => ['label' => 'Sections', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z"/>'],
                    'settings' => ['label' => 'Settings', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                    'seo'      => ['label' => 'SEO', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>'],
                ] as $key => $tabInfo)
                    <button
                        type="button"
                        wire:click="$set('tab', '{{ $key }}')"
                        aria-label="Open {{ $tabInfo['label'] }}"
                        class="relative flex items-center gap-2 rounded-t-lg px-4 py-2.5 text-sm font-medium transition-all focus:outline-none {{ $tab === $key ? 'bg-surface text-ink' : 'text-ink-muted hover:bg-surface/50 hover:text-ink' }}"
                    >
                        <svg class="h-4 w-4 {{ $tab === $key ? 'text-tint' : '' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">{!! $tabInfo['icon'] !!}</svg>
                        {{ $tabInfo['label'] }}
                        @if ($tab === $key)
                            <span class="absolute inset-x-0 -bottom-px h-0.5 rounded-full bg-tint"></span>
                        @endif
                    </button>
                @endforeach
            </nav>
        </div>
    </div>

    {{-- ===== CONTENT ===== --}}
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- Template selector (compact) --}}
        <div class="mb-6 flex flex-wrap items-end gap-4 rounded-xl border border-edge bg-surface p-4">
            <div class="min-w-[200px] flex-1 sm:max-w-xs">
                <label for="header_template_key" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-ink-faint">Template</label>
                <select
                    id="header_template_key"
                    wire:model.defer="template_key"
                    class="w-full rounded-lg border border-edge bg-surface-alt px-3 py-2 text-sm text-ink transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30"
                >
                    @foreach($templates as $template)
                        <option value="{{ $template }}">{{ Str::headline($template) }}</option>
                    @endforeach
                </select>
            </div>
            <p class="pb-1 text-xs text-ink-faint">{{ $this->templateDescription }}</p>
        </div>

        {{-- Rendering preview --}}
        @include('livewire.admin.pages.partials.rendering-preview-panel', [
            'templateKey' => $template_key,
            'footerVariant' => $this->footerVariant,
            'phoneLogic' => $this->phoneLogic,
            'urlDepth' => $this->urlDepth,
            'publishStatus' => $this->publishStatusLabel,
        ])

        <div class="mt-4 mb-6">
            @include('livewire.admin.pages.partials.how-this-page-works')
        </div>

        {{-- ===== TAB CONTENT ===== --}}

        @if ($tab === 'builder')
            <livewire:admin.pages.sections-builder :page="$page" />
        @endif

        @if ($tab === 'settings')
            <div class="max-w-3xl">
                <form wire:submit.prevent="save" class="overflow-hidden rounded-xl border border-edge bg-surface shadow-lg">
                    <div class="border-b border-edge px-6 py-4">
                        <h2 class="text-base font-semibold text-ink">Page Settings</h2>
                        <p class="mt-0.5 text-xs text-ink-faint">URL path, publish status, and entity relations.</p>
                    </div>

                    <div class="grid gap-5 p-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="full_path" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-ink-faint">URL Path</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-ink-faint">/</span>
                                <input
                                    id="full_path"
                                    type="text"
                                    wire:model.defer="full_path"
                                    class="w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 pl-7 text-sm text-ink transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30"
                                    placeholder="kitchen-remodeling/greenwich-ct"
                                />
                            </div>
                            @error('full_path')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-ink-faint">Status</label>
                            <select id="status" wire:model.defer="status" class="w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                            @error('status')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="published_at" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-ink-faint">Publish Date</label>
                            <input id="published_at" type="datetime-local" wire:model.defer="published_at" class="w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30" />
                            @error('published_at')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="service_id" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-ink-faint">Service</label>
                            <select id="service_id" wire:model.defer="service_id" class="w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30">
                                <option value="">None</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                            @error('service_id')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="county_id" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-ink-faint">County</label>
                            <select id="county_id" wire:model.defer="county_id" class="w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30">
                                <option value="">None</option>
                                @foreach($counties as $county)
                                    <option value="{{ $county->id }}">{{ $county->name }}</option>
                                @endforeach
                            </select>
                            @error('county_id')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="town_id" class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-ink-faint">Town</label>
                            <select id="town_id" wire:model.defer="town_id" class="w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink transition focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30">
                                <option value="">None</option>
                                @foreach($towns as $town)
                                    <option value="{{ $town->id }}">{{ $town->name }}</option>
                                @endforeach
                            </select>
                            @error('town_id')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-edge bg-surface-alt/50 px-6 py-4">
                        <a
                            href="{{ route('admin.pages.index') }}"
                            wire:navigate
                            class="rounded-lg border border-edge bg-surface-alt px-4 py-2 text-sm font-medium text-ink-muted transition hover:bg-surface hover:text-ink focus:outline-none focus:ring-2 focus:ring-tint/40"
                        >
                            Cancel
                        </a>
                        <button
                            type="submit"
                            class="rounded-lg bg-tint px-5 py-2 text-sm font-medium text-tint-on shadow-lg shadow-tint/20 transition hover:bg-tint-hover focus:outline-none focus:ring-2 focus:ring-tint/40"
                        >
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        @endif

        @if ($tab === 'seo')
            <div class="max-w-5xl">
                <div class="overflow-hidden rounded-xl border border-edge bg-surface shadow-lg">
                    <div class="border-b border-edge px-6 py-4">
                        <h2 class="text-base font-semibold text-ink">SEO & Meta</h2>
                        <p class="mt-0.5 text-xs text-ink-faint">Search engine optimization settings for this page.</p>
                    </div>
                    <div class="p-6">
                        <livewire:admin.pages.page-seo-editor :page="$page" />
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
