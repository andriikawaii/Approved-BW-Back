<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->index();
            $table->string('phone')->nullable()->index();
            $table->string('town')->nullable();
            $table->string('zip')->nullable();
            $table->string('consultation_type')->nullable();
            $table->string('contact_method')->nullable();
            $table->string('best_time')->nullable();
            $table->json('services')->nullable();
            $table->text('message')->nullable();
            $table->string('source_page')->nullable();
            $table->string('source_page_path')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('status', 32)->default('new')->index();
            $table->timestamp('emailed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
