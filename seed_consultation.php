<?php

use App\Models\Page;
use App\Models\Section;

// Check if page exists
$page = Page::where('full_path', '/free-consultation')->first();

if (!$page) {
    $page = Page::create([
        'full_path'       => '/free-consultation',
        'template_key'    => 'consultation',
        'status'          => 'published',
        'published_at'    => now(),
        'seo_title'       => 'Free Consultation | BUILTWELL Connecticut Home Remodeling',
        'seo_description' => 'Schedule a free, no-obligation home remodeling consultation with BUILTWELL. Licensed Connecticut contractor serving Fairfield and New Haven County. Kitchen, bathroom, basement, additions.',
    ]);
    echo "Created /free-consultation (ID:{$page->id})\n";
} else {
    Section::where('page_id', $page->id)->delete();
    $page->update([
        'template_key'    => 'consultation',
        'status'          => 'published',
        'published_at'    => $page->published_at ?? now(),
        'seo_title'       => 'Free Consultation | BUILTWELL Connecticut Home Remodeling',
        'seo_description' => 'Schedule a free, no-obligation home remodeling consultation with BUILTWELL. Licensed Connecticut contractor serving Fairfield and New Haven County. Kitchen, bathroom, basement, additions.',
    ]);
    echo "Found existing /free-consultation (ID:{$page->id}) — cleared sections\n";
}

// consultation template allowed: hero, lead_form, trust_bar, process_steps, faq_list, testimonials, cta_block, rich_text
$sections = [

    // 1. HERO — strong, direct, conversion-focused
    [
        'type' => 'hero',
        'data' => [
            'headline'         => 'Schedule Your Free Home Remodeling Consultation',
            'subheadline'      => 'Tell us about your project and we\'ll contact you within 24 hours to schedule a visit to your home. No pressure, no obligation — just honest advice from a licensed Connecticut contractor.',
            'background_image' => null,
            'background_video' => null,
            'overlay_opacity'  => 0.5,
        ],
    ],

    // 2. LEAD FORM — the main conversion element
    [
        'type' => 'lead_form',
        'data' => [
            'headline'    => 'Tell Us About Your Project',
            'subheadline' => 'Fill out the form below and our team will reach out within one business day.',
            'form_fields' => [
                ['name' => 'first_name',    'label' => 'First Name',       'type' => 'text',     'required' => true,  'placeholder' => 'John'],
                ['name' => 'last_name',     'label' => 'Last Name',        'type' => 'text',     'required' => true,  'placeholder' => 'Smith'],
                ['name' => 'email',         'label' => 'Email Address',    'type' => 'email',    'required' => true,  'placeholder' => 'john@example.com'],
                ['name' => 'phone',         'label' => 'Phone Number',     'type' => 'tel',      'required' => true,  'placeholder' => '(203) 555-0000'],
                ['name' => 'address',       'label' => 'Property Address', 'type' => 'text',     'required' => false, 'placeholder' => '123 Main St, Town, CT'],
                ['name' => 'project_type',  'label' => 'Project Type',     'type' => 'select',   'required' => true,  'options' => ['Kitchen Remodeling', 'Bathroom Remodeling', 'Basement Finishing', 'Home Addition', 'Flooring', 'Roofing', 'Other']],
                ['name' => 'timeline',      'label' => 'When do you want to start?', 'type' => 'select', 'required' => false, 'options' => ['As soon as possible', 'Within 1 month', 'Within 3 months', '3+ months out', 'Just exploring options']],
                ['name' => 'message',       'label' => 'Tell us more about your project', 'type' => 'textarea', 'required' => false, 'placeholder' => 'Describe what you have in mind — size, scope, goals, budget range, or anything else that would help us prepare for your consultation.'],
            ],
            'submit_label'       => 'Request My Free Consultation',
            'form_placeholder'   => true,
            'privacy_note'       => 'Your information is private and will never be shared. We\'ll only use it to contact you about your project.',
            'success_message'    => 'Thank you! We\'ve received your request and will contact you within one business day to schedule your consultation.',
        ],
    ],

    // 3. TRUST BAR — credibility right below the form
    [
        'type' => 'trust_bar',
        'data' => [
            'items' => [
                ['icon' => 'shield-check', 'text' => 'Licensed & Insured'],
                ['icon' => 'star',         'text' => '5-Star Google Rating'],
                ['icon' => 'document',     'text' => 'Written Warranty'],
                ['icon' => 'currency',     'text' => 'Fixed-Price Proposals'],
                ['icon' => 'clock',        'text' => '24hr Response Time'],
            ],
        ],
    ],

    // 4. PROCESS STEPS — what happens after they submit
    [
        'type' => 'process_steps',
        'data' => [
            'headline'    => 'What Happens After You Submit',
            'subheadline' => 'Our consultation process is simple, transparent, and completely free.',
            'steps' => [
                [
                    'step_number' => 1,
                    'title'       => 'We Call You',
                    'description' => 'Within one business day, a member of our team will call to discuss your project goals, answer initial questions, and schedule a convenient time to visit your home.',
                ],
                [
                    'step_number' => 2,
                    'title'       => 'Home Visit & Measurements',
                    'description' => 'We visit your property, take measurements, review the existing conditions, and talk through your vision in detail. This typically takes 30–45 minutes.',
                ],
                [
                    'step_number' => 3,
                    'title'       => 'Detailed Proposal',
                    'description' => 'Within 3–5 business days, you receive a fixed-price written proposal with full scope of work, material specifications, timeline, and warranty details. No hidden fees, no surprises.',
                ],
                [
                    'step_number' => 4,
                    'title'       => 'You Decide',
                    'description' => 'Review the proposal on your own time. Ask questions, request adjustments, or compare with other quotes. There is zero pressure to commit — the decision is always yours.',
                ],
            ],
        ],
    ],

    // 5. TESTIMONIALS — social proof to reinforce trust
    [
        'type' => 'testimonials',
        'data' => [
            'headline' => 'Homeowners Who Started Right Here',
            'testimonials' => [
                [
                    'name'   => 'Lisa & James R.',
                    'city'   => 'Greenwich, CT',
                    'rating' => 5,
                    'text'   => 'We filled out the consultation form on a Sunday night and got a call Monday morning. The home visit was thorough, the proposal was detailed and fair, and we signed within a week. Best contractor experience we\'ve ever had.',
                ],
                [
                    'name'   => 'Michael T.',
                    'city'   => 'Westport, CT',
                    'rating' => 5,
                    'text'   => 'The consultation alone was worth it — they gave us honest advice about what was realistic for our budget and timeline. No pushy sales tactics. Just professional, straightforward guidance.',
                ],
                [
                    'name'   => 'Karen M.',
                    'city'   => 'Orange, CT',
                    'rating' => 5,
                    'text'   => 'I requested a consultation for a kitchen remodel and received a detailed proposal within four days. The fixed-price format made it easy to compare with other quotes. BUILTWELL won on quality and transparency.',
                ],
            ],
        ],
    ],

    // 6. FAQ — overcome last objections
    [
        'type' => 'faq_list',
        'data' => [
            'headline' => 'Common Questions About Our Consultations',
            'faqs' => [
                [
                    'question' => 'Is the consultation really free?',
                    'answer'   => 'Yes, completely free with no obligation. We visit your home, discuss your goals, take measurements, and provide a written proposal — all at no cost. If you decide not to move forward, you owe nothing.',
                ],
                [
                    'question' => 'How quickly will I hear back?',
                    'answer'   => 'We respond to every consultation request within one business day. Most homeowners receive a call within a few hours of submitting the form.',
                ],
                [
                    'question' => 'What should I prepare before the home visit?',
                    'answer'   => 'Nothing specific is required. If you have inspiration photos, Pinterest boards, or a rough budget range in mind, that helps us prepare a more accurate proposal — but it\'s not necessary.',
                ],
                [
                    'question' => 'Do you serve my area?',
                    'answer'   => 'We serve homeowners throughout Fairfield County (Greenwich, Stamford, Westport, Norwalk, Fairfield, Darien, Trumbull, Shelton) and New Haven County (Orange, Milford, Branford, Hamden, Guilford, Wallingford, North Haven, New Haven).',
                ],
                [
                    'question' => 'What types of projects do you handle?',
                    'answer'   => 'Kitchen remodeling, bathroom remodeling, basement finishing, home additions, flooring, and roofing. If your project falls outside these categories, reach out anyway — we may be able to help or refer you to a trusted partner.',
                ],
                [
                    'question' => 'Will I receive a written proposal?',
                    'answer'   => 'Yes. Every consultation results in a detailed, fixed-price written proposal that includes full scope of work, material specifications, project timeline, payment schedule, and warranty terms. No verbal estimates, no vague numbers.',
                ],
            ],
        ],
    ],

    // 7. CTA BLOCK — final push
    [
        'type' => 'cta_block',
        'data' => [
            'headline'    => 'Prefer to Call?',
            'subheadline' => 'Our team is available Monday through Friday, 8am to 5pm. Call us directly and we\'ll get your consultation scheduled today.',
            'cta'         => ['label' => 'Call (203) 555-0100', 'url' => 'tel:+12035550100'],
        ],
    ],
];

foreach ($sections as $i => $s) {
    Section::create([
        'page_id'    => $page->id,
        'type'       => $s['type'],
        'sort_order' => $i,
        'is_active'  => true,
        'data'       => $s['data'],
    ]);
}

echo "Created " . count($sections) . " sections\n";
$types = collect($sections)->pluck('type')->implode(' → ');
echo "Order: {$types}\n";

// Summary
echo "\n=== RESULT ===\n";
$secs = Section::where('page_id', $page->id)->orderBy('sort_order')->get();
echo "/free-consultation (ID:{$page->id}) | {$page->template_key} | {$page->status} | sections: {$secs->count()}\n";
echo "Types: " . $secs->pluck('type')->implode(' → ') . "\n";

// Clear cache
\Illuminate\Support\Facades\Cache::flush();
echo "\nCache cleared. Done!\n";
