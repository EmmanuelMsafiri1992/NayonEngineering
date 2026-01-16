<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->string('type'); // hero, text, image, gallery, cta, features, testimonials, faq, contact, custom
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->json('settings')->nullable(); // Flexible settings per section type
            $table->string('background_color')->nullable();
            $table->string('background_image')->nullable();
            $table->string('text_color')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['page_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
