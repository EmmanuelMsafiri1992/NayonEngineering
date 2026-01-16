<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('template')->default('default');
            $table->string('featured_image')->nullable();

            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_image')->nullable();

            // Page settings
            $table->boolean('is_published')->default(false);
            $table->boolean('show_in_header')->default(false);
            $table->boolean('show_in_footer')->default(false);
            $table->boolean('is_homepage')->default(false);
            $table->integer('sort_order')->default(0);

            // Layout options
            $table->boolean('show_header')->default(true);
            $table->boolean('show_footer')->default(true);
            $table->boolean('show_breadcrumbs')->default(true);
            $table->string('layout_width')->default('container'); // container, full-width

            // Custom CSS/JS for this page
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();

            $table->timestamps();

            $table->index('is_published');
            $table->index('template');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
