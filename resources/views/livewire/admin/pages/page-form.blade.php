<div class="space-y-6">

    {{-- BASIC --}}
    <div class="card">
        <h3>Basic</h3>

        <input wire:model.defer="page.full_path" placeholder="/services/roofing" />
        <input wire:model.defer="page.template_key" placeholder="service" />

        <select wire:model.defer="page.status">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>

        <input type="datetime-local" wire:model.defer="page.published_at" />
    </div>

    {{-- RELATIONS --}}
    <div class="card">
        <h3>Relations</h3>

        <select wire:model.defer="page.service_id">
            <option value="">— Service —</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}">{{ $service->name }}</option>
            @endforeach
        </select>

        <select wire:model.defer="page.county_id">
            <option value="">— County —</option>
            @foreach($counties as $county)
                <option value="{{ $county->id }}">{{ $county->name }}</option>
            @endforeach
        </select>

        <select wire:model.defer="page.town_id">
            <option value="">— Town —</option>
            @foreach($towns as $town)
                <option value="{{ $town->id }}">{{ $town->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- HERO --}}
    <div class="card">
        <h3>Hero Media</h3>

        <select wire:model.defer="page.hero_media_id">
            <option value="">— None —</option>
            @foreach($media as $item)
                <option value="{{ $item->id }}">
                    {{ $item->title ?? $item->file_name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- SEO --}}
    <div class="card">
        <h3>SEO</h3>

        <input wire:model.defer="page.seo_title" placeholder="SEO Title" />
        <textarea wire:model.defer="page.seo_description" placeholder="SEO Description"></textarea>
        <input wire:model.defer="page.canonical_url" placeholder="https://example.com/page" />
    </div>

    {{-- SCHEMA --}}
    <div class="card">
        <h3>Schema</h3>

        <input wire:model.defer="page.schema_type" placeholder="LocalBusiness" />
        <textarea wire:model.defer="page.schema_overrides" placeholder='{"@type":"LocalBusiness"}'></textarea>
    </div>

    {{-- CONTENT --}}
    <div class="card">
        <h3>Content (JSON)</h3>

        <textarea
            wire:model.defer="page.content"
            rows="10"
            placeholder='{"intro":"Text","cta":"Call us"}'
        ></textarea>
    </div>

    <button wire:click="save" class="btn-primary">
        Save Page
    </button>

</div>
