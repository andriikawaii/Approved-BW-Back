<?php

use App\Http\Controllers\Api\PageController as ApiPageController;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$pagePayload = [
    'full_path' => '/terms',
    'template_key' => 'generic',
    'status' => 'published',
    'published_at' => now(),
    'seo_title' => 'Terms of Service | BuiltWell CT',
    'seo_description' => 'Terms of Service for BuiltWell CT, a licensed Connecticut home remodeling contractor. Review the terms governing use of our website and services.',
    'canonical_url' => null,
];

$termsMarkdown = <<<'MARKDOWN'
## Part 1: Service Terms

## 1. Company Information and Licensing

These Terms of Service ("Terms") govern your use of the website located at buildwellct.com (the "Website") and the home improvement services provided by Legacy of Clean LLC, doing business as BuiltWell CT ("BuiltWell," "we," "us," or "our"). Our principal place of business is located at 206A Boston Post Road, Orange, CT 06477.

BuiltWell CT operates under Connecticut Home Improvement Contractor License #0668405, issued by the Connecticut Department of Consumer Protection. We are authorized to perform home improvement work in the State of Connecticut in accordance with the Connecticut Home Improvement Act (Connecticut General Statutes, Chapter 400, Sections 20-418 through 20-432).

By accessing the Website or engaging our services, you acknowledge that you have read, understood, and agree to be bound by these Terms. If you do not agree to these Terms, you should not use the Website or engage our services.

## 2. Scope of Services

BuiltWell CT provides residential home improvement and remodeling services in Fairfield County and New Haven County, Connecticut. Our services include but are not limited to kitchen remodeling, bathroom remodeling, basement finishing, flooring installation, interior carpentry, and home additions.

The specific scope of work for any project is defined exclusively in the written contract or proposal signed by both the homeowner and BuiltWell CT. No verbal agreement, website content, social media post, advertisement, or informal communication creates a binding obligation on either party. Only a signed written contract establishes the terms of a specific project engagement.

We reserve the right to decline any project at our sole discretion, including but not limited to situations where the requested work falls outside our areas of expertise, where site conditions present safety concerns, or where the project location is outside our service area.

## 3. Estimates and Proposals

BuiltWell CT provides free consultations and estimates to homeowners within our service areas. All estimates, cost ranges, and pricing information communicated verbally, in writing, or on the Website are approximations only and do not constitute binding offers.

Estimates are based on information available at the time of assessment and are subject to change based on factors including but not limited to:

- Actual site conditions discovered during demolition or construction, including hidden damage, structural deficiencies, outdated wiring, plumbing issues, mold, asbestos, or other concealed conditions.
- Changes in material costs, availability, or lead times between the date of the estimate and the date of purchase.
- Modifications to the scope of work requested by the homeowner after the initial estimate.
- Requirements imposed by local building departments, inspectors, or code enforcement officials.
- Changes in applicable building codes or permit requirements.

A binding agreement is created only when both parties execute a written proposal or contract that specifies the scope of work, materials, project timeline, payment schedule, and total cost. All written contracts are separate documents governed by their own terms and by applicable Connecticut law.

## 4. Deposits and Payment Terms

Payment terms for each project are specified in the written contract between the homeowner and BuiltWell CT. In accordance with the Connecticut Home Improvement Act, no deposit or initial payment shall exceed one third (1/3) of the total contract price, or the cost of materials and supplies that have been specially ordered and cannot be used on other projects, whichever is less.

Progress payments are structured around project milestones as defined in the written contract. We do not require full payment before the project is substantially complete. Final payment is due upon completion of the project and the homeowner's approval of the finished work, subject to any punch list items agreed upon by both parties.

All payment methods accepted by BuiltWell CT are specified in the written contract. Late payments may be subject to the terms and conditions outlined in the project contract. BuiltWell CT reserves the right to suspend work on any project where payment obligations are not met according to the agreed schedule.

## 5. Project Scheduling and Timeline

Project timelines provided in estimates, proposals, and contracts are good faith approximations based on conditions known at the time of scheduling. Actual completion dates may vary due to factors beyond our control, including but not limited to:

- Delays in material delivery or manufacturing lead times.
- Weather conditions that affect exterior work, deliveries, or site access.
- Permit processing times and inspection scheduling by local building departments.
- Discovery of concealed conditions requiring additional work.
- Change orders requested by the homeowner.
- Delays caused by other contractors, utility companies, or third parties involved in the project.

BuiltWell CT will communicate schedule changes to the homeowner as promptly as possible. Delays caused by factors beyond our reasonable control do not constitute a breach of contract. We will make commercially reasonable efforts to minimize the impact of any delays on the overall project timeline.

## 6. Change Orders

Any modification to the scope of work, materials, or specifications described in the original written contract must be documented in a written change order signed by both the homeowner and BuiltWell CT before the additional or modified work begins.

Each change order will specify the nature of the change, the impact on the project cost (increase or decrease), and the impact on the project timeline. No verbal request for changes will be treated as authorization to perform additional work. BuiltWell CT is not obligated to perform any work beyond the original contract scope without a signed change order.

The homeowner acknowledges that change orders may affect the overall project timeline and that multiple change orders may result in cumulative schedule delays.

## 7. Permits and Inspections

BuiltWell CT handles all permit applications and coordinates all required inspections for projects under our management, unless the written contract specifies otherwise. Permit fees are included in the project cost unless stated otherwise in the written contract.

The homeowner acknowledges that permit processing times vary by municipality and are outside the control of BuiltWell CT. Delays in permit approvals or inspection scheduling by local building departments may affect the project timeline. The homeowner agrees to provide reasonable access to the property for all required inspections.

All work performed by BuiltWell CT is intended to comply with applicable Connecticut building codes and local municipal requirements. If an inspector requires modifications to approved work, BuiltWell CT will make the necessary corrections at no additional cost to the homeowner, provided the corrections are within the originally contracted scope of work.

## 8. Subcontractors

BuiltWell CT may engage licensed subcontractors to perform specialized work on your project, including but not limited to electrical, plumbing, HVAC, and other trade specific work that requires separate licensing under Connecticut law.

All subcontractors engaged by BuiltWell CT are required to hold valid Connecticut licenses applicable to their trade and to carry appropriate insurance coverage. BuiltWell CT assumes responsibility for the quality and timeliness of all subcontractor work performed under our contracts.

The homeowner's contractual relationship is solely with BuiltWell CT, not with any subcontractor. The homeowner should direct all communications, concerns, and requests through BuiltWell CT rather than directly to subcontractors working on the project.

## 9. Client Responsibilities

The homeowner agrees to the following responsibilities during the course of a contracted project:

- Providing safe and reasonable access to the work area during agreed upon working hours.
- Removing or securing personal belongings, furniture, and valuables from the work area prior to the start of construction, unless the contract specifies that BuiltWell CT will handle this task.
- Making timely decisions on material selections, design choices, and other items requiring homeowner input to avoid project delays.
- Providing accurate information about the property, including known issues such as water intrusion, pest damage, previous renovations, or the presence of hazardous materials.
- Notifying BuiltWell CT promptly of any concerns about the work in progress.
- Making payments according to the schedule specified in the written contract.
- Ensuring that pets are secured away from the work area during construction hours.

Failure by the homeowner to fulfill these responsibilities may result in project delays, additional costs, or both. BuiltWell CT is not responsible for delays or additional costs caused by the homeowner's failure to meet these obligations.

## 10. Warranty

BuiltWell CT provides a workmanship warranty on all contracted projects. The specific warranty terms, duration, and coverage are detailed in the written contract for each project and in our separate [Warranty Policy](/warranty/).

The workmanship warranty covers defects in the installation and construction work performed by BuiltWell CT and its subcontractors. The warranty does not cover:

- Normal wear and tear of materials and finishes.
- Damage caused by the homeowner, occupants, or third parties after project completion.
- Damage resulting from improper maintenance or failure to follow care instructions provided at project completion.
- Damage caused by natural disasters, floods, fire, or other events beyond human control.
- Issues arising from pre-existing conditions that were disclosed to the homeowner prior to or during construction.
- Manufacturer defects in products and materials, which are covered by the manufacturer's own warranty.

Warranty claims must be submitted in writing to BuiltWell CT within the warranty period. We will inspect the reported issue and, if covered under warranty, perform the necessary repairs within a reasonable timeframe.

## 11. Limitation of Liability for Services

To the fullest extent permitted by applicable law, the total aggregate liability of BuiltWell CT for any and all claims arising out of or related to the performance of contracted services shall not exceed the total amount paid by the homeowner under the applicable project contract.

BuiltWell CT shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of use, loss of income, loss of anticipated savings, or damage to property not part of the contracted work, regardless of whether such damages were foreseeable or whether BuiltWell CT was advised of the possibility of such damages.

Nothing in these Terms is intended to limit or exclude any liability that cannot be limited or excluded under applicable Connecticut law, including but not limited to liability under the Connecticut Home Improvement Act (Connecticut General Statutes, Chapter 400, Sections 20-418 through 20-432), the Connecticut Unfair Trade Practices Act (Connecticut General Statutes, Section 42-110a et seq.), or any other consumer protection statute that applies to our services. All rights and remedies available to the homeowner under Connecticut law are preserved.

## 12. Payment Disputes

If the homeowner disputes any portion of an invoice or payment request, the homeowner must provide written notice to BuiltWell CT within ten (10) business days of receiving the invoice, specifying the nature and basis of the dispute. The undisputed portion of any invoice remains due according to the original payment schedule.

Both parties agree to attempt to resolve payment disputes through good faith negotiation before pursuing any formal dispute resolution process. BuiltWell CT will not file a mechanic's lien or take other collection action on disputed amounts while good faith negotiations are in progress, provided the homeowner continues to make payments on undisputed amounts.

## 13. Termination

Either party may terminate a project contract in accordance with the termination provisions specified in that contract. In the absence of specific termination provisions, the following terms apply:

- The homeowner may terminate the contract at any time by providing written notice to BuiltWell CT. Upon termination by the homeowner, the homeowner is responsible for payment of all work completed to date, materials ordered or purchased, and any non-refundable commitments made by BuiltWell CT in connection with the project.
- BuiltWell CT may terminate the contract if the homeowner fails to make payments as agreed, fails to provide access to the work site, or otherwise materially breaches the contract terms, provided that BuiltWell CT first gives written notice and a reasonable opportunity to cure the breach.

The homeowner's right to cancel a home improvement contract within three (3) business days of signing, as provided under the Connecticut Home Improvement Act, is not affected by these Terms.

## 14. Dispute Resolution

In the event of any dispute, claim, or controversy arising out of or relating to a project contract or the services provided by BuiltWell CT, both parties agree to first attempt to resolve the matter through good faith negotiation. Either party may initiate this process by providing written notice to the other party describing the nature of the dispute and the relief sought.

If the dispute cannot be resolved through good faith negotiation within thirty (30) days of the initial written notice, the dispute shall be submitted to binding arbitration administered in New Haven County, Connecticut, in accordance with the rules of the American Arbitration Association. The arbitrator shall have the authority to award any remedy available under Connecticut law. The decision of the arbitrator shall be final and binding on both parties and may be entered as a judgment in any court of competent jurisdiction.

Nothing in this section prevents either party from seeking injunctive or other equitable relief in a court of competent jurisdiction to prevent irreparable harm. Nothing in this section limits the homeowner's right to file a complaint with the Connecticut Department of Consumer Protection or to pursue any remedy available under the Connecticut Home Improvement Act or the Connecticut Unfair Trade Practices Act.

## 15. Governing Law

All project contracts and these Terms shall be governed by and construed in accordance with the laws of the State of Connecticut, without regard to its conflict of law provisions. Any legal action or proceeding relating to a project contract, these Terms, or the services provided by BuiltWell CT shall be brought exclusively in the state or federal courts located in the State of Connecticut.

In the event of any conflict between these Terms and a signed project contract, the terms of the signed project contract shall prevail. In the event of any conflict between these Terms or a project contract and applicable Connecticut law, Connecticut law shall prevail.

## Part 2: Website Terms

## 16. Website Content and Accuracy

The content on this Website, including text, images, photographs, project descriptions, cost ranges, timelines, and other information, is provided for general informational purposes only. While we make reasonable efforts to keep the information on the Website current and accurate, we do not warrant or guarantee the accuracy, completeness, or timeliness of any content.

Cost ranges, project timelines, and other estimates presented on the Website are general approximations intended to provide a starting point for planning purposes. They do not constitute binding price quotes or guarantees. Actual costs and timelines for your specific project will be determined through an individual consultation and documented in a written proposal.

Photographs and images on the Website may depict completed projects, work in progress, or representative examples. Unless specifically identified otherwise, project photos represent work performed by BuiltWell CT. Results may vary based on individual project conditions, material selections, and other factors.

## 17. Intellectual Property and Ownership

All content on this Website, including but not limited to text, images, photographs, graphics, logos, icons, videos, page layouts, and design elements, is the property of Legacy of Clean LLC d/b/a BuiltWell CT or its licensors and is protected by United States copyright, trademark, and other intellectual property laws.

The BuiltWell name, BuiltWell CT name, logo, taglines, and all related marks are trademarks of Legacy of Clean LLC. You may not use any of these marks without our prior written permission.

You may not reproduce, distribute, modify, create derivative works from, publicly display, republish, download, store, or transmit any content from this Website without our prior written permission, except that you may print or download individual pages for your own personal, non-commercial use in connection with evaluating our services for your home improvement project.

## 18. Website Use Restrictions

You may use the Website to learn about our services, view project information, request consultations, and contact our team. You agree to use the Website only for lawful purposes and in accordance with these Terms.

You agree not to use the Website in any way that:

- Involves scraping, data mining, or automated collection of content from the Website without our prior written consent.
- Introduces malware, viruses, or any other harmful code or technology intended to disrupt, damage, or interfere with the Website or its users.
- Involves impersonating BuiltWell CT, its employees, or any other person or entity.
- Violates any applicable local, state, or federal law or regulation.
- Attempts to gain unauthorized access to any portion of the Website, its servers, or any connected systems.
- Interferes with or disrupts the integrity or performance of the Website.
- Uses the Website to send unsolicited commercial communications or advertising.
- Copies, reproduces, or redistributes Website content for commercial purposes without written authorization.

## 19. Website Accuracy Disclaimer

The Website and its content are provided on an "as is" and "as available" basis without warranties of any kind, either express or implied. BuiltWell CT does not warrant that the Website will be uninterrupted, error free, secure, or free of viruses or other harmful components.

We disclaim all warranties, express or implied, including but not limited to implied warranties of merchantability, fitness for a particular purpose, and non-infringement. We do not warrant that the information on the Website is complete, accurate, or current, or that the Website will meet your specific requirements.

You acknowledge that your use of the Website and reliance on any information provided on the Website is at your own risk.

## 20. Third Party Links

The Website may contain links to third party websites or services that are not owned or controlled by BuiltWell CT. These links are provided for your convenience and informational purposes only. We do not endorse, control, or assume any responsibility for the content, privacy policies, or practices of any third party websites or services.

Your use of any third party website or service is at your own risk and is subject to the terms and conditions of that third party. We encourage you to review the terms and privacy policies of any third party website you visit through links on our Website. BuiltWell CT is not liable for any damage or loss caused by or in connection with your use of any third party website or service.

## 21. Website Limitation of Liability

To the fullest extent permitted by law, BuiltWell CT, its owners, officers, employees, and agents shall not be liable for any direct, indirect, incidental, special, consequential, or punitive damages arising from your use of or inability to use the Website, including but not limited to damages for loss of profits, data, goodwill, or other intangible losses.

This limitation applies regardless of the legal theory on which the claim is based, whether in contract, tort, negligence, strict liability, or any other basis, even if BuiltWell CT has been advised of the possibility of such damages.

Some jurisdictions do not allow the exclusion or limitation of certain damages. In such jurisdictions, our liability is limited to the greatest extent permitted by applicable law.

## 22. User Submitted Content

When you submit information through our contact forms, consultation request forms, scheduling tools, or any other method on the Website, you confirm that the information you provide is accurate and truthful to the best of your knowledge.

Any information, photographs, project descriptions, reviews, testimonials, or other materials you submit through the Website may be used by BuiltWell CT for legitimate business purposes, including responding to your inquiry, scheduling consultations, preparing proposals, improving our services, and marketing purposes (with appropriate consent where required by law).

You retain ownership of any original content you submit, but you grant BuiltWell CT a non-exclusive, royalty free, perpetual license to use, display, and reproduce such content in connection with our business operations and marketing. We will handle your personal information in accordance with our [Privacy Policy](/privacy-policy/).

## 23. Indemnification

You agree to indemnify, defend, and hold harmless Legacy of Clean LLC d/b/a BuiltWell CT, its owners, officers, employees, and agents from and against any and all claims, liabilities, damages, losses, costs, and expenses (including reasonable legal fees) arising out of or related to:

- Your misuse of the Website in violation of these Terms.
- Your violation of any applicable law or the rights of any third party.
- Any content you submit through the Website that infringes on the intellectual property or other rights of any third party.
- Any misrepresentation made by you in connection with your use of the Website.

## 24. Severability

If any provision of these Terms is found to be invalid, illegal, or unenforceable by a court of competent jurisdiction, the remaining provisions shall continue in full force and effect. The invalid or unenforceable provision shall be modified to the minimum extent necessary to make it valid and enforceable while preserving the original intent of the provision.

The failure of BuiltWell CT to enforce any right or provision of these Terms shall not constitute a waiver of that right or provision. Any waiver of any provision of these Terms will be effective only if in writing and signed by BuiltWell CT.

## 25. Changes to Terms and Contact Information

BuiltWell CT reserves the right to modify, update, or replace any part of these Terms at any time. Changes will be effective immediately upon posting to the Website. The "Last Updated" date at the top of this page will be revised to reflect the most recent modification.

Your continued use of the Website after any changes to these Terms constitutes your acceptance of the revised Terms. If you do not agree with the updated Terms, you should discontinue use of the Website.

If you have any questions about these Terms of Service, please contact us using any of the methods below.

**Legacy of Clean LLC d/b/a BuiltWell CT**  
CT HIC License #0668405  
206A Boston Post Road  
Orange, CT 06477

**Email:** [info@buildwellct.com](mailto:info@buildwellct.com)

**Phone:**  
New Haven County: [(203) 466-9148](tel:2034669148)  
Fairfield County: [(203) 919-9616](tel:2039199616)

**Website:** [buildwellct.com](https://buildwellct.com)
MARKDOWN;

$sections = [
    [
        'type' => 'hero',
        'data' => [
            'headline' => 'Terms of Service',
            'subheadline' => 'Please review the terms and conditions that govern your use of the BuiltWell CT website and our services.',
            'background_image' => '/images/headers/kitchen-remodeling-header.jpg',
            'overlay' => ['opacity' => 0.52],
            'cta_primary' => ['label' => 'Free Estimate', 'url' => '/free-consultation/'],
            'cta_secondary' => ['label' => 'Call Now', 'url' => 'tel:2034669148'],
        ],
    ],
    [
        'type' => 'rich_text',
        'data' => [
            'title' => 'Terms of Service',
            'legal_updated' => 'Last Updated: March 13, 2026',
            'content' => $termsMarkdown,
            'align' => 'left',
            'variant' => 'default',
            'style_variant' => 'legal',
            'surface' => 'white',
            'container_width' => 'narrow',
            'spacing' => 'normal',
        ],
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

ApiPageController::forgetCacheForPath('/terms');
Artisan::call('optimize:clear');

$page = Page::query()
    ->where('full_path', '/terms')
    ->with(['sections' => fn ($query) => $query->orderBy('sort_order')])
    ->firstOrFail();

echo json_encode([
    'page_id' => $page->id,
    'full_path' => $page->full_path,
    'template_key' => $page->template_key,
    'section_types' => $page->sections->pluck('type')->all(),
    'section_count' => $page->sections->count(),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
