<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PageApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_live_page_api_returns_exact_contract_shape(): void
    {
        $page = Page::create([
            'full_path' => '/test',
            'type' => 'service',
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

        $response = $this->getJson('/api/pages/test');

        $response->assertOk();

        $payload = $response->json();

        $this->assertExactKeys(
            ['id', 'slug', 'template', 'seo', 'sections'],
            array_keys($payload)
        );

        $this->assertExactKeys(['title', 'description'], array_keys($payload['seo']));
        $this->assertIsString($payload['seo']['title']);
        $this->assertIsString($payload['seo']['description']);
        $this->assertNotSame('', $payload['seo']['title']);
        $this->assertNotSame('', $payload['seo']['description']);
        $this->assertIsArray($payload['sections']);

        $section = $payload['sections'][0];
        $this->assertExactKeys(['id', 'type', 'data', 'is_active'], array_keys($section));
    }

    public function test_preview_page_api_returns_exact_contract_shape(): void
    {
        $page = Page::create([
            'full_path' => '/preview-test',
            'type' => 'service',
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

        $url = URL::signedRoute('api.pages.preview', ['path' => 'preview-test']);
        $response = $this->getJson($url);

        $response->assertOk();

        $payload = $response->json();

        $this->assertExactKeys(
            ['id', 'slug', 'template', 'seo', 'sections'],
            array_keys($payload)
        );

        $this->assertExactKeys(['title', 'description'], array_keys($payload['seo']));
        $this->assertIsString($payload['seo']['title']);
        $this->assertIsString($payload['seo']['description']);
        $this->assertNotSame('', $payload['seo']['title']);
        $this->assertNotSame('', $payload['seo']['description']);
        $this->assertIsArray($payload['sections']);

        $section = $payload['sections'][0];
        $this->assertExactKeys(['id', 'type', 'data', 'is_active'], array_keys($section));
    }

    public function test_live_page_api_enforces_visibility_rules(): void
    {
        Page::create([
            'full_path' => '/future',
            'type' => 'service',
            'template_key' => 'service-default',
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);

        Page::create([
            'full_path' => '/draft',
            'type' => 'service',
            'template_key' => 'service-default',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $this->getJson('/api/pages/future')->assertNotFound();
        $this->getJson('/api/pages/draft')->assertNotFound();
    }

    public function test_preview_page_api_returns_draft_and_is_never_cached(): void
    {
        Page::create([
            'full_path' => '/draft-preview',
            'type' => 'service',
            'template_key' => 'service-default',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $cacheKey = 'page_api:' . md5('/draft-preview');
        Cache::forget($cacheKey);

        $url = URL::signedRoute('api.pages.preview', ['path' => 'draft-preview']);
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
