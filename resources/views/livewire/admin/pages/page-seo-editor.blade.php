<div class="space-y-6">
    @if (session()->has('success'))
        <div class="rounded-lg border border-emerald-600/30 bg-emerald-600/10 px-4 py-3 text-sm font-medium text-emerald-300" role="status" aria-live="polite">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-xl border border-edge bg-surface p-6 shadow-sm">
            <div class="mb-4 flex items-center gap-2">
                <h3 class="text-base font-semibold text-ink">SEO Fields</h3>
                <span class="inline-flex items-center rounded-full bg-emerald-600/20 px-2.5 py-0.5 text-xs font-semibold text-emerald-400">
                    SEO Important
                </span>
            </div>
            <p class="mb-5 text-sm text-ink-muted">Preview how this appears in search results.</p>

            <div class="space-y-4">
                <div>
                    <label for="seo_title" class="mb-1 block text-sm font-medium text-ink-muted">SEO Title</label>
                    <input
                        id="seo_title"
                        type="text"
                        wire:model.defer="seo_title"
                        aria-label="SEO title"
                        maxlength="60"
                        class="w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30"
                        placeholder="Enter SEO title"
                    />
                    @error('seo_title')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-ink-muted">{{ strlen($seo_title) }}/60 characters</p>
                </div>

                <div>
                    <label for="seo_description" class="mb-1 block text-sm font-medium text-ink-muted">SEO Description</label>
                    <textarea
                        id="seo_description"
                        rows="4"
                        wire:model.defer="seo_description"
                        aria-label="SEO description"
                        maxlength="160"
                        class="w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30"
                        placeholder="Enter SEO description"
                    ></textarea>
                    @error('seo_description')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-ink-muted">{{ strlen($seo_description) }}/160 characters</p>
                </div>

                <div>
                    <label for="canonical_url" class="mb-1 block text-sm font-medium text-ink-muted">Canonical URL (Optional)</label>
                    <input
                        id="canonical_url"
                        type="text"
                        wire:model.defer="canonical_url"
                        aria-label="Canonical URL"
                        placeholder="https://example.com/page"
                        class="w-full rounded-lg border border-edge bg-surface-alt px-3 py-2.5 text-sm text-ink focus:border-tint focus:outline-none focus:ring-2 focus:ring-tint/30"
                    />
                    @error('canonical_url')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-5 flex justify-end">
                <button
                    type="button"
                    wire:click="save"
                    aria-label="Save SEO settings"
                    class="inline-flex items-center rounded-lg bg-tint px-4 py-2 text-sm font-medium text-tint-on transition hover:bg-tint-hover focus:outline-none focus:ring-2 focus:ring-tint/30"
                >
                    Save SEO
                </button>
            </div>
        </section>

        <section class="rounded-xl border border-edge bg-surface p-6 shadow-sm">
            <h3 class="mb-3 text-base font-semibold text-ink">Search Result Preview</h3>
            <div class="rounded-xl border border-edge bg-panel p-4">
                <p class="text-xs text-emerald-400">{{ $canonical_url ?: url($page->full_path) }}</p>
                <h4 class="mt-1 text-lg leading-tight text-tint">
                    {{ $seo_title ?: 'Page title will appear here' }}
                </h4>
                <p class="mt-1 text-sm text-ink-muted">
                    {{ $seo_description ?: 'Meta description will appear here. Keep it concise and relevant to improve click-through rate.' }}
                </p>
            </div>
            <p class="mt-3 text-xs text-ink-muted">This is an approximate desktop search snippet preview.</p>
        </section>
    </div>
</div>
