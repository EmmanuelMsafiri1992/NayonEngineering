<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('site_settings')->insert([
            ['key' => 'site_name', 'value' => 'Nayon Engineering', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_email', 'value' => 'info@nayon-engineering.co.za', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_phone', 'value' => '+27 (0) 11 824 1059', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_address', 'value' => 'Germiston, Johannesburg, South Africa', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'business_hours', 'value' => 'Mon - Fri: 8:00 AM - 5:00 PM', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'facebook_url', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'twitter_url', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'linkedin_url', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'instagram_url', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
