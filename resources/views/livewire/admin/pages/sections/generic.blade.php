@php
    use App\Support\Sections\SectionRegistry;

    $schema = SectionRegistry::rulesFor($section['type']);
@endphp

<div class="space-y-6">

    @forelse ($schema as $field => $rules)
        @continue(str_contains($field, '.*'))

        @php
            // Editor resolution (single source)
            if (str_contains($rules, 'boolean')) {
                $editor = 'toggle';
            } elseif (
                str_contains($field, 'image') ||
                str_contains($field, 'media') ||
                str_contains($rules, 'file')
            ) {
                $editor = 'media';
            } elseif (str_contains($rules, 'in:')) {
                $editor = 'select';
            } elseif (str_contains($rules, 'array')) {
                $editor = 'repeater';
            } else {
                $editor = 'text';
            }
        @endphp

        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                {{ \Illuminate\Support\Str::headline($field) }}
            </label>

            @include("livewire.admin.pages.sections.$editor", [
                'index' => $index,
                'field' => $field,
                'rules' => $rules,
            ])
        </div>

    @empty
        <div class="text-sm text-zinc-500">
            No editable fields defined for this section.
        </div>
    @endforelse

</div>
