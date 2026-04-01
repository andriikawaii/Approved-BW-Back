<?php

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$pagePayload = [
    'full_path' => '/new-haven-county/new-haven-ct',
    'template_key' => 'service_town',
    'status' => 'published',
    'published_at' => now(),
    'seo_title' => 'Home Remodeling New Haven, CT | BuiltWell CT',
    'seo_description' => 'Home remodeling in New Haven, CT - kitchens, bathrooms, basements, and flooring. Licensed contractor serving the greater New Haven area. Free estimate.',
    'canonical_url' => 'https://buildwellct.com/new-haven-county/new-haven-ct/',
    'og_image_alt' => 'BuiltWell CT home remodeling contractor serving New Haven, Connecticut',
    'robots' => 'index, follow',
];

$rich = static fn (
    string $eyebrow,
    string $title,
    string $content,
    array $overrides = []
): array => array_merge([
    'eyebrow' => $eyebrow,
    'title' => $title,
    'highlight_text' => null,
    'content' => $content,
    'image' => null,
    'image_alt' => null,
    'image_position' => 'right',
    'cta' => null,
    'align' => 'left',
    'variant' => 'default',
    'style_variant' => 'default',
    'surface' => 'default',
    'container_width' => 'default',
    'spacing' => 'normal',
    'anchor_id' => null,
], $overrides);

$sections = [
    [
        'type' => 'hero',
        'data' => [
            'headline' => 'Home Remodeling in New Haven, CT',
            'subheadline' => 'Home remodeling in New Haven, CT for pre-war homes, Victorians, and modern builds. Licensed New Haven County contractor with crews who understand older housing stock.',
            'background_image' => '/images/areas/new-haven-ct-skyline.jpg',
            'background_image_alt' => 'Skyline view of New Haven, Connecticut',
            'overlay' => ['opacity' => 0.45],
            'cta_primary' => ['label' => 'Get Your Free Estimate', 'url' => '#contact'],
            'cta_secondary' => ['label' => 'Call (203) 466-9148', 'url' => 'tel:2034669148'],
            'badges' => [],
        ],
    ],
    [
        'type' => 'trust_bar',
        'data' => [
            'variant' => 'hero',
            'items' => [
                ['label' => 'Years of Experience', 'value' => '15+', 'icon' => 'clock'],
                ['label' => 'Completed Projects', 'value' => '100+', 'icon' => 'check-circle'],
                ['label' => 'Google Rating', 'value' => '4.9', 'icon' => 'star', 'url' => 'https://www.google.com/search?q=builtwell+ct+reviews'],
                ['label' => 'Fully Bonded and Insured', 'value' => null, 'icon' => 'shield'],
            ],
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            'New Haven Remodeling',
            'Professional Home Remodeling in New Haven, CT',
            "Home remodeling in New Haven, CT costs $25,000 to $150,000 or more depending on scope and which neighborhood you're in. East Rock Victorians and Wooster Square townhouses regularly add $15,000 to $40,000 in pre-war remediation costs on top of the base remodel. The city's housing stock is older, denser, and structurally more demanding than nearly anywhere else in Connecticut. We hold CT HIC License #0668405 and serve New Haven from our [Orange, CT office](/new-haven-county/orange-ct/).",
            [
                'highlight_text' => 'New Haven, CT',
                'anchor_id' => 'new-haven-intro',
                'align' => 'center',
                'cta' => ['label' => 'Get Your Free Estimate', 'url' => '#contact'],
            ]
        ),
    ],
    [
        'type' => 'full_width_text_dark',
        'data' => [
            'title' => "New Haven's Housing Stock: What You're Working With",
            'subtitle' => "New Haven's housing stock is unlike any other Connecticut city: 40.8% of homes were built before 1940, the median construction year is 1952, and duplexes and converted apartments make up 40.87% of occupied units. Single-family detached homes account for only 19.85% of the city's housing. That profile shapes every renovation project in this city.",
            'alignment' => 'center',
        ],
    ],
    [
        'type' => 'rich_text_image',
        'data' => [
            'title' => 'Balloon Framing, Plaster Walls, and Knob-and-Tube Wiring',
            'content' => "Homes built before roughly 1930 in New Haven overwhelmingly use balloon framing, where studs run continuously from the foundation sill to the roof ridge without horizontal blocking at the floor lines. When we open these walls, fire blocking installation is required before they are closed again. This is not optional under current code.\n\nVirtually every pre-1940 home has three-coat plaster on wood lath rather than drywall. Knob-and-tube wiring is common in pre-1945 homes and cannot be covered with insulation. CT code requires replacement during any significant renovation. A full rewire in a typical New Haven Victorian multifamily runs $15,000 to $30,000.",
            'image' => '/images/areas/new-haven-ct-victorian-framing.jpg',
            'image_alt' => 'Knob-and-tube wiring on exposed wood framing in pre-1930 New Haven CT home',
            'image_position' => 'left',
        ],
    ],
    [
        'type' => 'rich_text_image',
        'data' => [
            'title' => 'Stone Foundations and Lead Paint',
            'content' => "Pre-1900 homes in New Haven typically have stone foundation walls, usually dry-laid fieldstone or mortared rubble with dirt or brick floors. Stone foundations transmit moisture readily. Converting a stone-foundation basement to usable living space requires an interior drainage system, vapor barriers, and potentially underpinning before any framing can go up.\n\nPre-1940 homes in New Haven almost certainly contain lead paint, often with multiple layers accumulated over a century. Federal EPA RRP rules apply to any project disturbing more than six square feet of interior painted surface per room. Contractors must carry EPA RRP certification. We do.",
            'image' => '/images/areas/new-haven-ct-stone-foundation.jpg',
            'image_alt' => 'Stone and brick foundation walls in basement of pre-1900 New Haven CT home',
            'image_position' => 'right',
        ],
    ],
    [
        'type' => 'rich_text_image',
        'data' => [
            'title' => 'Steam Heat and Asbestos-Containing Materials',
            'content' => "Victorian homes in New Haven overwhelmingly use steam heat rather than forced air, typically one-pipe or two-pipe steam systems with cast iron radiators. Steam boiler replacement is a specialty trade that requires specific knowledge. Converting from steam to mini-split heat pumps involves additional planning that goes beyond a standard HVAC swap.\n\nAsbestos-containing materials appear in predictable locations: pipe insulation on steam heating systems, 9-inch vinyl floor tiles from the 1950s through the 1970s, ceiling tiles, plaster additives, and roofing shingles. Connecticut DPH requires a licensed abatement contractor for removal above threshold quantities. Many routine renovation tasks trigger pre-renovation testing.",
            'image' => '/images/areas/new-haven-ct-steam-radiator.jpg',
            'image_alt' => 'Cast iron steam radiator typical of Victorian-era heating systems in New Haven CT homes',
            'image_position' => 'left',
        ],
    ],
    [
        'type' => 'accordion_list',
        'data' => [
            'eyebrow' => 'Neighborhoods',
            'title' => 'New Haven Neighborhoods: Architecture and Renovation Context',
            'highlight_text' => 'Renovation Context',
            'subtitle' => 'New Haven is not one housing market. Each neighborhood has a distinct architectural character, housing period, and renovation reality. Knowing which neighborhood your home sits in is the starting point for understanding what your project will actually involve.',
            'items' => [
                ['title' => 'East Rock', 'content' => "East Rock is New Haven's most competitive owner-occupant neighborhood, running north of downtown and bordered by East Rock Park. The housing stock dates from roughly 1880 to 1930 and includes large single-family Queen Anne Victorians with turrets, wrap-around porches, and decorative gingerbread trim concentrated along Whitney Avenue, alongside triple-deckers, duplexes, converted mansions, and Craftsman bungalows from the 1910s through 1930s. The western half of the neighborhood falls within the Whitney Avenue Historic District, listed on the National Register, which makes these properties eligible for historic tax credits on qualified renovation work but does not impose mandatory exterior review. Median single-family prices run around $725,000. Projects here consistently involve balloon framing, plaster walls, knob-and-tube wiring, lead paint in multiple layers, and steam heat systems."],
                ['title' => 'Wooster Square', 'content' => "Wooster Square is New Haven's most architecturally significant 19th-century neighborhood and the most regulated for exterior renovation work. The housing stock is a concentrated collection of Federal, Greek Revival, Italianate, Second Empire, Queen Anne, and Islamic Revival buildings, many designed by Henry Austin. The Wooster Square Historic District was listed on the National Register in 1971 and became New Haven's first local historic district in 1970. A Certificate of Appropriateness is required from the Historic District Commission for all exterior changes. Window replacement, siding, doors, and exterior architectural modifications all require HDC approval before a building permit can be issued."],
                ['title' => 'Westville', 'content' => "Westville occupies the far northwest of New Haven and has the most suburban character of any neighborhood in the city. It developed as a streetcar suburb primarily in the early to mid 20th century, and the housing stock reflects that period: Colonial Revivals, Tudor Revivals, American Bungalows in the Craftsman style, and Arts and Crafts homes built from the 1910s through the 1940s, set under a mature tree canopy. The Westville Village Historic District covers the commercial core and is listed on the National Register, but does not impose mandatory review on exterior residential changes. Median single-family prices run around $527,000. The Craftsman bungalow stock here requires careful attention to original details when renovation work touches these elements."],
                ['title' => 'Beaver Hills', 'content' => 'Beaver Hills is a virtually intact early 20th-century suburban residential district, built almost entirely between 1908 and 1936 with almost no demolition or new construction since World War II. The Beaver Hills Historic District, listed on the National Register in 1986, includes 235 contributing buildings. Tudor Revival is the dominant style at 29%, alongside Colonial Revival, Bungalow, Queen Anne, Spanish Colonial Revival, and Prairie-style homes. The construction is genuine: balloon framing in the earliest homes, platform framing in the later ones, knob-and-tube wiring throughout the pre-1945 stock, plaster walls on wood lath, and single-pane steel casement windows.'],
                ['title' => 'Prospect Hill', 'content' => "Prospect Hill is New Haven's most established residential address, sitting north and west of Yale's main campus. The housing stock dates almost entirely from 1880 to 1930 and includes Queen Anne, Shingle Style, Colonial Revival, Tudor Revival, Italian Villa Revival, French Renaissance Revival, Spanish Colonial, and Prairie-style homes. The Prospect Hill Historic District was listed on the National Register in 1979 and covers 185 acres and 238 contributing buildings. Yale faculty, senior administrators, and medical professionals are the dominant homeowner profile. The homes are large, the architectural detail is significant, and the expectations for finish quality and period-appropriate materials are high."],
                ['title' => 'Edgewood', 'content' => 'Edgewood is adjacent to Edgewood Park, which was designed by Frederick Law Olmsted. The housing stock mixes Queen Anne multifamily homes, Victorian-era singles, early 20th-century bungalows, and Colonials, with more variable housing quality across the neighborhood than you find in East Rock or Prospect Hill. That variability creates genuine renovation and value-add opportunities. Projects here range from practical updates in bungalows that have been maintained but not modernized since the 1970s to more extensive work in larger Victorians with intact original detail worth preserving.'],
                ['title' => 'Ninth Square and Downtown', 'content' => "Ninth Square and the broader downtown core represent a distinct renovation context: converted 19th-century commercial buildings and industrial lofts rather than residential housing stock. Projects here involve load-bearing masonry construction, exposed ceiling heights that often run 12 to 16 feet, mechanical systems designed for commercial use, and the structural and code challenges of commercial-to-residential conversion. If you're renovating in this zone, the project requires a different planning approach than a residential neighborhood renovation."],
                ['title' => 'Fair Haven, Hill, Newhallville, Dixwell', 'content' => "These neighborhoods are defined by triple-deckers (two-family and three-family wood-frame buildings) and working-class housing stock built from the 1880s through the 1920s. The renovation conditions are the same as elsewhere in New Haven's pre-war stock: balloon framing, plaster, knob-and-tube wiring, lead paint, and steam or hot water heat. The scale of the work and the investment level tends to be more practical and targeted than in Prospect Hill or East Rock, but the structural conditions are no less real."],
            ],
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            'Permits and Approvals',
            'Permitting and Approvals in New Haven, CT',
            "All permits for home remodeling in New Haven are submitted through an online-only portal at the New Haven Building Department, located at 165 Church Street. Paper applications are not accepted, and the standard approval or denial timeline is 30 days by state law.\n\nExpress permits, which cover simpler scope work and require no plan review, are issued instantly through the portal. For properties in a local historic district, Historic District Commission review must be completed and a Certificate of Appropriateness issued before any building permit can proceed. Any structural work requires plans stamped by a Connecticut-licensed architect or engineer before the application will be accepted.\n\nH3: Local Historic Districts: Mandatory Review\n\nThree New Haven neighborhoods are covered by local historic district status, which means a Certificate of Appropriateness from the HDC is legally required before any exterior architectural change can be made. These are not voluntary guidelines. They are mandatory approvals that precede the building permit process.\n\nWooster Square Historic District (established 1970, New Haven's first local historic district) requires Certificate of Appropriateness review for all exterior changes including windows, doors, siding, and exterior architectural features. Contact the HDC at hdc@newhavenct.gov to begin the review process before any work is planned. City Point/Oyster Point Historic District (established 2001) and Quinnipiac River Historic District (established 1977) carry the same mandatory requirements.\n\nH3: National Register Districts: No Mandatory Review\n\nEast Rock (Whitney Avenue Historic District), Beaver Hills Historic District, Prospect Hill Historic District, Upper State Street, Orange Street, and Westville Village Historic District are all listed on the National Register. National Register listing does not impose any mandatory review on exterior changes for privately owned residential properties. However, it does make these properties eligible for Connecticut state historic tax credits on qualified rehabilitation work. If your home is in one of these National Register districts, it is worth speaking with your tax advisor about whether your project scope qualifies for the credit.\n\nWe handle all permit applications, plan coordination, and HDC submissions where applicable as part of every project we take on in New Haven.",
            ['highlight_text' => 'New Haven, CT', 'anchor_id' => 'new-haven-permits', 'container_width' => 'wide']
        ),
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            'Investment',
            'What Remodeling Costs in New Haven, CT',
            "Home remodeling in New Haven, CT costs $25,000 to $150,000 or more for a primary service project, with the specific neighborhood and age of the housing stock being the two variables that most significantly affect where a project lands within that range.\n\nNew Haven's renovation costs are generally lower than Westport or Greenwich, but the renovation complexity per dollar can be higher because the housing stock is older and the pre-war remediation items are more prevalent. A kitchen remodel in a 1920s East Rock Victorian is not the same project as a kitchen remodel in a 1970s Milford colonial of comparable square footage. The East Rock project may carry $15,000 to $40,000 in additional costs for lead abatement, knob-and-tube rewiring, and steam system work before the first cabinet goes in.",
            ['highlight_text' => 'New Haven, CT', 'anchor_id' => 'new-haven-costs-intro', 'container_width' => 'wide']
        ),
    ],
    [
        'type' => 'pricing_table',
        'data' => [
            'title' => 'Kitchen Remodeling Costs in New Haven, CT',
            'subtitle' => 'Pre-war premium: In East Rock, Prospect Hill, Wooster Square, Beaver Hills, and Westville, projects in the older housing stock routinely add $15,000 to $40,000 to the above ranges for lead abatement, knob-and-tube rewiring, asbestos testing and licensed abatement where required, and steam system coordination.',
            'columns' => ['Tier', 'Scope', 'Typical Range'],
            'rows' => [
                ['label' => 'Basic Refresh', 'notes' => 'Cabinet refacing, new countertops, appliances, paint', 'price' => '$25,000 - $50,000'],
                ['label' => 'Mid-Range', 'notes' => 'New cabinets, countertops, flooring, appliances, lighting', 'price' => '$50,000 - $90,000'],
                ['label' => 'High-End', 'notes' => 'Full custom, layout changes, premium materials', 'price' => '$90,000 - $150,000+'],
            ],
        ],
    ],
    [
        'type' => 'pricing_table',
        'data' => [
            'title' => 'Bathroom Remodeling Costs in New Haven, CT',
            'columns' => ['Tier', 'Scope', 'Typical Range'],
            'rows' => [
                ['label' => 'Basic', 'notes' => 'New fixtures, vanity, flooring, paint', 'price' => '$15,000 - $25,000'],
                ['label' => 'Mid-Range', 'notes' => 'Full gut, new tile, shower or tub, vanity, lighting', 'price' => '$25,000 - $55,000'],
                ['label' => 'High-End', 'notes' => 'Layout changes, premium fixtures, custom tile', 'price' => '$55,000 - $80,000+'],
            ],
        ],
    ],
    [
        'type' => 'pricing_table',
        'data' => [
            'title' => 'Basement Finishing Costs in New Haven, CT',
            'subtitle' => 'Stone foundation basements in pre-1900 New Haven homes require moisture assessment and often interior drainage systems before framing begins. That work is scoped and priced separately and must be completed before any finish work can be planned.',
            'columns' => ['Tier', 'Scope', 'Typical Range'],
            'rows' => [
                ['label' => 'Basic', 'notes' => 'Framing, drywall, flooring, lighting, paint', 'price' => '$25,000 - $45,000'],
                ['label' => 'Mid-Range', 'notes' => 'Multiple rooms, upgraded flooring, bathroom rough-in', 'price' => '$45,000 - $70,000'],
                ['label' => 'High-End', 'notes' => 'Full bathroom, wet bar, custom built-ins', 'price' => '$70,000 - $100,000+'],
            ],
        ],
    ],
    [
        'type' => 'pricing_table',
        'data' => [
            'title' => 'Flooring Costs in New Haven, CT',
            'subtitle' => 'Many pre-war New Haven homes have original hardwood floors in good condition underneath carpet or vinyl installed in later decades. Where original floors can be refinished, that is almost always the right choice, both for cost and for architectural integrity.',
            'columns' => ['Material', 'Best For', 'Installed Cost/sq ft'],
            'rows' => [
                ['label' => 'Solid Hardwood', 'notes' => 'Living rooms, dining rooms, bedrooms', 'price' => '$12 - $25'],
                ['label' => 'Engineered Hardwood', 'notes' => 'Basements, moisture-prone areas', 'price' => '$8 - $18'],
                ['label' => 'Luxury Vinyl Plank', 'notes' => 'Basements, kitchens, high-traffic', 'price' => '$6 - $14'],
                ['label' => 'Tile', 'notes' => 'Bathrooms, kitchens, entryways', 'price' => '$12 - $25'],
            ],
        ],
    ],
    [
        'type' => 'pricing_table',
        'data' => [
            'title' => 'Home Additions Costs in New Haven, CT',
            'subtitle' => 'Pre-war homes in New Haven may require structural reinforcement and historic district review for additions, adding to project scope.',
            'columns' => ['Type', 'Scope', 'Typical Range'],
            'rows' => [
                ['label' => 'Bump-Out', 'notes' => 'Single-room expansion, 100-200 sq ft', 'price' => '$150 - $400/sq ft'],
                ['label' => 'Single-Story', 'notes' => 'Family room, sunroom, or garage conversion', 'price' => '$150 - $400/sq ft'],
                ['label' => 'Second-Story', 'notes' => 'Full second floor with structural support', 'price' => '$200 - $400/sq ft'],
            ],
        ],
    ],
    [
        'type' => 'pricing_table',
        'data' => [
            'title' => 'Interior Painting Costs in New Haven, CT',
            'columns' => ['Scope', 'Details', 'Typical Range'],
            'rows' => [
                ['label' => 'Per Square Foot', 'notes' => 'Walls, ceilings, trim, proper surface prep', 'price' => '$3 - $6/sq ft'],
                ['label' => 'Single Room', 'notes' => 'Average bedroom or living room', 'price' => '$800 - $2,500'],
                ['label' => 'Whole Home', 'notes' => 'Full interior, all rooms, trim, doors', 'price' => '$8,000 - $25,000+'],
            ],
        ],
    ],
    [
        'type' => 'pricing_table',
        'data' => [
            'title' => 'Interior Carpentry Costs in New Haven, CT',
            'subtitle' => 'Victorian homes in New Haven often have original trim profiles that require custom matching. Pre-war molding profiles cannot be sourced from standard lumber yards.',
            'columns' => ['Scope', 'Details', 'Typical Range'],
            'rows' => [
                ['label' => 'Hourly Rate', 'notes' => 'Custom trim, built-ins, shelving, wainscoting', 'price' => '$75 - $150/hour'],
                ['label' => 'Crown Molding', 'notes' => 'Per linear foot, installed', 'price' => '$8 - $25/LF'],
                ['label' => 'Custom Built-Ins', 'notes' => 'Bookcases, window seats, mudroom storage', 'price' => '$3,000 - $15,000+'],
            ],
        ],
    ],
    [
        'type' => 'pricing_table',
        'data' => [
            'title' => 'Additional Service Costs in New Haven, CT',
            'columns' => ['Service', 'Details', 'Typical Range'],
            'rows' => [
                ['label' => 'Attic Conversions', 'notes' => 'Framing, insulation, electrical, flooring, egress', 'price' => '$50,000 - $150,000'],
                ['label' => 'Decks and Porches', 'notes' => 'Wood, composite, or PVC with railings and permits', 'price' => '$15,000 - $75,000'],
                ['label' => 'Design and Planning', 'notes' => 'Layout, material selection, 3D rendering, permit drawings', 'price' => '$2,500 - $15,000'],
                ['label' => 'Comfort and Accessibility', 'notes' => 'Grab bars, walk-in showers, widened doorways, ramps', 'price' => '$5,000 - $50,000'],
                ['label' => 'Insurance Reconstruction', 'notes' => 'Fire, water, storm damage rebuilds with carrier coordination', 'price' => '$25,000 - $250,000+'],
            ],
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            '',
            'What Drives Cost in This Market',
            "New Haven's pre-war housing stock is the primary cost driver. Over 40% of homes predate 1940, which means projects regularly involve balloon framing remediation, knob-and-tube rewiring at $15,000 to $30,000, lead paint abatement, asbestos testing, and steam heat coordination. Historic district requirements in Wooster Square add HDC review timelines and material specifications. The city's online-only permit portal runs up to 30 days for standard review. These are not surprises if you work with a contractor who is honest about what is inside the walls of a 100-year-old New Haven home.",
            ['anchor_id' => 'new-haven-cost-drivers', 'container_width' => 'wide']
        ),
    ],
    [
        'type' => 'services_grid',
        'data' => [
            'eyebrow' => 'Our Services',
            'title' => 'Our Remodeling Services in New Haven, CT',
            'highlight_text' => 'New Haven, CT',
            'subtitle' => "BuiltWell CT provides a full range of remodeling services in New Haven including kitchen renovation, bathroom remodeling, basement finishing, flooring, home additions, interior painting, carpentry, attic conversions, decks, design, and accessibility modifications, all permitted and backed by CT HIC License #0668405. New Haven's Victorians, multi-family properties, pre-war housing in East Rock and Westville, and historic district requirements shape how we approach every project.",
            'initial_visible_count' => 6,
            'toggle_label' => 'Show 6 More Services',
            'toggle_less_label' => 'Show Less',
            'items' => [
                ['title' => 'Kitchen Remodeling in New Haven, CT', 'summary' => 'Full kitchen renovations in New Haven\'s pre-war housing stock, including structural assessment for load-bearing walls, cabinetry, countertops, and electrical upgrades.', 'image' => '/services/kitchen-remodeling-ct.jpg', 'image_alt' => 'Kitchen remodeling in New Haven CT by BuiltWell from our Orange office', 'url' => '/kitchen-remodeling/new-haven-ct/', 'cta_label' => 'Get Started', 'price' => '$25K-$150K+', 'timeline' => '6-12 weeks'],
                ['title' => 'Bathroom Remodeling in New Haven, CT', 'summary' => 'Complete bathroom renovations in New Haven including tile, vanities, showers, tubs, plumbing upgrades, and steam system coordination where needed.', 'image' => '/services/bathroom-remodeling-ct.jpg', 'image_alt' => 'Bathroom remodeling in New Haven CT by BuiltWell from our Orange office', 'url' => '/bathroom-remodeling/new-haven-ct/', 'cta_label' => 'Get Started', 'price' => '$15K-$80K+', 'timeline' => '3-6 weeks'],
                ['title' => 'Basement Finishing in New Haven, CT', 'summary' => 'Convert unfinished basements into living space in New Haven with proper moisture assessment, stone foundation work, and egress windows as required.', 'image' => '/services/basement-finishing-ct.jpg', 'image_alt' => 'Basement finishing in New Haven CT by BuiltWell from our Orange office', 'url' => '/basement-finishing/new-haven-ct/', 'cta_label' => 'Get Started', 'price' => '$25K-$100K+', 'timeline' => '4-8 weeks'],
                ['title' => 'Flooring in New Haven, CT', 'summary' => 'Hardwood, LVP, tile, and engineered wood for New Haven homes. We assess original floors before recommending replacement and refinish where the wood is worth keeping.', 'image' => '/services/flooring-installation-ct.jpg', 'image_alt' => 'Flooring installation in New Haven CT by BuiltWell from our Orange office', 'url' => '/flooring/new-haven-ct/', 'cta_label' => 'Get Started', 'price' => '$6-$25/sq ft', 'timeline' => '2-5 days'],
                ['title' => 'Home Additions in New Haven, CT', 'summary' => 'Single-story and second-story additions, sunrooms, and bump-outs with full structural work for New Haven properties.', 'image' => '/services/home-additions-ct.jpg', 'image_alt' => 'Home additions in New Haven CT by BuiltWell from our Orange office', 'url' => '/home-additions/', 'cta_label' => 'Get Started', 'price' => '$150-$400/sq ft', 'timeline' => '8-16 weeks'],
                ['title' => 'Interior Painting in New Haven, CT', 'summary' => 'Walls, ceilings, trim, doors, and built-ins with professional-grade paints and proper prep for New Haven homes.', 'image' => '/services/interior-painting-ct.jpg', 'image_alt' => 'Interior painting in New Haven CT by BuiltWell from our Orange office', 'url' => '/interior-painting/', 'cta_label' => 'Get Started', 'price' => '$3-$6/sq ft', 'timeline' => '2-5 days'],
                ['title' => 'Interior Carpentry in New Haven, CT', 'summary' => 'Custom millwork, built-in cabinetry, wainscoting, crown molding, coffered ceilings, closet systems, and finish trim for New Haven residences.', 'image' => '/services/interior-carpentry-ct.jpg', 'image_alt' => 'Interior carpentry and custom millwork in New Haven CT by BuiltWell', 'url' => '/interior-carpentry/', 'cta_label' => 'Get Started', 'price' => '$75-$150/hr', 'timeline' => 'Varies'],
                ['title' => 'Attic Conversions in New Haven, CT', 'summary' => 'Converting unfinished attics in New Haven into bedrooms, offices, or playrooms with structural assessment through final finish.', 'image' => '/services/attic-conversions-ct.jpg', 'image_alt' => 'Attic conversion remodeling in New Haven CT by BuiltWell', 'url' => '/attic-conversions/', 'cta_label' => 'Get Started', 'price' => '$50K-$150K', 'timeline' => '6-12 weeks'],
                ['title' => 'Decks and Porches in New Haven, CT', 'summary' => 'Pressure-treated lumber, composite, and hardwood for New Haven outdoor spaces. Covered porches, screened-in structures, pergolas, and multi-level decks.', 'image' => '/services/decks-porches-ct.jpg', 'image_alt' => 'Deck and porch construction in New Haven CT by BuiltWell', 'url' => '/decks-porches/', 'cta_label' => 'Get Started', 'price' => '$15K-$75K', 'timeline' => '2-4 weeks'],
                ['title' => 'Design and Planning in New Haven, CT', 'summary' => 'Space planning, material selection, finish coordination, and project documentation for New Haven remodeling projects before construction begins.', 'image' => '/services/design-planning-ct.jpg', 'image_alt' => 'Remodeling design and planning in New Haven CT by BuiltWell', 'url' => '/remodeling-design-planning/', 'cta_label' => 'Get Started', 'price' => '$2.5K-$15K', 'timeline' => '2-6 weeks'],
                ['title' => 'Comfort and Accessibility in New Haven, CT', 'summary' => 'Grab bars, roll-in showers, wider doorways, ramp installation, and first-floor adaptations for New Haven homeowners of all ages and abilities.', 'image' => '/services/comfort-accessibility-ct.jpg', 'image_alt' => 'Comfort and accessibility remodeling in New Haven CT by BuiltWell', 'url' => '/comfort-accessibility-remodeling/', 'cta_label' => 'Get Started', 'price' => '$5K-$50K', 'timeline' => '1-4 weeks'],
                ['title' => 'Insurance Reconstruction in New Haven, CT', 'summary' => 'Rebuilding New Haven homes after fire, water, and storm damage. Full reconstruction once cleanup is complete, working directly with your insurance carrier.', 'image' => '/portfolio/builtwell-contractor-handshake-arrival-ct-optimized.jpg', 'image_alt' => 'Insurance reconstruction contractor in New Haven CT by BuiltWell', 'url' => '/insurance-restoration/', 'cta_label' => 'Get Started', 'price' => '$25K-$250K+', 'timeline' => '4-16 weeks'],
            ],
        ],
    ],
    [
        'type' => 'cta_block',
        'data' => [
            'title' => 'Ready to Remodel in New Haven?',
            'subtitle' => 'From our Orange headquarters - 15 minutes from New Haven - we bring local expertise and a straightforward process to every project in the city.',
            'button' => ['label' => 'Get Your Free Estimate', 'url' => '#contact'],
            'subtext' => 'On-site or remote via Google Meet. No charge, no obligation.',
            'variant' => 'default',
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            'Working With Us',
            'What to Expect Working With BuiltWell',
            "We handle all New Haven permits, Building Department portal submissions, HDC coordination where applicable, inspection scheduling, and subcontractor management under CT HIC License #0668405, with daily progress updates and a five-step process covering consultation through final walkthrough. We carry full liability insurance and workers' compensation, and we do not start a project until permits are pulled and the scope is in writing with a clear timeline attached.\n\nOur five-step process covers every project regardless of scale: Consultation, Planning, Selections, Build, and Walkthrough. You can read through the full process at [/process/](/process/). In practice, what this means for a New Haven project is that the planning phase accounts for the online permit portal timeline, HDC review where the property is in a local historic district, and any pre-war remediation items (knob-and-tube rewiring, lead abatement, asbestos testing) that need to be in the scope from the start rather than discovered mid-project.\n\nDuring active construction, you receive daily updates on progress and a clean job site at the end of every workday. If something unexpected turns up inside a wall, you hear from us that day with an explanation of what we found and your options before we proceed.\n\nH3: New Haven County Project Reference\n\nOur work in New Haven County includes a whole-home restoration in nearby Hamden, where the project involved flooring, interior painting, bathroom remodeling, and drywall throughout a home that had sustained significant damage. \"We were devastated when we saw the damage. BuiltWell took everything off our plate,\" said the Martins, Hamden. You can read the full case study at [/case-studies/whole-home-restoration-hamden/](/case-studies/whole-home-restoration-hamden/).\n\nNew Haven projects are served from our [Orange, CT office](/new-haven-county/orange-ct/). For county-level context on the full scope of what we do in this market, visit [/new-haven-county/](/new-haven-county/).",
            ['highlight_text' => 'Working With BuiltWell', 'anchor_id' => 'new-haven-expect', 'container_width' => 'narrow']
        ),
    ],
    [
        'type' => 'faq_list',
        'data' => [
            'eyebrow' => 'Common Questions',
            'title' => 'Frequently Asked Questions: New Haven Remodeling',
            'highlight_text' => 'New Haven Remodeling',
            'subtitle' => "New Haven homeowners most commonly ask about permit requirements, remodeling costs, project timelines, and what makes renovation more complex in the city's pre-war Victorian, Craftsman, and Colonial housing stock.",
            'items' => [
                ['question' => 'Do I need a permit for a kitchen remodel in New Haven, CT?', 'answer' => "Yes. Any kitchen remodel that involves structural work, electrical changes, or plumbing modifications requires permits through New Haven's online-only Building Department portal at 165 Church Street. Paper applications are not accepted. The standard approval timeline is 30 days by law. Express permits for simpler scope work are issued instantly unless the property is in a local historic district, which triggers HDC Certificate of Appropriateness review first. Structural work requires plans stamped by a Connecticut-licensed architect or engineer. We handle all permit applications and inspection coordination as part of every project. You do not need to navigate the portal yourself."],
                ['question' => 'What makes remodeling a Victorian or pre-war home in New Haven different from newer construction?', 'answer' => 'Pre-war homes in New Haven regularly involve six conditions that newer construction does not: balloon framing (studs running continuously from foundation to roof without fire blocking at floor lines, requiring fire blocking installation when walls are opened under current code); three-coat plaster on wood lath rather than drywall (different demo volume, different repair approach); knob-and-tube wiring (no ground, cannot be covered with insulation, CT code requires replacement during significant renovation at $15,000 to $30,000 for a full rewire); lead paint in multiple layers (EPA RRP certification required for any work disturbing more than six square feet per room); asbestos-containing materials in pipe insulation, floor tiles, and ceiling materials (licensed abatement required above threshold quantities under CT DPH rules); and steam heat systems requiring specialty trade knowledge for boiler replacement or conversion. A contractor who knows these conditions flags them at the consultation. One who does not learns them as change orders after demolition starts.'],
                ['question' => 'How much does a kitchen remodel cost in New Haven, CT?', 'answer' => "Kitchen remodeling in New Haven costs $25,000 to $50,000 for a basic refresh, $50,000 to $90,000 for a mid-range project with new cabinets and updated layout, and $90,000 to $150,000 or more for a high-end renovation with custom cabinetry and structural modifications. In East Rock, Prospect Hill, Wooster Square, and Beaver Hills, projects in pre-war homes typically carry an additional $15,000 to $40,000 in remediation costs for items that arise from the housing stock's age: lead abatement, knob-and-tube rewiring, asbestos testing and licensed abatement where required, and steam system coordination. These are not unexpected surprises if you work with a contractor who is honest about what is likely inside the walls of a 100-year-old New Haven home."],
                ['question' => 'Does my Wooster Square or East Rock home need Historic District approval for renovation?', 'answer' => 'It depends on which neighborhood and which type of district. Wooster Square is covered by New Haven\'s first local historic district (established 1970), which requires a Certificate of Appropriateness from the Historic District Commission before any exterior architectural change can proceed. This applies to windows, doors, siding, exterior trim, and other exterior features. Contact the HDC at hdc@newhavenct.gov before planning any exterior work. East Rock is covered by the Whitney Avenue Historic District, which is a National Register listing only, not a local historic district. National Register status does not impose mandatory review on exterior changes for privately owned residential properties. You do not need HDC approval to change your windows or siding in East Rock. You are, however, eligible for Connecticut state historic tax credits on qualified rehabilitation work, which is worth discussing with your tax advisor.'],
                ['question' => 'How long does a bathroom remodel take in New Haven?', 'answer' => "A typical bathroom remodel in New Haven takes three to six weeks for active construction. Add one to two weeks if the project involves steam system work. Replacing a steam supply line or coordinating with the steam heat system requires scheduling a specialty trade that adds time to the sequence. Permit approval through New Haven's Building Department runs up to 30 days, though express permits for simpler scope work are issued instantly. We build the permit timeline into the project schedule during planning so your start date reflects what is actually achievable."],
                ['question' => 'What happens if my New Haven, CT home needs reconstruction after a fire?', 'answer' => 'If your New Haven home needs reconstruction after a fire, the process starts with your insurance claim and an adjuster\'s damage assessment, followed by professional mitigation and then full rebuild. We handle fire reconstruction projects in New Haven from initial board-up and debris removal through complete restoration. We work directly with insurance carriers including State Farm, Liberty Mutual, Travelers, and The Hartford to document the full scope of damage, negotiate fair pricing on supplementals, and manage the rebuild. In New Haven\'s older housing stock, fire damage often reveals pre-existing conditions like knob-and-tube wiring or asbestos that must be addressed during reconstruction under current code, which we document for supplemental coverage. We bill your insurance carrier directly so you are not managing contractor payments out of pocket. Fire reconstruction in New Haven typically takes 3 to 6 months depending on the extent of structural damage. We hold CT HIC License #0668405 and carry the liability and workers\' compensation coverage that carriers require.'],
                ['question' => 'How do you handle lead paint during renovations in New Haven, CT?', 'answer' => "Lead paint in New Haven renovations is handled through EPA-certified Renovation, Repair, and Painting (RRP) procedures, which are legally required for any work disturbing more than six square feet of painted surface in homes built before 1978. The majority of New Haven's housing stock predates 1978, so lead paint is a factor on most renovation projects in neighborhoods like East Rock, Wooster Square, Westville, and Beaver Hills. Our crews are EPA RRP-certified and follow containment, dust control, and cleanup protocols on every project where lead paint is present. We test painted surfaces before demolition begins. If lead is found, we install plastic containment barriers, use HEPA-filtered equipment, and perform clearance testing after the work is complete. Lead abatement adds $2,000 to $8,000 to a typical renovation depending on the number of rooms and layers involved. This is not optional work. It is a federal requirement, and any contractor who skips it is putting your family at risk and exposing you to liability."],
            ],
        ],
    ],
    [
        'type' => 'areas_served',
        'data' => [
            'eyebrow' => 'Nearby Towns',
            'title' => 'New Haven County Towns We Also Serve',
            'highlight_text' => 'We Also Serve',
            'subtitle' => 'We serve all of New Haven County from our Orange, CT office. Orange is our home base and the town where we know the roads, the housing stock, and the Building Department best.',
            'counties' => [[
                'name' => 'New Haven County',
                'image' => '/images/areas/new-haven-county.jpg',
                'image_alt' => 'New Haven County Connecticut towns served by BuiltWell CT',
                'url' => '/new-haven-county/',
                'phone' => '(203) 466-9148',
                'description' => 'Served from our Orange, CT office. We cover every town in the county with dedicated local crews who know the housing stock and building departments.',
                'towns' => ['Orange', 'New Haven', 'Hamden', 'Branford', 'Guilford', 'Madison', 'Woodbridge', 'Milford', 'Cheshire'],
                'extra_towns' => ['Ansonia', 'Beacon Falls', 'Bethany', 'Derby', 'East Haven', 'Meriden', 'Middlebury', 'Naugatuck', 'North Branford', 'North Haven', 'Oxford', 'Prospect', 'Seymour', 'Southbury', 'Wallingford', 'Waterbury', 'West Haven', 'Wolcott'],
                'town_links' => ['Orange' => '/new-haven-county/orange-ct/', 'New Haven' => '/new-haven-county/new-haven-ct/', 'Hamden' => '/new-haven-county/hamden-ct/', 'Branford' => '/new-haven-county/branford-ct/', 'Guilford' => '/new-haven-county/guilford-ct/', 'Madison' => '/new-haven-county/madison-ct/', 'Woodbridge' => '/new-haven-county/woodbridge-ct/', 'Milford' => '/new-haven-county/milford-ct/'],
                'cta_label' => 'Learn more about New Haven County',
            ]],
        ],
    ],
    [
        'type' => 'trust_bar',
        'data' => ['variant' => 'strip', 'items' => [
            ['icon' => 'star', 'label' => 'Google Rating', 'value' => '4.9', 'url' => 'https://www.google.com/search?q=builtwell+ct+reviews'],
            ['icon' => 'check-circle', 'label' => 'Trusted on', 'value' => 'Houzz', 'url' => 'https://www.houzz.com/professionals/general-contractors/builtwell-ct'],
            ['icon' => 'license', 'label' => 'CT HIC License', 'value' => '#0668405', 'url' => 'https://www.elicense.ct.gov/Lookup/LicenseLookup.aspx'],
            ['icon' => 'check-circle', 'label' => 'Verified on', 'value' => 'Angi', 'url' => 'https://www.angi.com/companylist/us/ct/orange/builtwell-ct-reviews-'],
        ]],
    ],
    [
        'type' => 'lead_form',
        'data' => [
            'eyebrow' => 'Get Started',
            'title' => 'Schedule Your Free Consultation',
            'subtitle' => 'On-site or remote (Google Meet or Zoom). Call (203) 466-9148 or fill out the form below.',
            'subtitle2' => "We're based in Orange, CT and serve all of New Haven County. We'll confirm your appointment within one business day.",
            'images' => [
                ['image' => '/team/builtwell-owner-handshake-client-ct-02.jpg', 'alt' => 'BuiltWell CT meeting with a New Haven homeowner for a remodeling consultation'],
                ['image' => '/portfolio/builtwell-job-site-aerial-ct.jpg', 'alt' => 'BuiltWell CT owner meeting homeowner for a free consultation'],
            ],
            'fields' => [
                ['name' => 'full_name', 'label' => 'Name', 'type' => 'text', 'required' => true],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'required' => true],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                ['name' => 'zip', 'label' => 'Zip Code', 'type' => 'text', 'required' => true],
                ['name' => 'services_needed', 'label' => 'Services Needed', 'type' => 'checkbox_group', 'required' => true, 'options' => [
                    ['label' => 'Kitchen Remodeling', 'value' => 'Kitchen Remodeling'], ['label' => 'Bathroom Remodeling', 'value' => 'Bathroom Remodeling'], ['label' => 'Basement Finishing', 'value' => 'Basement Finishing'], ['label' => 'Flooring Installation', 'value' => 'Flooring Installation'], ['label' => 'Home Additions', 'value' => 'Home Additions'], ['label' => 'Interior Painting', 'value' => 'Interior Painting'], ['label' => 'Interior Carpentry', 'value' => 'Interior Carpentry'], ['label' => 'Attic Conversions', 'value' => 'Attic Conversions'], ['label' => 'Decks and Porches', 'value' => 'Decks and Porches'], ['label' => 'Design and Planning', 'value' => 'Design and Planning'], ['label' => 'Comfort and Accessibility', 'value' => 'Comfort and Accessibility'], ['label' => 'Other', 'value' => 'Other'],
                ]],
                ['name' => 'best_time', 'label' => 'Best Time to Contact', 'type' => 'select', 'required' => true, 'options' => [
                    ['label' => 'Morning (8am - 12pm)', 'value' => 'Morning (8am - 12pm)'], ['label' => 'Afternoon (12pm - 4pm)', 'value' => 'Afternoon (12pm - 4pm)'], ['label' => 'Evening (4pm - 6pm)', 'value' => 'Evening (4pm - 6pm)'], ['label' => 'Anytime', 'value' => 'Anytime'],
                ]],
                ['name' => 'contact_method', 'label' => 'Preferred Contact Method', 'type' => 'radio_group', 'required' => true, 'options' => [
                    ['label' => 'Call', 'value' => 'call'], ['label' => 'Text', 'value' => 'text'], ['label' => 'Email', 'value' => 'email'],
                ]],
                ['name' => 'project_details', 'label' => 'Tell Us About Your Project', 'type' => 'textarea', 'required' => false],
                ['name' => 'photos', 'label' => 'Upload Photos', 'type' => 'file', 'required' => false],
            ],
            'submit_label' => 'Get Your Free Estimate',
            'consent_text' => 'We respond within 24 hours. No spam, no obligation.',
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => $rich(
            '',
            'Flexible Financing Available',
            'Get approved in about 60 seconds and start your project today.',
            ['cta' => ['label' => 'Check Financing Options', 'url' => '/financing/'], 'style_variant' => 'financing_strip', 'spacing' => 'compact']
        ),
    ],
];

DB::transaction(function () use ($pagePayload, $sections): void {
    $page = Page::query()->firstOrNew(['full_path' => $pagePayload['full_path']]);
    $page->fill($pagePayload);
    $page->save();
    $page->sections()->delete();

    foreach ($sections as $index => $sectionPayload) {
        Section::query()->create([
            'page_id' => $page->id,
            'type' => $sectionPayload['type'],
            'data' => $sectionPayload['data'],
            'sort_order' => $index + 1,
            'is_active' => true,
        ]);
    }
});

ApiPageController::forgetCacheForPath('/new-haven-county/new-haven-ct');
Artisan::call('optimize:clear');

$page = Page::query()
    ->where('full_path', '/new-haven-county/new-haven-ct')
    ->with(['sections' => fn ($query) => $query->orderBy('sort_order')])
    ->firstOrFail();

echo json_encode([
    'page_id' => $page->id,
    'full_path' => $page->full_path,
    'template_key' => $page->template_key,
    'section_types' => $page->sections->pluck('type')->all(),
    'section_count' => $page->sections->count(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;
