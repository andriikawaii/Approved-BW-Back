<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            // URL & identity
            $table->string('full_path')->unique(); // /kitchen-remodeling/greenwich-ct/
            $table->string('type'); // home, global_service, service_town, about...
            $table->string('template_key'); // LOCKED (registry)

            // Status
            $table->string('status')->default('draft'); // draft | published
            $table->timestamp('published_at')->nullable();

            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('canonical_url')->nullable();

            // Schema
            $table->string('schema_type')->nullable();
            $table->json('schema_overrides')->nullable();

            // Content (template-aware)
            $table->json('content')->nullable();

            // Relations (optional, context driven)
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('county_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('town_id')->nullable()->constrained()->nullOnDelete();

            // MediaAsset
            $table->foreignId('hero_media_asset_id')->nullable()->constrained('media_assets')->nullOnDelete();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
