# BuiltWell CT Platform Documentation

Last updated: 2026-02-23

This document describes the production behavior of the **BuiltWell CT** platform backend (Laravel) and its contract with the decoupled frontend renderer (Next.js).

## Table of Contents

1. [Overview](#overview)
2. [Tech Stack](#tech-stack)
3. [Repository Structure](#repository-structure)
4. [Key Files](#key-files)
5. [How to Run Locally](#how-to-run-locally)
6. [Environment Variables](#environment-variables)
7. [Data Model Overview](#data-model-overview)
8. [URL Structure and Page Types](#url-structure-and-page-types)
9. [CMS: Pages and Sections](#cms-pages-and-sections)
10. [SEO System](#seo-system)
11. [Structured Data (JSON-LD)](#structured-data-json-ld)
12. [Phone, Address, and Footer Rules (LOCKED)](#phone-address-and-footer-rules-locked)
13. [Redirects](#redirects)
14. [Admin Panel Usage](#admin-panel-usage)
15. [API Documentation](#api-documentation)
16. [Deployment](#deployment)
17. [Verification Checklist](#verification-checklist)
18. [Troubleshooting](#troubleshooting)
19. [Security Notes](#security-notes)
20. [Maintenance](#maintenance)
21. [Spec Compliance](#spec-compliance)
22. [Docs QA Checklist](#docs-qa-checklist)

## Overview

BuiltWell CT uses a **decoupled architecture**:

- **Laravel backend**: API-first content source, admin CMS, SEO rules, structured data, redirects, sitemap, robots.
- **Next.js frontend**: page renderer that consumes `/api/pages/{path}` responses and renders sections.

High-level flow:

```text
Editors (Admin UI)
    |
    v
Laravel CMS (Pages + Sections + SEO + Redirects)
    |
    |  GET /api/pages/{path}
    v
PageController -> PageResource -> Resolvers
    |              | canonical
    |              | phones/footer
    |              | breadcrumbs/schema
    v
JSON payload
    |
    v
Next.js renderer
```

## Tech Stack

- PHP 8.2+
- Laravel 12
- Livewire + Flux (admin UI)
- MySQL (runtime schema in `database/schema/builtwell_backend.sql`)
- Spatie Activitylog
- Spatie Permission
- Tailwind + Vite (admin assets)

## Repository Structure

```text
app/
  Http/Controllers/Api/     # Public page API
  Http/Resources/           # API transformers
  Http/Middleware/          # Redirect resolution middleware
  Livewire/Admin/           # Admin CMS screens
  Models/                   # Domain models
  Observers/                # Cache/redirect side effects
  Services/                 # SEO/schema/footer/phone/breadcrumb logic
  Support/Sections/         # Section registry and policy validation
config/                     # Templates, section rules, app config
database/
  migrations/              # Migration history (some legacy drift exists)
  schema/                  # Runtime schema dump
routes/
  api.php                  # Public page API + preview
  web.php                  # Admin + sitemap/robots/llms routes
public/                    # Public assets
```

## Key Files

Models:

- `app/Models/Page.php`
- `app/Models/Section.php`
- `app/Models/Service.php`
- `app/Models/Town.php`
- `app/Models/County.php`
- `app/Models/Redirect.php`
- `app/Models/Setting.php`

Services and resolvers:

- `app/Services/CanonicalResolver.php`
- `app/Services/PhoneResolver.php`
- `app/Services/FooterTemplateResolver.php`
- `app/Services/SchemaBuilder.php`
- `app/Services/BreadcrumbBuilder.php`
- `app/Services/Seo/SitemapGenerator.php`

Middleware:

- `app/Http/Middleware/ResolvePageRedirect.php`

Resource and API controller:

- `app/Http/Resources/PageResource.php`
- `app/Http/Controllers/Api/PageController.php`

Admin (Livewire) screens:

- Pages: `app/Livewire/Admin/Pages/*`
- Sections library: `app/Livewire/Admin/Sections/Index.php`
- SEO settings: `app/Livewire/Admin/Seo/Settings.php`
- Redirects: `app/Livewire/Admin/Redirects/*`
- Activity log: `app/Livewire/Admin/Ops/Activity.php`

## How to Run Locally

Prerequisites:

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL

Notes:

- This repository currently includes `.env.production.example`, not `.env.example`.
- Copy from `.env.production.example` for local bootstrap, then adjust local values.

```bash
cp .env.production.example .env
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan db:seed --class=RoleSeeder
php artisan serve
npm run dev
```

Optional production-like asset build:

```bash
npm run build
```

Useful local cache commands:

```bash
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Environment Variables

Primary source file: `.env.production.example`

Core app:

- `APP_NAME`
- `APP_ENV`
- `APP_KEY`
- `APP_DEBUG`
- `APP_URL` (backend domain)
- `FRONTEND_URL` (public frontend domain used by canonical/schema/sitemap)

Database:

- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

Session/cache/queue:

- `SESSION_DRIVER`
- `SESSION_LIFETIME`
- `SESSION_ENCRYPT`
- `CACHE_STORE`
- `QUEUE_CONNECTION`

Mail:

- `MAIL_MAILER`
- `MAIL_HOST`
- `MAIL_PORT`
- `MAIL_USERNAME`
- `MAIL_PASSWORD`
- `MAIL_FROM_ADDRESS`
- `MAIL_FROM_NAME`

BuiltWell content policy:

- `BUILTWELL_OWNER_NAME` (used by content policy validator; owner name allowed only on `/about`)

Critical operational note:

- If `FRONTEND_URL` is wrong or missing, canonical URLs and sitemap `<loc>` values will be wrong.

## Data Model Overview

Published state is represented by:

- `pages.status = 'published'`
- `pages.published_at <= now()` or `pages.published_at IS NULL` (public visibility scope)

Core entities:

| Entity | Purpose | Key Fields | Relations |
|---|---|---|---|
| `pages` | Canonical page records | `full_path`, `template_key`, `status`, `published_at`, SEO fields | belongs to `service`, `county`, `town`; has many `sections` |
| `sections` | Ordered CMS blocks | `page_id`, `type`, `data` (JSON), `sort_order`, `is_active` | belongs to `page` |
| `services` | Service taxonomy | `name`, `slug`, `is_active`, `is_primary` | has many `pages` |
| `counties` | County taxonomy | `name`, `slug`, `phone`, `is_active` | has many `towns`, `pages` |
| `towns` | Town taxonomy | `county_id`, `name`, `slug`, `tier`, `has_hub_page` | belongs to `county`; has many `pages` |
| `redirects` | Redirect rules | `from_path`, `to_path`, `type`, `is_active` | used by middleware |
| `settings` | Key/value settings | `key`, `value` (JSON cast) | used by admin SEO settings and utility pages |

Current runtime counts (local DB snapshot):

- `pages`: 113 total
- `pages(status='published')`: 106
- `pages(status='draft')`: 7

## URL Structure and Page Types

`full_path` is stored normalized without trailing slash (except `/`). Canonical output always adds trailing slash.

Published URL patterns currently in use:

| Template | Pattern | Example |
|---|---|---|
| `home` | `/` | `/` |
| `about`, `contact`, `faq`, `portfolio`, `testimonials_page`, `consultation`, `generic` | `/{slug}` | `/about`, `/contact`, `/reviews`, `/free-consultation` |
| `service_global` | `/{service-slug}` | `/kitchen-remodeling` |
| `service_town` | `/{service-slug}/{town-slug}` | `/kitchen-remodeling/greenwich-ct` |
| `county_hub` | `/{county-slug}` and `/{county-slug}/{town-slug}` | `/fairfield-county`, `/new-haven-county/new-haven-ct` |
| `office` | fixed office path | `/new-haven-county/orange-ct` |
| `case_study` | `/case-studies/{slug}` | `/case-studies/whole-home-restoration-hamden` |

Important:

- There are draft `service_county` pages with `/{service}/{county}` style paths, but this pattern is not part of the currently published sitemap set.

## CMS: Pages and Sections

### `template_key` concept

`template_key` drives:

- Which section types are allowed
- Which section types are required
- Default section seed set for new pages
- Schema and breadcrumb behavior

Template registry source:

- `config/page-template-sections.php`
- `config/page-templates.php`

### Allowed section types

Global registry source:

- `config/sections.php` (`sections.types`)

Current registry size:

- 53 section types

Examples from registry:

- `hero`, `hero_slider`, `rich_text`, `faq_list`, `services_grid`, `town_list`, `map_embed`, `cta_block`, `process_steps`, `testimonials`, `case_study_*`, `service_*`

Template-specific enforcement:

- `service_global` allows 25 section types, requires `service_hero`
- `service_town` allows 19 section types
- `county_hub` requires `hero` and `town_list`
- `office` requires `hero` and `map_embed`
- `faq` requires `hero` and `faq_list`

### Section ordering and storage

- Sections are persisted in `sections` table with `sort_order`.
- UI reordering updates `sort_order` sequence.
- Save behavior in builder replaces all sections for a page inside a DB transaction.
- Only `is_active = true` sections are returned in public API responses.

### Save-time validation and policy rules

On section save:

- Unknown section types are rejected.
- Required section types must be present and active.
- LOCKED CTA copy is validated (`SectionValidator`).
- LOCKED content policies are validated (`ContentPolicyValidator`).

## SEO System

SEO fields on `pages`:

- `seo_title`
- `seo_description`
- `canonical_url` (stored)

Canonical generation:

- API response canonical is computed by `CanonicalResolver` using `FRONTEND_URL + full_path`.
- Trailing slash is always enforced in canonical output.

Sitemap:

- Public endpoint: `GET /sitemap.xml`
- Source: published + public-visible pages (`status='published'` and `published_at` rule)
- Generator: `App\Services\Seo\SitemapGenerator`
- Cache key: `seo:sitemap.xml` (6h route cache)

Robots:

- Public endpoint: `GET /robots.txt`
- Route emits crawler rules and sitemap reference to `FRONTEND_URL/sitemap.xml`

LLMs:

- Public endpoint: `GET /llms.txt`
- Generates a machine-readable site summary text

Operational caveat:

- `public/robots.txt` exists. Depending on server config, static file serving may override dynamic route behavior for `/robots.txt`.

## Structured Data (JSON-LD)

Builder: `App\Services\SchemaBuilder`

Always emitted:

- `BreadcrumbList` for all pages

Primary schema by page:

| Page Condition | Schema Type |
|---|---|
| `template_key = home` | `Organization` |
| path `/new-haven-county/orange-ct/` | `HomeAndConstructionBusiness` |
| `template_key = faq` | `FAQPage` |
| `template_key in (service_global, service_county, service_town)` | `Service` |

Restrictions enforced in observer:

- `FAQPage` only on `/faq` paths
- `HomeAndConstructionBusiness` only on `/new-haven-county/orange-ct/`
- `Service` only for service templates
- `Place` only for location templates

## Phone, Address, and Footer Rules (LOCKED)

Phone resolver (`PhoneResolver`):

- Fairfield County pages: `(203) 919-9616` only
- New Haven County pages: `(203) 466-9148` only
- Global pages: both numbers

Footer resolver (`FooterTemplateResolver`):

- Template `A`: global pages (no county context)
- Template `B`: Fairfield county pages
- Template `C`: New Haven county pages
- Template `D`: office page only (`/new-haven-county/orange-ct/`) with full address/hours/phones

Address visibility:

- Full office address is resolved only for office page footer template `D`

Additional LOCKED content rules:

- CTA labels allowed: `Schedule a Free Consultation` or `Get a Free Estimate`
- CTA subtext lock: `On-site or remote (Google Meet or Zoom)`
- Owner name allowed only on `/about`
- Forbidden phrase blocked: `Stamford Service Area Team`

## Redirects

Creation and updates:

- Admin redirects are created/edited via Livewire (`Admin\Redirects\Create/Edit`).
- Paths are normalized (`RedirectPathNormalizer`).
- Loop prevention validates reverse redirects and redirect chains.
- Page path change auto-creates redirect from old path to new path (`PageObserver`), unless loop would be introduced.

Resolution:

- Middleware: `ResolvePageRedirect`
- Applied to route: `GET /api/pages/{path?}`
- Active redirects are resolved with chain safety checks.

API behavior:

- Returns JSON payload with redirect metadata and `Location` header.
- Status code is redirect type (`301` or `302`).

Example:

```json
{
  "redirect": true,
  "status": 301,
  "location": "/careers"
}
```

Browser behavior:

- Current binding is API route middleware, so `/api/pages/*` responses are JSON redirect responses.
- Middleware contains fallback for non-API requests: HTTP redirect to `FRONTEND_URL + targetPath`.

## Admin Panel Usage

Access control:

- Admin routes require authentication and role middleware.
- Allowed global roles: `super_admin`, `editor`, `seo_manager`.
- SEO/redirect sections are restricted to `seo_manager|super_admin`.
- System/Ops screens are restricted to `super_admin`.

Pages and sections workflow:

1. Create page in `Admin > Pages > Create`.
2. Set `full_path`, `template_key`, status, and optional service/county/town context.
3. Open page editor.
4. Use `Sections` tab to compose ordered blocks.
5. Use `Settings` tab for path/status/publish date/context.
6. Use `SEO` tab for title/description/canonical field values.

Company SEO settings:

- `Admin > SEO > Settings`
- Stores company-level keys in `settings` table (`site_name`, `logo_path`, phone fields, canonical base, etc.).

Redirect management:

- `Admin > Redirects`
- Create `from_path -> to_path`, set type `301/302`, activate/deactivate.

Activity log:

- `Admin > Ops > Activity`
- Shows recent changes from Spatie activity log for observed models (pages/sections, and model-level logging configuration).

## API Documentation

### Endpoints

Public page API:

- `GET /api/pages/{path?}`
- Middleware: redirect resolution
- Returns published, public-visible pages only
- Cached (`pages.cache_ttl`, default 10 minutes)

Preview API:

- `GET /api/preview/pages?path={path}`
- Middleware: signed URL required (`signed`)
- Returns draft and published pages
- Not cached

SEO utility endpoints:

- `GET /sitemap.xml`
- `GET /robots.txt`
- `GET /llms.txt`

### Response shape

Top-level keys returned by `PageResource`:

- `id`
- `slug`
- `template`
- `seo` (`title`, `description`, `canonical`)
- `phones`
- `footer`
- `breadcrumbs`
- `schema`
- `sections`

### Example response: `home`

```json
{
  "id": 2,
  "slug": "/",
  "template": "home",
  "seo": {
    "title": "BuiltWell CT - Home Remodeling in Fairfield & New Haven County",
    "description": "Professional home remodeling services across Fairfield and New Haven County.",
    "canonical": "http://localhost:3000/"
  },
  "phones": {
    "mode": "both"
  },
  "footer": {
    "template": "A"
  },
  "schema": [
    {
      "@type": "Organization"
    },
    {
      "@type": "BreadcrumbList"
    }
  ]
}
```

### Example response: `service_town`

```json
{
  "id": 90,
  "slug": "/basement-finishing/branford-ct",
  "template": "service_town",
  "seo": {
    "title": "Basement Finishing in Branford, CT | BUILTWELL",
    "canonical": "http://localhost:3000/basement-finishing/branford-ct/"
  },
  "phones": {
    "mode": "single",
    "items": [
      {
        "label": "New Haven County",
        "number": "(203) 466-9148"
      }
    ]
  },
  "footer": {
    "template": "C"
  },
  "schema": [
    {
      "@type": "Service"
    },
    {
      "@type": "BreadcrumbList"
    }
  ]
}
```

### Example response: `office`

```json
{
  "id": 28,
  "slug": "/new-haven-county/orange-ct",
  "template": "office",
  "seo": {
    "title": "BuiltWell CT Office - Orange, CT",
    "canonical": "http://localhost:3000/new-haven-county/orange-ct/"
  },
  "phones": {
    "mode": "single"
  },
  "footer": {
    "template": "D",
    "address": {
      "street": "206A Boston Post Road",
      "city": "Orange",
      "state": "CT",
      "zip": "06477"
    }
  },
  "schema": [
    {
      "@type": "HomeAndConstructionBusiness"
    },
    {
      "@type": "BreadcrumbList"
    }
  ]
}
```

## Deployment

Source: `DEPLOY.md`

Pre-deploy:

```bash
cp .env.production.example .env
```

Set production values in `.env`:

- `APP_ENV=production`
- `APP_DEBUG=false`
- Correct `APP_URL` and `FRONTEND_URL`
- Valid DB/mail credentials

Deploy commands:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate   # first deploy only
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Post-deploy cache reset (if needed):

```bash
php artisan optimize:clear
```

## Verification Checklist

Copy/paste-ready checks:

```bash
# 1) Published pages count (must be 106)
php artisan tinker --execute="echo App\Models\Page::where('status','published')->count();"

# 2) Public sitemap URL count (must be 106)
curl -s https://api.DOMAIN/sitemap.xml | grep -o "<url>" | wc -l

# 3) Canonical domain should use FRONTEND_URL
curl -s https://api.DOMAIN/api/pages | jq '.seo.canonical'

# 4) Office schema should be HomeAndConstructionBusiness
curl -s https://api.DOMAIN/api/pages/new-haven-county/orange-ct | jq '.schema[0]["@type"]'

# 5) FAQ schema should be FAQPage
curl -s https://api.DOMAIN/api/pages/faq | jq '.schema[0]["@type"]'

# 6) Service town page should have single county phone
curl -s https://api.DOMAIN/api/pages/kitchen-remodeling/greenwich-ct | jq '.phones'

# 7) Redirect API contract check
curl -si https://api.DOMAIN/api/pages/subcontractors

# 8) robots endpoint check
curl -s https://api.DOMAIN/robots.txt | head -20

# 9) llms endpoint check
curl -s https://api.DOMAIN/llms.txt | head -20

# 10) Route cache and config cache status sanity
php artisan about
```

## Troubleshooting

Canonical points to localhost:

- Cause: `FRONTEND_URL` not set correctly.
- Fix: set production frontend URL and clear config cache.

Published page not in API:

- Cause: `status != published` or `published_at` is in the future.
- Fix: set `status='published'` and valid `published_at`.

Page missing from sitemap:

- Cause: page not public-visible per `publicVisible()` scope.
- Fix: verify status/publish date and clear sitemap cache key.

`/robots.txt` content is unexpectedly minimal:

- Cause: static `public/robots.txt` may be served by web server instead of route.
- Fix: remove or align static file, or change server precedence.

Redirect not triggering:

- Cause: no active redirect match for normalized `from_path`.
- Fix: verify normalized paths (leading slash, no trailing slash) and `is_active=true`.

Section save blocked with validation error:

- Cause: LOCKED CTA/content policy validation failed.
- Fix: adjust section copy to allowed values and policy rules.

Preview API returns 403:

- Cause: invalid or missing signed URL parameters.
- Fix: regenerate signed URL and retry.

## Security Notes

Admin protection:

- Admin routes are behind `auth` middleware.
- Role-based authorization is enforced via Spatie Permission middleware.

Preview protection:

- `/api/preview/pages` requires a signed URL.
- Preview URL generation is provided via `App\Support\PreviewUrl`.

Operational security:

- Keep `APP_DEBUG=false` in production.
- Use HTTPS for all frontend/backend domains.
- Rotate secrets and mail/DB credentials per environment.

## Maintenance

### Add a new town safely

1. Create town in `Admin > Towns` with correct `county_id`, slug, tier, and active flag.
2. If it should have a hub page, set `has_hub_page` and create corresponding page.
3. Create/update service-town pages using `service_town` template with proper `service_id`, `county_id`, `town_id`.
4. Confirm phone/footer logic via API response.

### Add a new service safely

1. Create service slug and name in `Admin > Services`.
2. Create a `service_global` page for the new service.
3. If needed, create town pages (`service_town`) for selected towns.
4. Validate internal linking policy (global service pages must not link down to service-town pages).

### Add or edit a page safely

1. Create page with target `template_key`.
2. Use section builder and keep required sections active.
3. Set SEO fields and publish status.
4. Verify generated schema and canonical in API output.
5. Check redirects auto-created when changing paths.

### Keep sitemap at 106 published pages

1. Treat `106` as a release gate.
2. Before publishing new pages, decide which pages must be moved to draft if count must remain fixed.
3. Run count checks before and after release.
4. Validate `/sitemap.xml` URL count equals published count.

## Spec Compliance

As implemented on 2026-02-23:

- Published sitemap requirement is satisfied:
  - `Page::where('status','published')->count() = 106`
  - Generated sitemap URL nodes = 106

- LOCKED phone/address/footer rules are implemented in backend resolvers:
  - County-specific phone behavior via `PhoneResolver`
  - Footer templates `A/B/C/D` via `FooterTemplateResolver`
  - Full office address and hours only on `/new-haven-county/orange-ct/`

- LOCKED schema restrictions are enforced:
  - `FAQPage` only for FAQ path
  - `HomeAndConstructionBusiness` only for office page
  - `BreadcrumbList` emitted for all pages

## Docs QA Checklist

- Confirm the document references the decoupled Laravel API + Next.js renderer architecture.
- Confirm `published` logic is documented as `status='published'` plus `published_at` visibility rule.
- Confirm URL patterns reflect actual published paths (`/{service}/{town}` style for service-town pages).
- Confirm section/template behavior is tied to `config/page-template-sections.php` and `config/sections.php`.
- Confirm SEO section states canonical uses `FRONTEND_URL` and enforces trailing slash.
- Confirm schema section includes page-type mapping and restrictions for FAQ and office page.
- Confirm redirect section explains JSON redirect behavior for `/api/pages/*`.
- Confirm deployment section includes production commands from `DEPLOY.md`.
- Confirm verification checklist includes count checks for published pages and sitemap URLs.
- Confirm spec compliance section explicitly states the 106-page requirement and LOCKED footer/phone/address rules.
