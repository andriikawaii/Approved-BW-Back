@php
    use App\Support\Sections\SectionRegistry;
    use Illuminate\Support\Str;

    $schema = SectionRegistry::rulesFor($section['type']);
    $meta = SectionRegistry::get($section['type']);

    // Group fields by category for better UX
    $groups = ['text' => [], 'media' => [], 'cta' => [], 'repeater' => [], 'other' => []];

    foreach ($schema as $field => $rules) {
        if (str_contains($field, '.*')) continue;

        // Determine editor type
        if (str_contains($rules, 'boolean')) {
            $editor = 'toggle';
        } elseif (
            str_contains($field, 'image') ||
            str_contains($field, 'media') ||
            str_contains($field, 'cover_') ||
            str_contains($field, 'logo') ||
            str_contains($field, 'avatar') ||
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

        // Categorize
        if ($editor === 'repeater') {
            $group = 'repeater';
        } elseif ($editor === 'media') {
            $group = 'media';
        } elseif (str_contains($field, 'cta') || str_contains($field, 'button')) {
            $group = 'cta';
        } elseif (in_array($editor, ['text', 'select', 'toggle'])) {
            $group = 'text';
        } else {
            $group = 'other';
        }

        $groups[$group][$field] = ['rules' => $rules, 'editor' => $editor];
    }

    // Remove empty groups
    $groups = array_filter($groups);

    $groupLabels = [
        'text' => 'Content',
        'media' => 'Media',
        'cta' => 'Call to Action',
        'repeater' => 'Items',
        'other' => 'Settings',
    ];
@endphp

<div class="space-y-6">

    @forelse ($groups as $groupKey => $fields)
        <div class="space-y-4">
            @if (count($groups) > 1)
                <div class="text-xs font-semibold uppercase tracking-wider text-zinc-500 border-b border-zinc-800 pb-1">
                    {{ $groupLabels[$groupKey] ?? Str::headline($groupKey) }}
                </div>
            @endif

            @foreach ($fields as $field => $fieldMeta)
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-zinc-300">
                        {{ Str::headline($field) }}
                    </label>

                    @include("livewire.admin.pages.sections.{$fieldMeta['editor']}", [
                        'section' => $section,
                        'index' => $index,
                        'field' => $field,
                        'rules' => $fieldMeta['rules'],
                    ])
                </div>
            @endforeach
        </div>
    @empty
        <div class="text-sm text-zinc-500">
            No editable fields defined for this section.
        </div>
    @endforelse

</div>
