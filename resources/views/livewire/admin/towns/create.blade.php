<div class="max-w-xl space-y-6">
    <h1 class="text-2xl font-semibold">Create Town</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <select wire:model.defer="county_id" required class="w-full border rounded px-3 py-2">
            <option value="">Select county</option>
            @foreach ($counties as $county)
                <option value="{{ $county->id }}">{{ $county->name }}</option>
            @endforeach
        </select>

        <x-input label="Name" wire:model.defer="name" required />
        <x-input label="Slug" wire:model.defer="slug" required />

        <select wire:model="tier" class="w-full border rounded px-3 py-2">
            <option value="1">Tier 1</option>
            <option value="2">Tier 2</option>
        </select>

        <label class="flex items-center gap-2">
            <input type="checkbox" wire:model.defer="has_hub_page" {{ $tier == 2 ? 'disabled' : '' }}>
            <span>Has hub page (Tier 1 only)</span>
        </label>

        <x-input label="Sort order" type="number" wire:model.defer="sort_order" />

        <label class="flex items-center gap-2">
            <input type="checkbox" wire:model.defer="is_active">
            <span>Active</span>
        </label>

        <div class="flex gap-3">
            <button class="px-4 py-2 bg-primary text-white rounded">Save</button>
            <a href="{{ route('admin.towns.index') }}" class="px-4 py-2 border rounded">Cancel</a>
        </div>
    </form>
</div>
