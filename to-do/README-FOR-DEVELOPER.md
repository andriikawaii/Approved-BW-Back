# BuiltWell CT -- Developer Handoff Package

## What This Is

This folder contains all 106 pages of website content for buildwellct.com, plus root-level configuration files. Every page is production-ready with meta titles, descriptions, schema markup, internal links, CTAs, image recommendations, and footer assignments.

---

## Folder Structure

```
developer-handoff/
|
|-- root-files/
|   |-- llms.txt          --> Deploy to: buildwellct.com/llms.txt
|   |-- robots.txt        --> Deploy to: buildwellct.com/robots.txt
|
|-- content/
|   |-- core-pages/          (18 files) -- Homepage, About, Contact, FAQ, Process,
|   |                                      Services Hub, County Hubs, Orange Office,
|   |                                      Portfolio, Reviews, Careers, Warranty,
|   |                                      Financing, Free Consultation, Homeowner Hub,
|   |                                      Areas We Serve, Case Studies Hub
|   |
|   |-- global-pages/        (11 files) -- 4 Primary + 7 Secondary service pages
|   |   |-- 01-kitchen.md         --> /kitchen-remodeling/
|   |   |-- 02-bathroom.md        --> /bathroom-remodeling/
|   |   |-- 03-basement.md        --> /basement-finishing/
|   |   |-- 04-additions.md       --> /home-additions/
|   |   |-- 05-flooring.md        --> /flooring/
|   |   |-- 06-painting.md        --> /interior-painting/
|   |   |-- 07-carpentry.md       --> /interior-carpentry/
|   |   |-- 08-attic.md           --> /attic-conversions/
|   |   |-- 09-decks.md           --> /decks-porches/
|   |   |-- 10-design-planning.md --> /remodeling-design-planning/
|   |   |-- 11-comfort-accessibility.md --> /comfort-accessibility-remodeling/
|   |
|   |-- service-town-pages/  (64 files) -- 4 services x 16 towns
|   |   |-- kitchen/    (16 towns)  --> /kitchen-remodeling/{town}-ct/
|   |   |-- bathroom/   (16 towns)  --> /bathroom-remodeling/{town}-ct/
|   |   |-- basement/   (16 towns)  --> /basement-finishing/{town}-ct/
|   |   |-- flooring/   (16 towns)  --> /flooring/{town}-ct/
|   |
|   |-- town-hub-pages/      (4 files) -- Strategic town hubs
|   |   |-- greenwich-ct.md   --> /fairfield-county/greenwich-ct/
|   |   |-- westport-ct.md    --> /fairfield-county/westport-ct/
|   |   |-- new-haven-ct.md   --> /new-haven-county/new-haven-ct/
|   |   |-- madison-ct.md     --> /new-haven-county/madison-ct/
|   |
|   |-- case-studies/         (5 files)
|   |   |-- kitchen-remodeling-new-canaan.md   --> /case-studies/kitchen-remodeling-new-canaan/
|   |   |-- kitchen-remodeling-milford.md      --> /case-studies/kitchen-remodeling-milford/
|   |   |-- bathroom-remodeling-westport.md    --> /case-studies/bathroom-remodeling-westport/
|   |   |-- basement-finishing-darien.md       --> /case-studies/basement-finishing-darien/
|   |   |-- whole-home-restoration-hamden.md   --> /case-studies/whole-home-restoration-hamden/
|   |
|   |-- utility-pages/       (4 files)
|       |-- privacy-policy.md   --> /privacy-policy/
|       |-- terms-of-service.md --> /terms/
|       |-- thank-you.md        --> /thank-you/
|       |-- sitemap.md          --> /sitemap/
```

---

## Root Files -- Deployment Instructions

### llms.txt
- Place at site root: `buildwellct.com/llms.txt`
- Plain text Markdown file (UTF-8)
- Helps AI models (ChatGPT, Claude, Perplexity) understand the site
- Update when adding new services, locations, or case studies
- Keep under 10KB

### robots.txt
- Place at site root: `buildwellct.com/robots.txt`
- Allows all major search engines and AI crawlers
- References the sitemap at `buildwellct.com/sitemap.xml`

---

## Content File Format

Every content file follows this structure:

```
PAGE: [Page Name]
URL: [Full URL path]
TEMPLATE: [Template type]
COUNTY: [Fairfield / New Haven / Global]

[META] -- Title (under 60 chars) and Description (150-160 chars)
[SCHEMA] -- JSON-LD markup (copy directly into page head)
[CONTENT] -- Full page content with H1, H2, H3 hierarchy
[INTERNAL LINKS] -- All internal links with anchor text
[CTA] -- Call-to-action block with correct phone number
[IMAGE RECOMMENDATIONS] -- Suggested images with alt text
[FOOTER TEMPLATE] -- Which footer template to use (A, B, C, or D)
```

---

## Critical URL Rules

- Service x Town pages: `/{service}/{town}-ct/` (NOT `/services/{service}/{town}-ct/`)
- County hubs: `/fairfield-county/` (NOT `/areas-we-serve/fairfield-county/`)
- Orange office: `/new-haven-county/orange-ct/`

---

## Footer Templates

| Template | Used On | Content |
|----------|---------|---------|
| A | Global pages | Both phones, no address |
| B | Fairfield pages | "Fairfield County Service Area Team" + (203) 919-9616 |
| C | New Haven pages | "Served from our Orange, CT office" + (203) 466-9148 |
| D | Orange page ONLY | Full address + hours + map |

---

## Phone Numbers

- Fairfield County pages: (203) 919-9616 only
- New Haven County pages: (203) 466-9148 only
- Global pages: both phones, labeled by county

---

## Schema Rules

| Schema Type | Where |
|-------------|-------|
| BreadcrumbList | Every page |
| FAQPage | /faq/ page ONLY |
| HomeAndConstructionBusiness | /new-haven-county/orange-ct/ ONLY |
| Service | Service x Town pages ONLY |
| Organization | Homepage ONLY |

---

## Page Count Summary

| Category | Count |
|----------|-------|
| Core pages | 18 |
| Global service pages | 11 |
| Town hub pages | 4 |
| Service x Town pages | 64 |
| Case studies | 5 |
| Utility pages | 4 |
| **Total content files** | **106** |
| Root files (llms.txt, robots.txt) | 2 |

---

## Reference Document

The full technical specification is in `source/technical-spec-v3.3.md` in the main project folder. That document is the single source of truth for all structural, schema, linking, and formatting decisions.

---

*Prepared: March 2026*
*Source: BuiltWell CT Technical Specification v3.3 FINAL*
