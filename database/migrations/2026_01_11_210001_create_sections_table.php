<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('page_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type');
            // hero, text, image, gallery, cta…

            $table->json('data')->nullable();
            // sav fleksibilan sadržaj (naslov, tekst, dugme, slike…)

            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
