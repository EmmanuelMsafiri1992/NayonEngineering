<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportAcdcProducts extends Command
{
    protected $signature = 'import:acdc-products
                            {--limit=0 : Limit number of products to import (0 = all)}
                            {--skip-images : Skip downloading images}
                            {--category= : Import only specific category ID}
                            {--start-page=1 : Start from this page number}';

    protected $description = 'Import products from acdc.co.za website';

    private $baseUrl = 'https://acdc.co.za';
    private $imageBaseUrl = 'https://a365.acdc.co.za/Images/acdc-web-images';
    private $categoryMap = [];
    private $importedCount = 0;
    private $errorCount = 0;
    private $skippedCount = 0;

    // Main category IDs and slugs from ACDC website
    private $mainCategories = [
        4191 => ['name' => 'Audio & Visual Alarms', 'slug' => 'audio-visual-alarms'],
        4182 => ['name' => 'Automation Products', 'slug' => 'automation-products'],
        4185 => ['name' => 'Automotive', 'slug' => 'automotive'],
        4181 => ['name' => 'Capacitors & PFC', 'slug' => 'capacitors-pfc'],
        4190 => ['name' => 'Circuit Breakers, Fuses & Switchgear', 'slug' => 'circuit-breakers-fuses-switchgear'],
        4171 => ['name' => 'Crane & Vehicle Controls', 'slug' => 'crane-vehicle-controls'],
        4167 => ['name' => 'Digital Video Systems', 'slug' => 'digital-video-systems'],
        4184 => ['name' => 'Electronics', 'slug' => 'electronics'],
        4169 => ['name' => 'Enclosures & Fittings', 'slug' => 'enclosures-fittings'],
        4170 => ['name' => 'Fans, Bug Killers & Hygiene', 'slug' => 'fans-bug-killers-hygiene'],
        4187 => ['name' => 'Gewiss', 'slug' => 'gewiss'],
        4174 => ['name' => 'Hazardous Areas and Mining', 'slug' => 'hazardous-areas-and-mining'],
        4179 => ['name' => 'Instruments & Telemetry', 'slug' => 'instruments-telemetry'],
        4178 => ['name' => 'Level Control and Pumps', 'slug' => 'level-control-and-pumps'],
        4173 => ['name' => 'Lighting', 'slug' => 'lighting'],
        4180 => ['name' => 'Motors & Drives', 'slug' => 'motors-drives'],
        4183 => ['name' => 'Plugs, Sockets & Adaptors', 'slug' => 'plugs-sockets-adaptors'],
        4186 => ['name' => 'Sensors & Limit Switches', 'slug' => 'sensors-limit-switches'],
        4176 => ['name' => 'Solar & Inverters', 'slug' => 'solar-inverters'],
        4172 => ['name' => 'Test & Measurement', 'slug' => 'test-measurement'],
        4177 => ['name' => 'Tools & Accessories', 'slug' => 'tools-accessories'],
        4175 => ['name' => 'Wiring & Installation', 'slug' => 'wiring-installation'],
    ];

    public function handle()
    {
        $this->info('Starting ACDC.co.za product import...');

        $limit = (int) $this->option('limit');
        $skipImages = $this->option('skip-images');
        $specificCategory = $this->option('category');
        $startPage = (int) $this->option('start-page');

        // Create images directory
        if (!$skipImages) {
            $imagesPath = public_path('images/products/acdc');
            if (!file_exists($imagesPath)) {
                mkdir($imagesPath, 0755, true);
            }
        }

        $categories = $this->mainCategories;

        if ($specificCategory) {
            if (isset($this->mainCategories[$specificCategory])) {
                $categories = [$specificCategory => $this->mainCategories[$specificCategory]];
            } else {
                $this->error("Category ID {$specificCategory} not found");
                return 1;
            }
        }

        $totalProducts = 0;

        foreach ($categories as $categoryId => $categoryData) {
            $categoryName = $categoryData['name'];
            $categorySlug = $categoryData['slug'];

            $this->info("\n\nProcessing category: {$categoryName} (ID: {$categoryId})");

            $page = $startPage;
            $hasMore = true;
            $categoryProducts = 0;

            while ($hasMore) {
                $this->info("  Fetching page {$page}...");

                try {
                    $products = $this->fetchCategoryPage($categoryId, $categorySlug, $page);

                    if (empty($products)) {
                        $hasMore = false;
                        continue;
                    }

                    foreach ($products as $productData) {
                        if ($limit > 0 && $totalProducts >= $limit) {
                            $this->info("\nReached limit of {$limit} products");
                            break 3;
                        }

                        try {
                            $this->importProduct($productData, $categoryName, $skipImages);
                            $this->importedCount++;
                            $categoryProducts++;
                            $totalProducts++;

                            if ($totalProducts % 10 === 0) {
                                $this->info("    Imported {$totalProducts} products...");
                            }
                        } catch (\Exception $e) {
                            $this->errorCount++;
                            $this->warn("    Error: " . $e->getMessage());
                        }

                        // Minimal delay
                        usleep(50000); // 50ms
                    }

                    $page++;

                    // Stop if we got less than expected (usually 16 per page)
                    if (count($products) < 12) {
                        $hasMore = false;
                    }

                    // Small delay between pages
                    usleep(200000); // 200ms

                } catch (\Exception $e) {
                    $this->error("  Error fetching page {$page}: " . $e->getMessage());
                    $hasMore = false;
                }
            }

            $this->info("  Category complete: {$categoryProducts} products imported");

            // Reset start page for subsequent categories
            $startPage = 1;
        }

        $this->newLine(2);
        $this->info("Import completed!");
        $this->info("Successfully imported: {$this->importedCount} products");
        $this->info("Skipped (existing): {$this->skippedCount}");
        $this->info("Errors: {$this->errorCount}");

        return 0;
    }

    private function fetchCategoryPage(int $categoryId, string $categorySlug, int $page): array
    {
        $url = "{$this->baseUrl}/{$categoryId}-{$categorySlug}?page={$page}";

        $response = Http::timeout(60)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'text/html,application/xhtml+xml',
            ])
            ->get($url);

        if (!$response->successful()) {
            throw new \Exception("HTTP {$response->status()}");
        }

        $html = $response->body();
        return $this->parseProductList($html);
    }

    private function parseProductList(string $html): array
    {
        $products = [];

        // Match product links with pattern: /category-slug/ID-product-slug.html
        preg_match_all(
            '/<a[^>]+href="(https?:\/\/acdc\.co\.za\/[^"]+\/(\d+)-[^"]+\.html)"[^>]*>/i',
            $html,
            $linkMatches
        );

        $seenIds = [];
        foreach ($linkMatches[2] as $index => $productId) {
            if (isset($seenIds[$productId])) continue;
            $seenIds[$productId] = true;

            $products[] = [
                'url' => $linkMatches[1][$index],
                'id' => $productId,
            ];
        }

        return $products;
    }

    private function importProduct(array $productData, string $categoryName, bool $skipImages): void
    {
        $url = $productData['url'];
        $productId = $productData['id'];

        // Fetch product page
        $response = Http::timeout(30)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ])
            ->get($url);

        if (!$response->successful()) {
            throw new \Exception("Failed to fetch product page");
        }

        $html = $response->body();
        $details = $this->parseProductDetails($html, $productId);

        if (empty($details['name']) || empty($details['sku'])) {
            throw new \Exception("Could not parse product details");
        }

        // Check if product already exists
        $existing = Product::where('sku', $details['sku'])->first();
        if ($existing) {
            $this->skippedCount++;
            return;
        }

        // Get or create category
        $category = $this->getOrCreateCategory($categoryName);

        // Download image
        $imageName = null;
        if (!$skipImages && !empty($details['image_url'])) {
            $imageName = $this->downloadImage($details['image_url'], $details['sku']);
        }

        // Generate slug
        $slug = Str::slug($details['name'] . '-' . $details['sku']);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->where('sku', '!=', $details['sku'])->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Calculate prices (add 15% markup for list price)
        $netPrice = $details['price'] ?? 0;
        $listPrice = $netPrice * 1.15;

        Product::create([
            'sku' => $details['sku'],
            'name' => $details['name'],
            'slug' => $slug,
            'category_id' => $category->id,
            'subcategory' => $details['subcategory'] ?? null,
            'brand' => $details['brand'] ?? 'ACDC',
            'list_price' => $listPrice,
            'net_price' => $netPrice,
            'discount' => $listPrice > $netPrice ? round((($listPrice - $netPrice) / $listPrice) * 100) : 0,
            'stock' => $details['in_stock'] ? 100 : 0,
            'warranty' => '12 months',
            'image' => $imageName ? 'acdc/' . $imageName : null,
            'description' => $details['description'] ?? '',
            'is_featured' => false,
            'is_active' => true,
        ]);
    }

    private function parseProductDetails(string $html, string $productId): array
    {
        $details = [
            'id' => $productId,
            'name' => null,
            'sku' => null,
            'price' => 0,
            'description' => '',
            'brand' => 'ACDC',
            'image_url' => null,
            'in_stock' => false,
            'subcategory' => null,
        ];

        // Extract product name from h1 or title
        if (preg_match('/<h1[^>]*itemprop="name"[^>]*>([^<]+)</i', $html, $match)) {
            $details['name'] = trim(html_entity_decode($match[1]));
        } elseif (preg_match('/<h1[^>]*class="[^"]*product[^"]*"[^>]*>([^<]+)</i', $html, $match)) {
            $details['name'] = trim(html_entity_decode($match[1]));
        } elseif (preg_match('/<title>([^<|]+)/i', $html, $match)) {
            $details['name'] = trim(html_entity_decode($match[1]));
        }

        // Extract SKU/Reference
        if (preg_match('/itemprop="sku"[^>]*content="([^"]+)"/i', $html, $match)) {
            $details['sku'] = trim($match[1]);
        } elseif (preg_match('/Reference[:\s]*<[^>]*>([^<]+)</i', $html, $match)) {
            $details['sku'] = trim($match[1]);
        } elseif (preg_match('/"sku"\s*:\s*"([^"]+)"/i', $html, $match)) {
            $details['sku'] = trim($match[1]);
        } elseif (preg_match('/"mpn"\s*:\s*"([^"]+)"/i', $html, $match)) {
            $details['sku'] = trim($match[1]);
        }

        // Extract price
        if (preg_match('/itemprop="price"[^>]*content="([0-9.]+)"/i', $html, $match)) {
            $details['price'] = (float) $match[1];
        } elseif (preg_match('/"price"\s*:\s*"?([0-9.]+)"?/i', $html, $match)) {
            $details['price'] = (float) $match[1];
        } elseif (preg_match('/R\s*([0-9,]+\.[0-9]{2})/i', $html, $match)) {
            $details['price'] = (float) str_replace(',', '', $match[1]);
        }

        // Extract description
        if (preg_match('/<div[^>]*id="product-description"[^>]*>(.*?)<\/div>/is', $html, $match)) {
            $details['description'] = trim(strip_tags($match[1]));
        } elseif (preg_match('/itemprop="description"[^>]*>([^<]+)</i', $html, $match)) {
            $details['description'] = trim(html_entity_decode($match[1]));
        }

        // Extract image URL
        if (preg_match('/(https?:\/\/a365\.acdc\.co\.za\/Images\/[^"\'>\s]+\.(?:jpg|jpeg|png|gif))/i', $html, $match)) {
            $details['image_url'] = $match[1];
        } elseif (preg_match('/itemprop="image"[^>]*content="([^"]+)"/i', $html, $match)) {
            $details['image_url'] = $match[1];
        } elseif (preg_match('/"image"\s*:\s*"([^"]+)"/i', $html, $match)) {
            $details['image_url'] = $match[1];
        }

        // Check stock status
        if (preg_match('/InStock|in.stock/i', $html)) {
            $details['in_stock'] = true;
        }

        // Extract brand
        if (preg_match('/itemprop="brand"[^>]*>([^<]+)</i', $html, $match)) {
            $details['brand'] = trim($match[1]);
        } elseif (preg_match('/"brand"\s*:\s*\{[^}]*"name"\s*:\s*"([^"]+)"/i', $html, $match)) {
            $details['brand'] = trim($match[1]);
        }

        return $details;
    }

    private function getOrCreateCategory(string $name): Category
    {
        $slug = Str::slug($name);

        if (isset($this->categoryMap[$slug])) {
            return $this->categoryMap[$slug];
        }

        // Category icon mapping
        $icons = [
            'audio' => 'fa-volume-up',
            'alarm' => 'fa-bell',
            'automation' => 'fa-cogs',
            'automotive' => 'fa-car',
            'capacitor' => 'fa-bolt',
            'circuit' => 'fa-microchip',
            'breaker' => 'fa-toggle-on',
            'fuse' => 'fa-shield-alt',
            'switchgear' => 'fa-toggle-on',
            'crane' => 'fa-truck-loading',
            'video' => 'fa-video',
            'electronic' => 'fa-microchip',
            'enclosure' => 'fa-box',
            'fan' => 'fa-fan',
            'hazardous' => 'fa-exclamation-triangle',
            'mining' => 'fa-hard-hat',
            'instrument' => 'fa-tachometer-alt',
            'level' => 'fa-water',
            'pump' => 'fa-water',
            'lighting' => 'fa-lightbulb',
            'motor' => 'fa-cog',
            'drive' => 'fa-tachometer-alt',
            'plug' => 'fa-plug',
            'socket' => 'fa-plug',
            'sensor' => 'fa-satellite-dish',
            'solar' => 'fa-sun',
            'inverter' => 'fa-bolt',
            'test' => 'fa-vial',
            'tool' => 'fa-wrench',
            'wiring' => 'fa-ethernet',
            'installation' => 'fa-tools',
            'gewiss' => 'fa-industry',
        ];

        $icon = 'fa-plug';
        $lowerName = strtolower($name);
        foreach ($icons as $keyword => $iconClass) {
            if (str_contains($lowerName, $keyword)) {
                $icon = $iconClass;
                break;
            }
        }

        $category = Category::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'icon' => $icon,
                'sort_order' => Category::max('sort_order') + 1,
                'is_active' => true,
            ]
        );

        $this->categoryMap[$slug] = $category;
        return $category;
    }

    private function downloadImage(string $imageUrl, string $sku): ?string
    {
        // Clean the SKU for filename
        $cleanSku = preg_replace('/[^a-zA-Z0-9_-]/', '_', $sku);
        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
        $filename = $cleanSku . '.' . strtolower($extension);
        $localPath = public_path('images/products/acdc/' . $filename);

        // Skip if already exists
        if (file_exists($localPath)) {
            return $filename;
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->get($imageUrl);

            if ($response->successful()) {
                $imageData = $response->body();

                // Basic check: skip very small images (likely placeholders) or images with watermark indicators
                $imageSize = strlen($imageData);
                if ($imageSize < 1000) {
                    return null; // Too small, probably placeholder
                }

                // Check for watermark in image (basic check - if image is very large, might have watermark overlay)
                // Most clean product images from CDN are under 500KB
                if ($imageSize > 2000000) {
                    // Very large file, might have high-res watermark
                    // Still save it but could flag for review
                }

                file_put_contents($localPath, $imageData);
                return $filename;
            }
        } catch (\Exception $e) {
            // Silent fail for images
        }

        return null;
    }
}
