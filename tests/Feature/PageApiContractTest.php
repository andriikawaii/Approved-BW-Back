<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class PageApiContractTest extends TestCase
{
    use DatabaseTransactions;

    public function test_live_page_api_returns_exact_contract_shape(): void
    {
        $slug = 'test-' . Str::lower(Str::random(8));
        $fullPath = '/' . $slug;

        $page = Page::create([
            'full_path' => $fullPath,
            'template_key' => 'service-default',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'seo_title' => 'Test SEO Title',
            'seo_description' => 'Test SEO Description',
        ]);

        Section::create([
            'page_id' => $page->id,
            'type' => 'hero',
            'data' => ['headline' => 'Hello'],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/pages/' . $slug);

        $response->assertOk();

        $payload = $response->json();

        $this->assertExactKeys(
            ['id', 'slug', 'template', 'seo', 'phones', 'footer', 'breadcrumbs', 'schema', 'sections'],
            array_keys($payload)
        );

        $this->assertExactKeys(['title', 'description', 'canonical'], array_keys($payload['seo']));
        $this->assertIsString($payload['seo']['title']);
        $this->assertIsString($payload['seo']['description']);
        $this->assertIsString($payload['seo']['canonical']);
        $this->assertNotSame('', $payload['seo']['title']);
        $this->assertNotSame('', $payload['seo']['description']);
        $this->assertStringContainsString('/' . $slug . '/', $payload['seo']['canonical']);
        $this->assertIsArray($payload['sections']);
        $this->assertIsArray($payload['phones']);
        $this->assertIsArray($payload['footer']);
        $this->assertIsArray($payload['breadcrumbs']);
        $this->assertIsArray($payload['schema']);

        $section = $payload['sections'][0];
        $this->assertExactKeys(['id', 'type', 'data', 'is_active'], array_keys($section));
    }

    public function test_preview_page_api_returns_exact_contract_shape(): void
    {
        $slug = 'preview-test-' . Str::lower(Str::random(8));
        $fullPath = '/' . $slug;

        $page = Page::create([
            'full_path' => $fullPath,
            'template_key' => 'service-default',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'seo_title' => 'Preview SEO Title',
            'seo_description' => 'Preview SEO Description',
        ]);

        Section::create([
            'page_id' => $page->id,
            'type' => 'text',
            'data' => ['body' => 'Copy'],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $url = URL::signedRoute('api.pages.preview', ['path' => $slug]);
        $response = $this->getJson($url);

        $response->assertOk();

        $payload = $response->json();

        $this->assertExactKeys(
            ['id', 'slug', 'template', 'seo', 'phones', 'footer', 'breadcrumbs', 'schema', 'sections'],
            array_keys($payload)
        );

        $this->assertExactKeys(['title', 'description', 'canonical'], array_keys($payload['seo']));
        $this->assertIsString($payload['seo']['title']);
        $this->assertIsString($payload['seo']['description']);
        $this->assertIsString($payload['seo']['canonical']);
        $this->assertNotSame('', $payload['seo']['title']);
        $this->assertNotSame('', $payload['seo']['description']);
        $this->assertStringContainsString('/' . $slug . '/', $payload['seo']['canonical']);
        $this->assertIsArray($payload['sections']);
        $this->assertIsArray($payload['phones']);
        $this->assertIsArray($payload['footer']);
        $this->assertIsArray($payload['breadcrumbs']);
        $this->assertIsArray($payload['schema']);

        $section = $payload['sections'][0];
        $this->assertExactKeys(['id', 'type', 'data', 'is_active'], array_keys($section));
    }

    public function test_live_page_api_enforces_visibility_rules(): void
    {
        $futureSlug = 'future-' . Str::lower(Str::random(8));
        $draftSlug = 'draft-' . Str::lower(Str::random(8));

        Page::create([
            'full_path' => '/' . $futureSlug,
            'template_key' => 'service-default',
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);

        Page::create([
            'full_path' => '/' . $draftSlug,
            'template_key' => 'service-default',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $this->getJson('/api/pages/' . $futureSlug)->assertNotFound();
        $this->getJson('/api/pages/' . $draftSlug)->assertNotFound();
    }

    public function test_preview_page_api_returns_draft_and_is_never_cached(): void
    {
        $slug = 'draft-preview-' . Str::lower(Str::random(8));
        $fullPath = '/' . $slug;

        Page::create([
            'full_path' => $fullPath,
            'template_key' => 'service-default',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $cacheKey = 'page_api:' . md5($fullPath);
        Cache::forget($cacheKey);

        $url = URL::signedRoute('api.pages.preview', ['path' => $slug]);
        $this->getJson($url)->assertOk();

        $this->assertFalse(Cache::has($cacheKey));
    }

    private function assertExactKeys(array $expected, array $actual): void
    {
        sort($expected);
        sort($actual);

        $this->assertSame($expected, $actual);
    }
}
