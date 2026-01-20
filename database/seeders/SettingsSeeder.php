<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Seed default site settings.
     */
    public function run(): void
    {
        $settings = [
            // SEO Settings
            'seo_site_title' => 'Nayon Engineering',
            'seo_tagline' => 'Industrial Spares, Supplies, Projects & Installations',
            'seo_meta_description' => 'Nayon Engineering is your trusted partner for industrial spares, electrical supplies, project management, and installations across Africa and globally.',
            'seo_meta_keywords' => 'industrial spares, electrical supplies, switchgear, engineering, Africa, Mozambique, South Africa',
            'seo_index_site' => '1',

            // Company Information
            'site_name' => 'Nayon Engineering',
            'site_email' => 'info@nayon-engineering.co.za',
            'site_phone' => '+27 (0) 11 824 1059',
            'site_address' => 'Johannesburg, South Africa',
            'business_hours' => 'Mon - Fri: 8:00 AM - 5:00 PM',

            // About Us
            'about_intro_title' => 'Nayon Engineering',
            'about_intro_text_1' => 'Nayon Engineering is your trusted partner for industrial spares, electrical supplies, project management, and installations across Africa and globally.',
            'about_intro_text_2' => 'Our comprehensive range of products includes everything from basic electrical components to advanced automation systems, ensuring that we can meet all your industrial and electrical needs under one roof.',
            'about_intro_text_3' => 'We pride ourselves on offering competitive prices, quality products from trusted brands, and exceptional customer service.',
            'about_mission_title' => 'Our Mission',
            'about_mission_text' => 'To provide quality electrical products and services that empower industries across Africa and beyond.',
            'about_vision_title' => 'Our Vision',
            'about_vision_text' => 'To be the leading supplier of industrial electrical products and services in Africa.',
            'about_values_title' => 'Our Values',
            'about_values_text' => 'Integrity, quality, innovation, and customer focus drive everything we do.',

            // Footer
            'footer_about' => 'Your trusted partner for industrial spares, electrical supplies, project management, and installations across Africa and globally.',
            'footer_copyright' => 'Nayon Engineering. All rights reserved.',
        ];

        foreach ($settings as $key => $value) {
            // Only set if not already exists (preserve admin changes)
            if (!Setting::where('key', $key)->exists()) {
                Setting::create([
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        }

        // Clear settings cache after seeding
        Setting::clearCache();
    }
}
