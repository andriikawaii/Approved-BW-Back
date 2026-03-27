<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Page;
use Illuminate\Contracts\Console\Kernel;

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$page = Page::query()
    ->whereIn('full_path', ['/bathroom-remodeling', '/bathroom-remodeling/'])
    ->with(['sections' => fn ($query) => $query->orderBy('sort_order')])
    ->first();

if (!$page) {
    fwrite(STDERR, "Page not found.\n");
    exit(1);
}

$richTexts = $page->sections->where('type', 'rich_text')->values();
$local = $richTexts->get(1);
if ($local) {
    $data = $local->data ?? [];
    $data['eyebrow'] = 'Local Knowledge';
    $data['title'] = 'Why Connecticut Bathroom Remodeling Requires Local Expertise';
    $data['highlight_text'] = 'Local Expertise';
    $data['content'] = "Connecticut homes present bathroom-specific construction conditions that contractors without local experience often miss.\n\nConnecticut homes present bathroom-specific construction conditions that contractors without local experience often miss until they're already into the project, and by then, those surprises cost time and money.\n\nPre-1970 homes throughout both counties frequently have cast iron tubs that can weigh 300 to 400 pounds. Extracting them without damaging the surrounding structure, flooring, or plumbing requires care and experience. Plaster walls, which are common in older New Haven, Greenwich, and Guilford homes, require different preparation behind tile than modern drywall does. The substrate needs to be correct before any tile adhesive goes down, or the tile will fail.\n\nCoastal towns along Long Island Sound, including Westport, Milford, Branford, Guilford, and Madison, deal with elevated ambient humidity and salt air that affect how tile adhesives cure and how grout holds up over time. We account for those conditions in our material recommendations and installation methods.\n\nWalk-in shower installations and tub-to-shower conversions typically require a building permit in Connecticut. Electrical work in bathrooms, including new lighting circuits and GFCI outlets, requires an electrical permit in most towns. Some towns issue these as a single permit; others require separate filings. Towns like Greenwich, New Canaan, and Darien have strict zoning oversight, and projects in historic districts in towns like Guilford and Ridgefield may involve additional review for certain types of work. We handle all permit applications and inspections as a standard part of every project.";
    $local->data = $data;
    $local->save();
}

$faq = $page->sections->firstWhere('type', 'faq_list');
if ($faq) {
    $data = $faq->data ?? [];
    $data['title'] = 'Bathroom Remodeling Questions';
    $data['items'] = [
        [
            'question' => 'Do I need a permit for a bathroom remodel in Connecticut?',
            'answer' => "Yes. Bathroom remodeling work that involves plumbing, electrical, or structural modifications requires permits in all Connecticut towns. Replacing a tub with a walk-in shower almost always requires a building permit, and the electrical work involved in updating bathroom lighting and installing GFCI outlets requires an electrical permit in most municipalities. Even renovations that appear cosmetic can trigger permit requirements if they involve any licensed trade work. Some towns issue a single permit covering all trades; others require separate filings for building, electrical, and plumbing, each with their own inspections. We handle all permit applications and inspection coordination as part of the project; you don't need to manage that process separately.",
        ],
        [
            'question' => 'How long does a bathroom remodel take in CT?',
            'answer' => 'Most full bathroom renovations in Connecticut complete in three to six weeks of active construction. Basic updates such as a new vanity, fixtures, flooring, and paint without major structural or plumbing changes run two to three weeks. Full gut renovations with custom tile, a new shower enclosure, and relocated plumbing typically take five to six weeks. Projects that involve layout changes, plumbing relocation, or expanding the footprint of the bathroom extend beyond that. We provide a specific project schedule in your written proposal so you know your expected start date, key milestones, and projected completion before work begins.',
        ],
        [
            'question' => 'Can I use the bathroom during a remodel?',
            'answer' => "Not the bathroom being renovated. We seal off the work area with dust barriers for safety and dust control, and the space won't be functional until the project is complete. If you have a second bathroom in your home, you'll be able to use that throughout the project. If the bathroom being renovated is your only bathroom, we'll discuss that with you during the planning phase and work through a realistic approach, whether that means sequencing the work to restore basic function as quickly as possible or identifying alternatives. We make sure you understand exactly what access looks like before construction begins.",
        ],
        [
            'question' => "What's the difference between a bathroom refresh and a full remodel?",
            'answer' => "A bathroom refresh updates surfaces and fixtures without opening walls or changing the layout. It typically includes a new vanity and countertop, updated faucets and fixtures, new flooring, paint, and sometimes new lighting. The plumbing supply and drain lines stay in place, and nothing structural changes. A full remodel involves gutting the space to the studs, which allows for layout changes, proper waterproofing behind tile, subfloor repairs, plumbing relocation, and a complete redesign of the space. Refreshes generally start around \$15,000. Full gut renovations start around \$25,000 and go up from there based on scope and material selections. The right choice depends on the condition of your current bathroom and what you're trying to accomplish.",
        ],
        [
            'question' => 'Can you install a walk-in shower in place of a bathtub?',
            'answer' => "Yes. Tub-to-shower conversions are one of our most commonly requested projects, and we handle every aspect: plumbing reconfiguration, drain installation with proper slope for barrier-free entry, waterproofing membrane, tile, and the shower enclosure itself. We'll assess whether the existing floor framing needs reinforcement for the new drain location and whether the subfloor can support the tile system you want. Walk-in shower installations require a building permit in Connecticut, which we handle as part of the project. If the bathroom currently has only one tub and you're replacing it with a shower, we'll discuss that during the consultation, particularly if resale value is a consideration, since some buyers prefer at least one tub in the home.",
        ],
        [
            'question' => 'Does a bathroom remodel increase home value in Connecticut?',
            'answer' => "Yes. Bathroom remodeling consistently ranks among the highest-ROI home improvements in Connecticut. A mid-range bathroom renovation typically returns 60 to 70 percent of the investment at resale, and in competitive Fairfield County markets like Greenwich, Westport, and Darien, updated bathrooms are often expected by buyers rather than viewed as a bonus. The return depends on the scope of the project, the quality of materials used, and how the finished bathroom compares to other homes in your neighborhood. Over-improving relative to neighboring homes can reduce the percentage return. We can help you make material and scope decisions that balance what you want to live with and what makes sense for your home's market position.",
        ],
        [
            'question' => 'What happens if you find unexpected issues behind the walls during demolition?',
            'answer' => 'It happens regularly in Connecticut homes, especially those built before 1990. Common findings include deteriorated plumbing behind tile, water damage to wall framing or subfloor, inadequate waterproofing, galvanized drain lines that have corroded, and occasionally asbestos in old floor tiles or lead paint on existing surfaces. When we discover something unexpected, we contact you that same day, explain what we found, present your options with clear costs, and wait for your approval before proceeding. We do not make decisions about your home without your input. Any additional work is documented in a change order with a specific cost and timeline impact so there are no surprises on the final invoice.',
        ],
        [
            'question' => 'Do you offer financing for bathroom remodeling projects?',
            'answer' => 'Yes. We offer flexible financing through GreenSky, which allows you to get approved in about 60 seconds and start your project right away. Financing options include low monthly payments and promotional periods depending on the plan you choose. Many homeowners use financing to move forward with higher-quality materials or a broader scope of work than they would with cash alone. We can walk you through the options during your consultation so you have a clear picture of both the project cost and the monthly payment before you commit to anything.',
        ],
    ];
    $faq->data = $data;
    $faq->save();
}

echo "Updated bathroom local expertise and FAQ copy.\n";
