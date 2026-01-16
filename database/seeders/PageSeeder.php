<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // Create Home Page
        $homePage = Page::create([
            'title' => 'Home',
            'slug' => 'home',
            'excerpt' => 'Welcome to our website',
            'content' => '<p>Welcome to Nayon Engineering - Your trusted partner for industrial spares and electrical supplies.</p>',
            'template' => 'default',
            'is_published' => true,
            'is_homepage' => true,
            'show_in_header' => false,
            'show_in_footer' => false,
            'sort_order' => 0,
        ]);

        // Create About Page
        Page::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'excerpt' => 'Learn more about our company',
            'content' => '<p>Nayon Engineering is your trusted partner for industrial spares, electrical supplies, project management, and installations across Africa and globally.</p>',
            'template' => 'default',
            'is_published' => true,
            'show_in_header' => true,
            'show_in_footer' => true,
            'sort_order' => 1,
        ]);

        // Create Contact Page
        Page::create([
            'title' => 'Contact Us',
            'slug' => 'contact-us',
            'excerpt' => 'Get in touch with us',
            'content' => '<p>We would love to hear from you. Please fill out the form below or use our contact information.</p>',
            'template' => 'default',
            'is_published' => true,
            'show_in_header' => true,
            'show_in_footer' => true,
            'sort_order' => 2,
        ]);

        // Create Privacy Policy Page
        Page::create([
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'content' => '<h2>Privacy Policy</h2><p>This privacy policy explains how we collect, use, and protect your personal information.</p>',
            'template' => 'default',
            'is_published' => true,
            'show_in_footer' => true,
            'sort_order' => 3,
        ]);

        // Create Terms Page
        Page::create([
            'title' => 'Terms & Conditions',
            'slug' => 'terms-conditions',
            'content' => '<h2>Terms & Conditions</h2><p>Please read these terms and conditions carefully before using our website.</p>',
            'template' => 'default',
            'is_published' => true,
            'show_in_footer' => true,
            'sort_order' => 4,
        ]);

        // Create Header Menu
        $headerMenu = Menu::create([
            'name' => 'Main Navigation',
            'slug' => 'main-navigation',
            'location' => 'header',
            'is_active' => true,
        ]);

        // Add menu items
        MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Home',
            'url' => '/',
            'type' => 'custom',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Products',
            'url' => '/products',
            'type' => 'custom',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'About',
            'url' => '/about',
            'type' => 'custom',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Contact',
            'url' => '/contact',
            'type' => 'custom',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Create Footer Menu
        $footerMenu = Menu::create([
            'name' => 'Footer Navigation',
            'slug' => 'footer-navigation',
            'location' => 'footer',
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Privacy Policy',
            'url' => '/privacy-policy',
            'type' => 'custom',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Terms & Conditions',
            'url' => '/terms-and-conditions',
            'type' => 'custom',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->command->info('Default pages, menus, and menu items created successfully!');
    }
}
