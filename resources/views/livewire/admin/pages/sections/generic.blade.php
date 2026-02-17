@php
    use App\Support\Sections\SectionRegistry;
    use Illuminate\Support\Str;

    $schema = SectionRegistry::rulesFor($section['type']);
    $meta = SectionRegistry::get($section['type']);

    $groups = ['text' => [], 'media' => [], 'cta' => [], 'repeater' => [], 'other' => []];

    foreach ($schema as $field => $rules) {
        if (str_contains($field, '.')) continue;

        if (str_contains($rules, 'boolean')) {
            $editor = 'toggle';
        } elseif (
            (
                str_contains($field, 'image') ||
                str_contains($field, 'media') ||
                str_contains($field, 'cover_') ||
                str_contains($field, 'logo') ||
                str_contains($field, 'avatar') ||
                str_contains($rules, 'file')
            ) && !preg_match('/(_(alt|caption|position))$/', $field)
        ) {
            $editor = 'media';
        } elseif (str_contains($rules, 'array')) {
            $isRepeater = false;
            foreach ($schema as $schemaKey => $schemaRules) {
                if (str_starts_with($schemaKey, $field . '.*.')) {
                    $isRepeater = true;
                    break;
                }
            }
            $editor = $isRepeater ? 'repeater' : 'object';
        } elseif (preg_match('/(^|\|)in:/', $rules)) {
            $editor = 'select';
        } else {
            $editor = 'text';
        }

        if ($editor === 'repeater') {
            $group = 'repeater';
        } elseif ($editor === 'media') {
            $group = 'media';
        } elseif ($editor === 'object' || str_contains($field, 'cta') || str_contains($field, 'button')) {
            $group = 'cta';
        } elseif (in_array($editor, ['text', 'select', 'toggle'])) {
            $group = 'text';
        } else {
            $group = 'other';
        }

        $groups[$group][$field] = ['rules' => $rules, 'editor' => $editor];
    }

    $groups = array_filter($groups);

    $groupLabels = [
        'text' => 'Content',
        'media' => 'Media',
        'cta' => 'Call to Action',
        'repeater' => 'Items',
        'other' => 'Settings',
    ];

    $groupIcons = [
        'text' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>',
        'media' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V4.5A1.5 1.5 0 0020.25 3H3.75A1.5 1.5 0 002.25 4.5v15A1.5 1.5 0 003.75 21z"/>',
        'cta' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59"/>',
        'repeater' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>',
        'other' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
    ];

    $fieldHelpers = [
        'eyebrow' => 'Small text above the headline',
        'headline' => 'Main heading for this section',
        'subheadline' => 'Supporting text below the headline',
        'content' => 'Main body content (supports HTML)',
        'image_alt' => 'Alt text for accessibility',
        'image_position' => 'Where the image appears relative to text',
        'embed_url' => 'Full embed URL from Google Maps',
        'timeline' => 'e.g. "6-12 weeks"',
        'variant' => 'Visual style variant',
        'align' => 'Text alignment',
        'consent_text' => 'Legal consent text shown below submit button',
        'submit_label' => 'Text shown on the submit button',
    ];
@endphp

<div class="space-y-6">

    @forelse ($groups as $groupKey => $fields)
        <div class="space-y-4">
            @if (count($groups) > 1)
                <div class="flex items-center gap-2 pb-1">
                    <svg class="h-3.5 w-3.5 text-ink-faint" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        {!! $groupIcons[$groupKey] ?? $groupIcons['other'] !!}
                    </svg>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-ink-faint">
                        {{ $groupLabels[$groupKey] ?? Str::headline($groupKey) }}
                    </span>
                    <div class="flex-1 border-t border-edge"></div>
                </div>
            @endif

            @foreach ($fields as $field => $fieldMeta)
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-ink-muted">
                        {{ Str::headline($field) }}
                    </label>
                    @if (isset($fieldHelpers[$field]))
                        <p class="mb-2 text-[11px] text-ink-faint">{{ $fieldHelpers[$field] }}</p>
                    @endif

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
        <div class="flex items-center gap-2 rounded-lg px-3 py-4 text-sm text-ink-faint">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
            No editable fields defined for this section.
        </div>
    @endforelse

</div>
