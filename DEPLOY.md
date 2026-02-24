# BuiltWell CT — Production Deploy Checklist

## Pre-deploy

1. Copy `.env.production.example` to `.env` on the server
2. Fill in ALL placeholder values (DB credentials, MAIL, APP_KEY)
3. Verify critical settings:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `FRONTEND_URL=https://builtwellct.com` (or actual domain)
   - `APP_URL=https://api.builtwellct.com` (backend domain)
   - `SESSION_ENCRYPT=true`

## Deploy commands

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate   # only on first deploy
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## Post-deploy verification

```bash
# 1. Published page count (expect 106)
php artisan tinker --execute="echo App\Models\Page::where('status','published')->count();"

# 2. Canonical uses FRONTEND_URL, not localhost
curl -s https://api.DOMAIN/api/pages/home | jq '.data.seo.canonical'
# Expected: "https://builtwellct.com/"

# 3. Home schema includes Organization
curl -s https://api.DOMAIN/api/pages/home | jq '.data.schema[0]["@type"]'
# Expected: "Organization"

# 4. Sitemap uses FRONTEND_URL
curl -s https://api.DOMAIN/sitemap.xml | head -10

# 5. Redirect returns frontend URL (not /api/pages/...)
curl -sI https://api.DOMAIN/api/pages/roofing
# Expected: Location header pointing to frontend path
```

## Cache flush (after any content changes)

```bash
php artisan tinker --execute="Illuminate\Support\Facades\Cache::flush();"
```

## SEO fixer (tinker-first)

```bash
php artisan tinker
>>> app(\App\Support\SeoFix\SeoFixRunner::class)->run(['dry_run' => true]);
>>> app(\App\Support\SeoFix\SeoFixRunner::class)->run(['dry_run' => false]);
```
