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
        // Skip if pages already exist (prevent duplicate seeding)
        if (Page::count() > 0) {
            return;
        }

        // Disable foreign key checks and clear existing data (database-agnostic)
        $driver = \DB::getDriverName();
        if ($driver === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        MenuItem::truncate();
        Menu::truncate();
        Page::truncate();

        if ($driver === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Create About Us Page
        Page::create([
            'title' => 'About Us',
            'slug' => 'about',
            'excerpt' => 'Learn more about Nayon Engineering - Your trusted partner for industrial spares and electrical supplies.',
            'content' => $this->getAboutContent(),
            'template' => 'default',
            'meta_title' => 'About Us - Nayon Engineering',
            'meta_description' => 'Nayon Engineering is your trusted partner for industrial spares, electrical supplies, project management, and installations across Africa and globally.',
            'is_published' => true,
            'show_in_header' => true,
            'show_in_footer' => true,
            'sort_order' => 1,
        ]);

        // Create Contact Page
        Page::create([
            'title' => 'Contact Us',
            'slug' => 'contact',
            'excerpt' => 'Get in touch with Nayon Engineering for all your electrical and industrial needs.',
            'content' => $this->getContactContent(),
            'template' => 'default',
            'meta_title' => 'Contact Us - Nayon Engineering',
            'meta_description' => 'Contact Nayon Engineering for industrial spares, electrical supplies, and project management services.',
            'is_published' => true,
            'show_in_header' => true,
            'show_in_footer' => true,
            'sort_order' => 2,
        ]);

        // Create Services Page
        Page::create([
            'title' => 'Our Services',
            'slug' => 'services',
            'excerpt' => 'Comprehensive industrial and electrical services tailored to meet your unique needs.',
            'content' => $this->getServicesContent(),
            'template' => 'default',
            'meta_title' => 'Our Services - Nayon Engineering',
            'meta_description' => 'Nayon Engineering offers switchgear solutions, electrical supplies, project management, and installation services.',
            'is_published' => true,
            'show_in_header' => true,
            'show_in_footer' => true,
            'sort_order' => 3,
        ]);

        // Create Privacy Policy Page
        Page::create([
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'excerpt' => 'Our privacy policy explains how we collect, use, and protect your personal information.',
            'content' => $this->getPrivacyContent(),
            'template' => 'default',
            'meta_title' => 'Privacy Policy - Nayon Engineering',
            'meta_description' => 'Read our privacy policy to understand how Nayon Engineering collects and protects your personal information.',
            'is_published' => true,
            'show_in_footer' => true,
            'sort_order' => 4,
        ]);

        // Create Terms & Conditions Page
        Page::create([
            'title' => 'Terms & Conditions',
            'slug' => 'terms-and-conditions',
            'excerpt' => 'Please read our terms and conditions carefully before using our website.',
            'content' => $this->getTermsContent(),
            'template' => 'default',
            'meta_title' => 'Terms & Conditions - Nayon Engineering',
            'meta_description' => 'Read the terms and conditions for using Nayon Engineering website and services.',
            'is_published' => true,
            'show_in_footer' => true,
            'sort_order' => 5,
        ]);

        // Create FAQs Page
        Page::create([
            'title' => 'Frequently Asked Questions',
            'slug' => 'faqs',
            'excerpt' => 'Find answers to commonly asked questions about our products and services.',
            'content' => $this->getFaqsContent(),
            'template' => 'default',
            'meta_title' => 'FAQs - Nayon Engineering',
            'meta_description' => 'Find answers to frequently asked questions about Nayon Engineering products and services.',
            'is_published' => true,
            'show_in_footer' => true,
            'sort_order' => 6,
        ]);

        // Create Careers Page
        Page::create([
            'title' => 'Careers',
            'slug' => 'careers',
            'excerpt' => 'Join our team at Nayon Engineering and build your career with us.',
            'content' => $this->getCareersContent(),
            'template' => 'default',
            'meta_title' => 'Careers - Nayon Engineering',
            'meta_description' => 'Explore career opportunities at Nayon Engineering. Join our team of professionals.',
            'is_published' => true,
            'show_in_footer' => true,
            'sort_order' => 7,
        ]);

        // Create Track Order Page
        Page::create([
            'title' => 'Track Your Order',
            'slug' => 'track-order',
            'excerpt' => 'Track the status of your order.',
            'content' => '<p>Enter your order number to track its status.</p>',
            'template' => 'default',
            'meta_title' => 'Track Order - Nayon Engineering',
            'meta_description' => 'Track the status of your Nayon Engineering order.',
            'is_published' => true,
            'show_in_footer' => false,
            'sort_order' => 8,
        ]);

        // Create Header Menu
        $headerMenu = Menu::create([
            'name' => 'Main Navigation',
            'slug' => 'main-navigation',
            'location' => 'header',
            'is_active' => true,
        ]);

        MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'Home', 'url' => '/', 'type' => 'custom', 'sort_order' => 0, 'is_active' => true]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'Products', 'url' => '/products', 'type' => 'custom', 'sort_order' => 1, 'is_active' => true]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'Services', 'url' => '/services', 'type' => 'custom', 'sort_order' => 2, 'is_active' => true]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'About', 'url' => '/about', 'type' => 'custom', 'sort_order' => 3, 'is_active' => true]);
        MenuItem::create(['menu_id' => $headerMenu->id, 'title' => 'Contact', 'url' => '/contact', 'type' => 'custom', 'sort_order' => 4, 'is_active' => true]);

        // Create Footer Menu
        $footerMenu = Menu::create([
            'name' => 'Footer Navigation',
            'slug' => 'footer-navigation',
            'location' => 'footer',
            'is_active' => true,
        ]);

        MenuItem::create(['menu_id' => $footerMenu->id, 'title' => 'About Us', 'url' => '/about', 'type' => 'custom', 'sort_order' => 0, 'is_active' => true]);
        MenuItem::create(['menu_id' => $footerMenu->id, 'title' => 'Services', 'url' => '/services', 'type' => 'custom', 'sort_order' => 1, 'is_active' => true]);
        MenuItem::create(['menu_id' => $footerMenu->id, 'title' => 'FAQs', 'url' => '/faqs', 'type' => 'custom', 'sort_order' => 2, 'is_active' => true]);
        MenuItem::create(['menu_id' => $footerMenu->id, 'title' => 'Careers', 'url' => '/careers', 'type' => 'custom', 'sort_order' => 3, 'is_active' => true]);
        MenuItem::create(['menu_id' => $footerMenu->id, 'title' => 'Privacy Policy', 'url' => '/privacy-policy', 'type' => 'custom', 'sort_order' => 4, 'is_active' => true]);
        MenuItem::create(['menu_id' => $footerMenu->id, 'title' => 'Terms & Conditions', 'url' => '/terms-and-conditions', 'type' => 'custom', 'sort_order' => 5, 'is_active' => true]);

        $this->command->info('All existing pages imported successfully!');
    }

    private function getAboutContent(): string
    {
        return <<<'HTML'
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; margin-bottom: 60px;">
    <div>
        <h2 style="font-size: 32px; margin-bottom: 20px;">Welcome to <span style="color: #0079c1;">Nayon Engineering</span></h2>
        <p style="color: #a0a0a0; line-height: 1.8; margin-bottom: 20px;">
            Nayon Engineering is your trusted partner for industrial spares, electrical supplies, project management, and installations across Africa and globally.
        </p>
        <p style="color: #a0a0a0; line-height: 1.8; margin-bottom: 20px;">
            Our comprehensive range of products includes everything from basic electrical components to advanced automation systems, ensuring that we can meet all your industrial and electrical needs under one roof.
        </p>
        <p style="color: #a0a0a0; line-height: 1.8;">
            We pride ourselves on offering competitive prices, quality products from trusted brands, and exceptional customer service.
        </p>
    </div>
    <div style="background-color: #16213e; border-radius: 8px; height: 400px; display: flex; align-items: center; justify-content: center;">
        <div style="text-align: center; color: #a0a0a0;">
            <i class="fas fa-building" style="font-size: 80px; margin-bottom: 20px; color: #0079c1;"></i>
            <p>Company Image</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 60px;">
    <div style="background-color: #16213e; border-radius: 8px; padding: 40px 30px; text-align: center;">
        <div style="width: 80px; height: 80px; background-color: #0079c1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fas fa-bullseye" style="font-size: 36px; color: #fff;"></i>
        </div>
        <h3 style="margin-bottom: 15px;">Our Mission</h3>
        <p style="color: #a0a0a0; line-height: 1.7;">
            To provide quality electrical products and services that empower industries across Africa and beyond.
        </p>
    </div>
    <div style="background-color: #16213e; border-radius: 8px; padding: 40px 30px; text-align: center;">
        <div style="width: 80px; height: 80px; background-color: #0079c1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fas fa-eye" style="font-size: 36px; color: #fff;"></i>
        </div>
        <h3 style="margin-bottom: 15px;">Our Vision</h3>
        <p style="color: #a0a0a0; line-height: 1.7;">
            To be the leading supplier of industrial electrical products and services in Africa.
        </p>
    </div>
    <div style="background-color: #16213e; border-radius: 8px; padding: 40px 30px; text-align: center;">
        <div style="width: 80px; height: 80px; background-color: #0079c1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fas fa-heart" style="font-size: 36px; color: #fff;"></i>
        </div>
        <h3 style="margin-bottom: 15px;">Our Values</h3>
        <p style="color: #a0a0a0; line-height: 1.7;">
            Integrity, quality, innovation, and customer focus drive everything we do.
        </p>
    </div>
</div>

<div style="margin-bottom: 60px;">
    <h2 style="font-size: 28px; margin-bottom: 30px; text-align: center;">Industries We <span style="color: #0079c1;">Serve</span></h2>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
        <div style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 8px; padding: 30px; text-align: center; border: 1px solid #2a2a4a;">
            <i class="fas fa-bolt" style="font-size: 40px; color: #0079c1; margin-bottom: 15px;"></i>
            <h4>Power & Energy</h4>
        </div>
        <div style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 8px; padding: 30px; text-align: center; border: 1px solid #2a2a4a;">
            <i class="fas fa-tractor" style="font-size: 40px; color: #0079c1; margin-bottom: 15px;"></i>
            <h4>Agriculture</h4>
        </div>
        <div style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 8px; padding: 30px; text-align: center; border: 1px solid #2a2a4a;">
            <i class="fas fa-hard-hat" style="font-size: 40px; color: #0079c1; margin-bottom: 15px;"></i>
            <h4>Construction</h4>
        </div>
        <div style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 8px; padding: 30px; text-align: center; border: 1px solid #2a2a4a;">
            <i class="fas fa-oil-can" style="font-size: 40px; color: #0079c1; margin-bottom: 15px;"></i>
            <h4>Oil & Gas</h4>
        </div>
        <div style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 8px; padding: 30px; text-align: center; border: 1px solid #2a2a4a;">
            <i class="fas fa-industry" style="font-size: 40px; color: #0079c1; margin-bottom: 15px;"></i>
            <h4>Manufacturing</h4>
        </div>
        <div style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 8px; padding: 30px; text-align: center; border: 1px solid #2a2a4a;">
            <i class="fas fa-gem" style="font-size: 40px; color: #0079c1; margin-bottom: 15px;"></i>
            <h4>Mining</h4>
        </div>
        <div style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 8px; padding: 30px; text-align: center; border: 1px solid #2a2a4a;">
            <i class="fas fa-water" style="font-size: 40px; color: #0079c1; margin-bottom: 15px;"></i>
            <h4>Water & Utilities</h4>
        </div>
        <div style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 8px; padding: 30px; text-align: center; border: 1px solid #2a2a4a;">
            <i class="fas fa-truck" style="font-size: 40px; color: #0079c1; margin-bottom: 15px;"></i>
            <h4>Transportation</h4>
        </div>
    </div>
</div>
HTML;
    }

    private function getContactContent(): string
    {
        return <<<'HTML'
<p style="text-align: center; color: #a0a0a0; max-width: 600px; margin: 0 auto 40px;">
    Have questions or need assistance? We're here to help. Reach out to us through any of the methods below.
</p>
HTML;
    }

    private function getServicesContent(): string
    {
        return <<<'HTML'
<p style="text-align: center; color: #a0a0a0; max-width: 800px; margin: 0 auto 50px; font-size: 18px; line-height: 1.8;">
    At Nayon Engineering, we offer comprehensive industrial and electrical services tailored to meet the unique needs of various industries across Africa and globally.
</p>

<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; margin-bottom: 60px;">
    <div style="background-color: #16213e; border-radius: 8px; overflow: hidden;">
        <div style="height: 200px; background: linear-gradient(135deg, #0079c1 0%, #0079c1dd 100%); display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-bolt" style="font-size: 80px; opacity: 0.5; color: #fff;"></i>
        </div>
        <div style="padding: 30px;">
            <h3 style="font-size: 24px; margin-bottom: 15px;">Switchgear Solutions</h3>
            <p style="color: #a0a0a0; line-height: 1.8; margin-bottom: 20px;">We provide comprehensive switchgear solutions including design, installation, commissioning, and maintenance.</p>
            <ul style="color: #a0a0a0; margin-bottom: 20px; padding-left: 20px;">
                <li style="margin-bottom: 10px;">LV & MV Switchgear Installation</li>
                <li style="margin-bottom: 10px;">Panel Board Assembly</li>
                <li style="margin-bottom: 10px;">Motor Control Centers</li>
                <li style="margin-bottom: 10px;">Protection & Metering Systems</li>
            </ul>
            <a href="/contact" style="display: inline-block; padding: 10px 20px; background: #0079c1; color: #fff; text-decoration: none; border-radius: 6px;">Get a Quote</a>
        </div>
    </div>
    <div style="background-color: #16213e; border-radius: 8px; overflow: hidden;">
        <div style="height: 200px; background: linear-gradient(135deg, #f7b731 0%, #f7b731dd 100%); display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-plug" style="font-size: 80px; opacity: 0.5; color: #fff;"></i>
        </div>
        <div style="padding: 30px;">
            <h3 style="font-size: 24px; margin-bottom: 15px;">Electrical Supplies</h3>
            <p style="color: #a0a0a0; line-height: 1.8; margin-bottom: 20px;">We stock a comprehensive range of electrical products from leading manufacturers.</p>
            <ul style="color: #a0a0a0; margin-bottom: 20px; padding-left: 20px;">
                <li style="margin-bottom: 10px;">Circuit Breakers & Protection</li>
                <li style="margin-bottom: 10px;">Cables & Wiring Accessories</li>
                <li style="margin-bottom: 10px;">Lighting Solutions</li>
                <li style="margin-bottom: 10px;">Automation Products</li>
            </ul>
            <a href="/contact" style="display: inline-block; padding: 10px 20px; background: #0079c1; color: #fff; text-decoration: none; border-radius: 6px;">Get a Quote</a>
        </div>
    </div>
    <div style="background-color: #16213e; border-radius: 8px; overflow: hidden;">
        <div style="height: 200px; background: linear-gradient(135deg, #28a745 0%, #28a745dd 100%); display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-project-diagram" style="font-size: 80px; opacity: 0.5; color: #fff;"></i>
        </div>
        <div style="padding: 30px;">
            <h3 style="font-size: 24px; margin-bottom: 15px;">Project Management</h3>
            <p style="color: #a0a0a0; line-height: 1.8; margin-bottom: 20px;">Our experienced project managers oversee electrical and industrial projects from conception to completion.</p>
            <ul style="color: #a0a0a0; margin-bottom: 20px; padding-left: 20px;">
                <li style="margin-bottom: 10px;">Project Planning & Design</li>
                <li style="margin-bottom: 10px;">Resource Coordination</li>
                <li style="margin-bottom: 10px;">Quality Assurance</li>
                <li style="margin-bottom: 10px;">Documentation & Reporting</li>
            </ul>
            <a href="/contact" style="display: inline-block; padding: 10px 20px; background: #0079c1; color: #fff; text-decoration: none; border-radius: 6px;">Get a Quote</a>
        </div>
    </div>
    <div style="background-color: #16213e; border-radius: 8px; overflow: hidden;">
        <div style="height: 200px; background: linear-gradient(135deg, #6f42c1 0%, #6f42c1dd 100%); display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-tools" style="font-size: 80px; opacity: 0.5; color: #fff;"></i>
        </div>
        <div style="padding: 30px;">
            <h3 style="font-size: 24px; margin-bottom: 15px;">Installation Services</h3>
            <p style="color: #a0a0a0; line-height: 1.8; margin-bottom: 20px;">Our certified technicians provide professional installation services for all types of electrical equipment.</p>
            <ul style="color: #a0a0a0; margin-bottom: 20px; padding-left: 20px;">
                <li style="margin-bottom: 10px;">Equipment Installation</li>
                <li style="margin-bottom: 10px;">Testing & Commissioning</li>
                <li style="margin-bottom: 10px;">System Integration</li>
                <li style="margin-bottom: 10px;">Training & Handover</li>
            </ul>
            <a href="/contact" style="display: inline-block; padding: 10px 20px; background: #0079c1; color: #fff; text-decoration: none; border-radius: 6px;">Get a Quote</a>
        </div>
    </div>
</div>

<h2 style="text-align: center; margin-bottom: 40px;">Additional <span style="color: #0079c1;">Services</span></h2>
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
    <div style="background-color: #16213e; border-radius: 8px; padding: 30px; text-align: center;">
        <div style="width: 70px; height: 70px; background-color: #0079c1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fas fa-wrench" style="font-size: 30px; color: #fff;"></i>
        </div>
        <h4 style="margin-bottom: 10px;">Maintenance</h4>
    </div>
    <div style="background-color: #16213e; border-radius: 8px; padding: 30px; text-align: center;">
        <div style="width: 70px; height: 70px; background-color: #0079c1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fas fa-solar-panel" style="font-size: 30px; color: #fff;"></i>
        </div>
        <h4 style="margin-bottom: 10px;">Solar Solutions</h4>
    </div>
    <div style="background-color: #16213e; border-radius: 8px; padding: 30px; text-align: center;">
        <div style="width: 70px; height: 70px; background-color: #0079c1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fas fa-cogs" style="font-size: 30px; color: #fff;"></i>
        </div>
        <h4 style="margin-bottom: 10px;">Automation</h4>
    </div>
    <div style="background-color: #16213e; border-radius: 8px; padding: 30px; text-align: center;">
        <div style="width: 70px; height: 70px; background-color: #0079c1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fas fa-chalkboard-teacher" style="font-size: 30px; color: #fff;"></i>
        </div>
        <h4 style="margin-bottom: 10px;">Training</h4>
    </div>
</div>
HTML;
    }

    private function getPrivacyContent(): string
    {
        return <<<'HTML'
<div style="max-width: 800px; margin: 0 auto;">
    <div style="background-color: #16213e; border-radius: 8px; padding: 40px;">
        <p style="color: #a0a0a0; margin-bottom: 30px;">Last updated: January 2024</p>

        <h2 style="margin-bottom: 15px;">1. Information We Collect</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support. This may include your name, email address, phone number, and shipping address.
        </p>

        <h2 style="margin-bottom: 15px;">2. How We Use Your Information</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            We use the information we collect to process orders, communicate with you, improve our services, and send you updates about products and promotions (with your consent).
        </p>

        <h2 style="margin-bottom: 15px;">3. Information Sharing</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            We do not sell or rent your personal information to third parties. We may share your information with service providers who assist us in operating our business, such as payment processors and shipping companies.
        </p>

        <h2 style="margin-bottom: 15px;">4. Data Security</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.
        </p>

        <h2 style="margin-bottom: 15px;">5. Your Rights</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            You have the right to access, correct, or delete your personal information. You may also opt out of receiving marketing communications at any time.
        </p>

        <h2 style="margin-bottom: 15px;">6. Contact Us</h2>
        <p style="color: #a0a0a0; margin-bottom: 20px;">
            If you have any questions about this Privacy Policy, please contact us:
        </p>
        <ul style="color: #a0a0a0; padding-left: 20px;">
            <li>Email: info@nayon-engineering.co.za</li>
            <li>Phone: +27 (0) 11 824 1059</li>
            <li>Address: Germiston, Johannesburg, South Africa</li>
        </ul>
    </div>
</div>
HTML;
    }

    private function getTermsContent(): string
    {
        return <<<'HTML'
<div style="max-width: 800px; margin: 0 auto;">
    <div style="background-color: #16213e; border-radius: 8px; padding: 40px;">
        <p style="color: #a0a0a0; margin-bottom: 30px;">Last updated: January 2024</p>

        <h2 style="margin-bottom: 15px;">1. Acceptance of Terms</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            By accessing and using this website, you accept and agree to be bound by the terms and conditions of this agreement. If you do not agree to these terms, please do not use this website.
        </p>

        <h2 style="margin-bottom: 15px;">2. Products and Services</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            All products and services displayed on this website are subject to availability. We reserve the right to limit quantities and discontinue products without notice. Prices are subject to change without prior notification.
        </p>

        <h2 style="margin-bottom: 15px;">3. Orders and Payment</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            All orders are subject to acceptance and availability. We reserve the right to refuse any order. Payment must be received in full before goods are dispatched. We accept various payment methods as indicated during checkout.
        </p>

        <h2 style="margin-bottom: 15px;">4. Shipping and Delivery</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            Delivery times are estimates only and are not guaranteed. We are not responsible for delays caused by shipping carriers or circumstances beyond our control. Risk of loss passes to you upon delivery.
        </p>

        <h2 style="margin-bottom: 15px;">5. Returns and Refunds</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            Products may be returned within 14 days of receipt if unused and in original packaging. Refunds will be processed within 7-10 business days after receipt of returned items. Shipping costs are non-refundable.
        </p>

        <h2 style="margin-bottom: 15px;">6. Warranty</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            Products are covered by manufacturer warranties where applicable. We do not provide additional warranties beyond those offered by manufacturers. Warranty claims should be directed to the relevant manufacturer.
        </p>

        <h2 style="margin-bottom: 15px;">7. Limitation of Liability</h2>
        <p style="color: #a0a0a0; margin-bottom: 30px;">
            Nayon Engineering shall not be liable for any indirect, incidental, special, or consequential damages arising from the use of our products or services.
        </p>

        <h2 style="margin-bottom: 15px;">8. Contact Information</h2>
        <p style="color: #a0a0a0; margin-bottom: 20px;">
            For questions regarding these terms, please contact us:
        </p>
        <ul style="color: #a0a0a0; padding-left: 20px;">
            <li>Email: info@nayon-engineering.co.za</li>
            <li>Phone: +27 (0) 11 824 1059</li>
            <li>Address: Germiston, Johannesburg, South Africa</li>
        </ul>
    </div>
</div>
HTML;
    }

    private function getFaqsContent(): string
    {
        return <<<'HTML'
<div style="max-width: 800px; margin: 0 auto;">
    <div style="background-color: #16213e; border-radius: 8px; padding: 25px; margin-bottom: 15px;">
        <h3 style="margin-bottom: 15px; display: flex; align-items: flex-start; gap: 15px;">
            <span style="background-color: #0079c1; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">1</span>
            What products does Nayon Engineering supply?
        </h3>
        <p style="color: #a0a0a0; padding-left: 45px;">We supply a wide range of electrical products including switchgear, circuit breakers, cables, lighting solutions, solar equipment, batteries, and various electrical accessories from leading brands.</p>
    </div>

    <div style="background-color: #16213e; border-radius: 8px; padding: 25px; margin-bottom: 15px;">
        <h3 style="margin-bottom: 15px; display: flex; align-items: flex-start; gap: 15px;">
            <span style="background-color: #0079c1; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">2</span>
            Do you offer bulk/wholesale pricing?
        </h3>
        <p style="color: #a0a0a0; padding-left: 45px;">Yes, we offer competitive pricing for bulk orders and wholesale customers. Please contact our sales team for a custom quote based on your requirements.</p>
    </div>

    <div style="background-color: #16213e; border-radius: 8px; padding: 25px; margin-bottom: 15px;">
        <h3 style="margin-bottom: 15px; display: flex; align-items: flex-start; gap: 15px;">
            <span style="background-color: #0079c1; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">3</span>
            What areas do you deliver to?
        </h3>
        <p style="color: #a0a0a0; padding-left: 45px;">We deliver throughout South Africa. Delivery times vary based on location. Contact us for specific delivery information to your area.</p>
    </div>

    <div style="background-color: #16213e; border-radius: 8px; padding: 25px; margin-bottom: 15px;">
        <h3 style="margin-bottom: 15px; display: flex; align-items: flex-start; gap: 15px;">
            <span style="background-color: #0079c1; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">4</span>
            How can I track my order?
        </h3>
        <p style="color: #a0a0a0; padding-left: 45px;">Once your order is dispatched, you will receive a tracking number via email. You can use this to track your delivery through our shipping partner's website.</p>
    </div>

    <div style="background-color: #16213e; border-radius: 8px; padding: 25px; margin-bottom: 15px;">
        <h3 style="margin-bottom: 15px; display: flex; align-items: flex-start; gap: 15px;">
            <span style="background-color: #0079c1; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">5</span>
            What payment methods do you accept?
        </h3>
        <p style="color: #a0a0a0; padding-left: 45px;">We accept EFT (Electronic Fund Transfer), credit cards, and cash on collection. For large orders, we can arrange payment terms for approved business accounts.</p>
    </div>

    <div style="background-color: #16213e; border-radius: 8px; padding: 25px; margin-bottom: 15px;">
        <h3 style="margin-bottom: 15px; display: flex; align-items: flex-start; gap: 15px;">
            <span style="background-color: #0079c1; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">6</span>
            Do you provide installation services?
        </h3>
        <p style="color: #a0a0a0; padding-left: 45px;">Yes, we offer professional installation services for electrical equipment. Our qualified technicians can handle installations of various scales. Contact us for a quote.</p>
    </div>

    <div style="background-color: #16213e; border-radius: 8px; padding: 25px; margin-bottom: 15px;">
        <h3 style="margin-bottom: 15px; display: flex; align-items: flex-start; gap: 15px;">
            <span style="background-color: #0079c1; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">7</span>
            What is your return policy?
        </h3>
        <p style="color: #a0a0a0; padding-left: 45px;">Products can be returned within 14 days of purchase if unused and in original packaging. Please refer to our Terms & Conditions for full details on returns and refunds.</p>
    </div>

    <div style="background-color: #16213e; border-radius: 8px; padding: 25px; margin-bottom: 15px;">
        <h3 style="margin-bottom: 15px; display: flex; align-items: flex-start; gap: 15px;">
            <span style="background-color: #0079c1; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">8</span>
            Do products come with warranties?
        </h3>
        <p style="color: #a0a0a0; padding-left: 45px;">Yes, all products come with manufacturer warranties. Warranty periods vary by product and brand. Please check individual product pages or contact us for specific warranty information.</p>
    </div>

    <div style="text-align: center; margin-top: 40px; padding: 30px; background-color: #16213e; border-radius: 8px;">
        <h3 style="margin-bottom: 15px;">Still have questions?</h3>
        <p style="color: #a0a0a0; margin-bottom: 20px;">Our team is here to help. Contact us and we'll get back to you as soon as possible.</p>
        <a href="/contact" style="display: inline-block; padding: 12px 25px; background: #0079c1; color: #fff; text-decoration: none; border-radius: 6px;">
            <i class="fas fa-envelope"></i> Contact Us
        </a>
    </div>
</div>
HTML;
    }

    private function getCareersContent(): string
    {
        return <<<'HTML'
<div style="max-width: 800px; margin: 0 auto;">
    <div style="text-align: center; margin-bottom: 50px;">
        <i class="fas fa-users" style="font-size: 60px; color: #0079c1; margin-bottom: 20px;"></i>
        <h2 style="margin-bottom: 15px;">Join Our Team</h2>
        <p style="color: #a0a0a0; max-width: 600px; margin: 0 auto;">
            At Nayon Engineering, we're always looking for talented individuals who share our passion for excellence in electrical solutions.
        </p>
    </div>

    <div style="background-color: #16213e; border-radius: 8px; padding: 40px; margin-bottom: 30px;">
        <h3 style="margin-bottom: 25px; text-align: center;">Why Work With Us?</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 25px;">
            <div style="text-align: center;">
                <i class="fas fa-chart-line" style="font-size: 30px; color: #0079c1; margin-bottom: 15px;"></i>
                <h4 style="margin-bottom: 10px;">Growth Opportunities</h4>
                <p style="color: #a0a0a0; font-size: 14px;">Develop your career with training and advancement</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-handshake" style="font-size: 30px; color: #0079c1; margin-bottom: 15px;"></i>
                <h4 style="margin-bottom: 10px;">Great Team</h4>
                <p style="color: #a0a0a0; font-size: 14px;">Work alongside experienced professionals</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-briefcase" style="font-size: 30px; color: #0079c1; margin-bottom: 15px;"></i>
                <h4 style="margin-bottom: 10px;">Competitive Benefits</h4>
                <p style="color: #a0a0a0; font-size: 14px;">Attractive salary and benefits package</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-lightbulb" style="font-size: 30px; color: #0079c1; margin-bottom: 15px;"></i>
                <h4 style="margin-bottom: 10px;">Innovation</h4>
                <p style="color: #a0a0a0; font-size: 14px;">Work with cutting-edge electrical technology</p>
            </div>
        </div>
    </div>

    <div style="background-color: #16213e; border-radius: 8px; padding: 40px; margin-bottom: 30px;">
        <h3 style="margin-bottom: 25px; text-align: center;">Current Openings</h3>
        <div style="text-align: center; padding: 40px 20px;">
            <i class="fas fa-clipboard-list" style="font-size: 48px; color: #a0a0a0; margin-bottom: 20px;"></i>
            <p style="color: #a0a0a0; margin-bottom: 20px;">
                No open positions at the moment. Check back soon or send us your CV for future opportunities.
            </p>
        </div>
    </div>

    <div style="background-color: #0079c1; border-radius: 8px; padding: 40px; text-align: center; color: white;">
        <h3 style="margin-bottom: 15px; color: white;">Interested in Joining Us?</h3>
        <p style="margin-bottom: 25px; opacity: 0.9;">
            Send your CV to our HR department and we'll keep it on file for future opportunities.
        </p>
        <a href="mailto:careers@nayon-engineering.co.za" style="display: inline-block; padding: 12px 25px; background-color: white; color: #0079c1; text-decoration: none; border-radius: 6px;">
            <i class="fas fa-envelope"></i> Send Your CV
        </a>
        <p style="margin-top: 20px; font-size: 14px; opacity: 0.8;">
            Email: careers@nayon-engineering.co.za
        </p>
    </div>
</div>
HTML;
    }
}
