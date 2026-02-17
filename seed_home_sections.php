<?php

use App\Models\Section;

// Delete all existing home page sections
Section::where('page_id', 2)->delete();

$sections = [
    // 1. HERO SLIDER
    [
        'page_id' => 2,
        'type' => 'hero_slider',
        'sort_order' => 0,
        'is_active' => true,
        'data' => [
            'headline' => 'The Last Contractor You\'ll Hire',
            'subheadline' => 'Full-service remodeling for kitchens, bathrooms, basements, and more — backed by a written warranty and real accountability.',
            'slides' => [
                ['image' => '', 'alt' => 'Modern kitchen remodel in Connecticut', 'caption' => null],
                ['image' => '', 'alt' => 'Luxury bathroom renovation', 'caption' => null],
                ['image' => '', 'alt' => 'Basement finishing project', 'caption' => null],
            ],
            'cta_primary' => [
                'label' => 'Schedule a Free Consultation',
                'url' => '/free-consultation/',
            ],
            'cta_secondary' => [
                'label' => 'Call (203) 555-0100',
                'url' => 'tel:+12035550100',
            ],
            'badges' => [
                ['label' => 'CT Licensed & Insured', 'value' => '#0668405'],
                ['label' => '5-Star Google Rating', 'value' => null],
            ],
        ],
    ],

    // 2. TRUST BAR
    [
        'page_id' => 2,
        'type' => 'trust_bar',
        'sort_order' => 1,
        'is_active' => true,
        'data' => [
            'items' => [
                ['icon' => 'star', 'label' => '5.0 Google Rating', 'value' => '50+ Reviews'],
                ['icon' => 'shield', 'label' => 'BBB A+ Rated', 'value' => null],
                ['icon' => 'clock', 'label' => '15+ Years Experience', 'value' => null],
                ['icon' => 'id-card', 'label' => 'CT HIC License', 'value' => '#0668405'],
                ['icon' => 'map', 'label' => 'Fairfield & New Haven Counties', 'value' => null],
            ],
        ],
    ],

    // 3. RICH TEXT — PROBLEM SECTION
    [
        'page_id' => 2,
        'type' => 'rich_text',
        'sort_order' => 2,
        'is_active' => true,
        'data' => [
            'eyebrow' => 'The Problem We Solve',
            'title' => 'Tired of Contractors Who Disappear?',
            'content' => "Most homeowners have the same story: a contractor who seemed great at first — then stopped showing up, went over budget, or left the job half-finished.\n\nAt BUILTWELL, we built our company around the opposite experience. We show up when we say we will. We communicate every step of the way. And we don't consider a project done until you're completely satisfied.\n\nThat's not a slogan — it's how we operate on every single project.",
            'image' => null,
            'image_alt' => null,
            'image_position' => 'right',
            'cta' => [
                'label' => 'See How We\'re Different',
                'url' => '/about/',
            ],
            'align' => 'left',
            'variant' => 'default',
        ],
    ],

    // 4. SERVICES GRID
    [
        'page_id' => 2,
        'type' => 'services_grid',
        'sort_order' => 3,
        'is_active' => true,
        'data' => [
            'title' => 'What We Do',
            'subtitle' => 'Full-service remodeling across Connecticut — from design to final walkthrough.',
            'items' => [
                ['title' => 'Kitchen Remodeling', 'summary' => 'Custom layouts, premium cabinetry, and expert craftsmanship for the heart of your home.', 'image' => null, 'url' => '/kitchen-remodeling/', 'cta_label' => 'Learn More'],
                ['title' => 'Bathroom Remodeling', 'summary' => 'From outdated to spa-worthy — walk-in showers, double vanities, and heated floors.', 'image' => null, 'url' => '/bathroom-remodeling/', 'cta_label' => 'Learn More'],
                ['title' => 'Basement Finishing', 'summary' => 'Turn unused square footage into living space, home offices, or entertainment rooms.', 'image' => null, 'url' => '/basement-finishing/', 'cta_label' => 'Learn More'],
                ['title' => 'Flooring', 'summary' => 'Hardwood, tile, LVP and more — installed right the first time.', 'image' => null, 'url' => '/flooring/', 'cta_label' => 'Learn More'],
                ['title' => 'Interior Painting', 'summary' => 'Clean lines, premium paints, and meticulous prep for a flawless finish.', 'image' => null, 'url' => '/interior-painting/', 'cta_label' => 'Learn More'],
                ['title' => 'Exterior Renovations', 'summary' => 'Siding, decks, patios, and curb appeal upgrades built to last.', 'image' => null, 'url' => '/exterior-renovations/', 'cta_label' => 'Learn More'],
            ],
        ],
    ],

    // 5. STATS BAR
    [
        'page_id' => 2,
        'type' => 'stats_counter',
        'sort_order' => 4,
        'is_active' => true,
        'data' => [
            'title' => null,
            'subtitle' => null,
            'variant' => 'dark',
            'items' => [
                ['value' => '24+', 'label' => 'Bathroom Remodels', 'icon' => 'bath'],
                ['value' => '15+', 'label' => 'Years of Experience', 'icon' => 'clock'],
                ['value' => '100%', 'label' => 'Client Satisfaction', 'icon' => 'star'],
                ['value' => '500+', 'label' => 'Projects Completed', 'icon' => 'check'],
            ],
        ],
    ],

    // 6. RICH TEXT + IMAGE (ORIGINS)
    [
        'page_id' => 2,
        'type' => 'rich_text_image',
        'sort_order' => 5,
        'is_active' => true,
        'data' => [
            'eyebrow' => 'Our Story',
            'title' => 'Built on Integrity, Not Shortcuts',
            'content' => "BUILTWELL was founded with a simple belief: homeowners deserve a contractor who treats their home — and their time — with respect.\n\nAfter years of working in the industry and seeing how most companies operate, we decided to build something different. A company that communicates clearly, shows up on time, and stands behind every project with a real warranty.\n\nWe're not the biggest contractor in Connecticut. But we might be the most reliable one you'll ever work with.",
            'image' => null,
            'image_alt' => 'BUILTWELL team on a job site',
            'image_position' => 'right',
            'quote_text' => '"We don\'t just build spaces — we build trust."',
        ],
    ],

    // 7. WARRANTY / FEATURES
    [
        'page_id' => 2,
        'type' => 'feature_list_two_column',
        'sort_order' => 6,
        'is_active' => true,
        'data' => [
            'eyebrow' => 'The BUILTWELL Difference',
            'title' => 'What Sets Us Apart',
            'left_features' => [
                ['title' => 'Written Warranty on Every Project', 'description' => 'Every BUILTWELL project comes with a written warranty — so you never have to wonder if we\'ll stand behind our work.'],
                ['title' => 'Daily Communication', 'description' => 'You\'ll hear from us every single day your project is active. No ghosting, no guessing.'],
                ['title' => 'Fixed-Price Proposals', 'description' => 'Our proposals are detailed and transparent. The price we quote is the price you pay — unless you decide to change the scope.'],
                ['title' => 'Clean Job Sites', 'description' => 'We treat your home like our own. Daily cleanup, protective coverings, and respect for your space.'],
            ],
            'right_bullets' => [
                'CT Licensed & Insured (HIC #0668405)',
                'BBB A+ Rated',
                'Background-checked crews',
                'On-time project delivery',
                'Dedicated project manager',
                'Post-project support',
            ],
            'closing_quote' => '"We believe accountability is the foundation of every great remodel."',
        ],
    ],

    // 8. FAQ
    [
        'page_id' => 2,
        'type' => 'faq_list',
        'sort_order' => 7,
        'is_active' => true,
        'data' => [
            'title' => 'Frequently Asked Questions',
            'subtitle' => 'Answers to the most common questions homeowners ask before starting a remodel.',
            'items' => [
                ['question' => 'How long does a typical kitchen remodel take?', 'answer' => 'Most kitchen remodels take 6–12 weeks depending on scope. Custom cabinetry can add 8–12 weeks of lead time. We\'ll give you a clear timeline before work begins.'],
                ['question' => 'Do you offer free consultations?', 'answer' => 'Yes — every project starts with a free, no-obligation consultation. We\'ll visit your home (or meet via Zoom), discuss your goals, and provide a detailed proposal.'],
                ['question' => 'Are you licensed and insured?', 'answer' => 'Absolutely. BUILTWELL holds a Connecticut Home Improvement Contractor license (#0668405) and carries full liability and workers\' compensation insurance.'],
                ['question' => 'What areas do you serve?', 'answer' => 'We serve homeowners throughout Fairfield County and New Haven County, Connecticut — including Greenwich, Stamford, Norwalk, Westport, Milford, Orange, and surrounding towns.'],
                ['question' => 'Do you provide a warranty?', 'answer' => 'Yes. Every BUILTWELL project comes with a written warranty covering workmanship. We stand behind what we build.'],
                ['question' => 'How much does a bathroom remodel cost?', 'answer' => 'Bathroom remodels in Connecticut typically range from $25,000–$75,000+ depending on size, materials, and scope. We provide fixed-price proposals so there are no surprises.'],
            ],
        ],
    ],

    // 9. AREAS SERVED
    [
        'page_id' => 2,
        'type' => 'areas_served',
        'sort_order' => 8,
        'is_active' => true,
        'data' => [
            'title' => 'Areas We Serve',
            'subtitle' => 'Proudly serving homeowners throughout Fairfield County and New Haven County, Connecticut.',
            'counties' => [
                [
                    'name' => 'Fairfield County',
                    'towns' => ['Greenwich', 'Stamford', 'Norwalk', 'Westport', 'Fairfield', 'Trumbull', 'Shelton', 'Stratford', 'Bridgeport', 'Darien', 'New Canaan', 'Weston', 'Wilton', 'Ridgefield', 'Redding', 'Easton', 'Monroe'],
                ],
                [
                    'name' => 'New Haven County',
                    'towns' => ['New Haven', 'Orange', 'Milford', 'West Haven', 'Hamden', 'Branford', 'Guilford', 'Madison', 'North Haven', 'Woodbridge', 'Bethany', 'Derby', 'Ansonia', 'Seymour'],
                ],
            ],
        ],
    ],

    // 10. PROCESS STEPS
    [
        'page_id' => 2,
        'type' => 'process_steps',
        'sort_order' => 9,
        'is_active' => true,
        'data' => [
            'title' => 'From First Call to Finished Project',
            'subtitle' => 'Our proven 5-step process keeps every project on track.',
            'steps' => [
                ['title' => 'Consultation', 'short' => 'We visit your home or meet via Zoom', 'description' => 'We discuss your goals, assess the space, and answer all your questions — no pressure, no obligation.'],
                ['title' => 'Planning', 'short' => 'Detailed scope, timeline, and proposal', 'description' => 'You receive a clear, fixed-price proposal with everything included — scope, timeline, materials, and cost.'],
                ['title' => 'Selections', 'short' => 'Materials and finish selections', 'description' => 'We guide you through cabinets, counters, tile, flooring, fixtures, and finishes with expert recommendations.'],
                ['title' => 'Build', 'short' => 'Construction with daily communication', 'description' => 'We show up when we say we will, keep the site clean, and update you every single day.'],
                ['title' => 'Walkthrough', 'short' => 'Final review before we call it done', 'description' => 'We walk through everything together and address anything that needs attention before the project closes.'],
            ],
        ],
    ],

    // 11. TESTIMONIALS
    [
        'page_id' => 2,
        'type' => 'testimonials',
        'sort_order' => 10,
        'is_active' => true,
        'data' => [
            'title' => 'What Our Clients Say',
            'subtitle' => 'Real feedback from homeowners across Connecticut.',
            'layout' => 'carousel',
            'items' => [
                ['name' => 'Sarah M.', 'location' => 'Greenwich, CT', 'quote' => 'BUILTWELL completely transformed our kitchen. They were on time every day, communicated constantly, and the quality is incredible. Best contractor experience we\'ve ever had.', 'avatar' => null, 'rating' => 5, 'year' => '2025'],
                ['name' => 'James & Lisa R.', 'location' => 'Orange, CT', 'quote' => 'We\'ve hired contractors before who disappeared halfway through. BUILTWELL was the complete opposite — professional, reliable, and they stood behind their work with a real warranty.', 'avatar' => null, 'rating' => 5, 'year' => '2025'],
                ['name' => 'Michael T.', 'location' => 'Westport, CT', 'quote' => 'Our basement went from an unusable storage space to a beautiful family room. The whole process was smooth and the team was a pleasure to work with.', 'avatar' => null, 'rating' => 5, 'year' => '2024'],
            ],
        ],
    ],

    // 12. PROJECTS PREVIEW
    [
        'page_id' => 2,
        'type' => 'project_highlights',
        'sort_order' => 11,
        'is_active' => true,
        'data' => [
            'title' => 'Recent Projects',
            'items' => [
                ['title' => 'Modern Kitchen Remodel', 'description' => 'Complete gut renovation with custom cabinetry and quartz countertops.', 'image' => null, 'url' => '/projects/modern-kitchen-greenwich/', 'tag' => 'Greenwich, CT'],
                ['title' => 'Spa Bathroom Renovation', 'description' => 'Walk-in shower, heated floors, and double vanity in a master bathroom.', 'image' => null, 'url' => '/projects/spa-bathroom-orange/', 'tag' => 'Orange, CT'],
                ['title' => 'Basement Entertainment Room', 'description' => 'Full basement finish with wet bar, home theater, and guest bedroom.', 'image' => null, 'url' => '/projects/basement-westport/', 'tag' => 'Westport, CT'],
            ],
        ],
    ],

    // 13. CTA FORM
    [
        'page_id' => 2,
        'type' => 'lead_form',
        'sort_order' => 12,
        'is_active' => true,
        'data' => [
            'title' => 'Ready to Start Your Project?',
            'subtitle' => 'Schedule your free, no-obligation consultation today. We\'ll visit your home, discuss your vision, and provide a detailed proposal.',
            'steps' => [
                ['number' => 1, 'text' => 'Tell us about your project'],
                ['number' => 2, 'text' => 'Schedule a site visit'],
                ['number' => 3, 'text' => 'Get your detailed proposal'],
            ],
            'fields' => [
                ['name' => 'full_name', 'label' => 'Full Name', 'type' => 'text', 'required' => true],
                ['name' => 'phone', 'label' => 'Phone Number', 'type' => 'tel', 'required' => true],
                ['name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'required' => true],
                ['name' => 'project_type', 'label' => 'Project Type', 'type' => 'select', 'required' => true, 'options' => [
                    ['label' => 'Kitchen Remodeling', 'value' => 'kitchen'],
                    ['label' => 'Bathroom Remodeling', 'value' => 'bathroom'],
                    ['label' => 'Basement Finishing', 'value' => 'basement'],
                    ['label' => 'Flooring', 'value' => 'flooring'],
                    ['label' => 'Interior Painting', 'value' => 'painting'],
                    ['label' => 'Exterior Renovations', 'value' => 'exterior'],
                    ['label' => 'Other', 'value' => 'other'],
                ]],
                ['name' => 'zip', 'label' => 'Zip Code', 'type' => 'text', 'required' => true],
                ['name' => 'details', 'label' => 'Tell us about your project (optional)', 'type' => 'textarea', 'required' => false],
            ],
            'submit_label' => 'Book Free Consultation',
            'consent_text' => 'By submitting, you agree to receive calls or texts from BUILTWELL. We respect your privacy.',
        ],
    ],
];

foreach ($sections as $s) {
    Section::create($s);
}

echo "Created " . count($sections) . " homepage sections successfully.\n";
echo "Section types: " . collect($sections)->pluck('type')->implode(', ') . "\n";
