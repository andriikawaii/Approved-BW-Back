@php
    use App\Support\Sections\SectionRegistry;

    $schema = SectionRegistry::rulesFor($section['type']);
@endphp

<div class="space-y-6">

    @forelse ($schema as $field => $rules)
        @continue(str_contains($field, '.*'))

        @php
            // Map validation → editor component
            if (str_contains($rules, 'boolean')) {
                $editor = 'toggle';
            } elseif (str_contains($rules, 'array')) {
                $editor = 'repeater';
            } elseif (str_contains($rules, 'in:')) {
                $editor = 'select';
            } elseif (str_contains($rules, 'image') || str_contains($rules, 'file')) {
                $editor = 'media';
            } else {
                $editor = 'text';
            }
        @endphp

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-1">
                {{ \Illuminate\Support\Str::headline($field) }}
            </label>

            @includeIf("livewire.admin.pages.sections.$editor", [
                'section' => $section,
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
