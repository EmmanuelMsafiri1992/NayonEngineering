<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportAcdcProductsFast extends Command
{
    protected $signature = 'import:acdc-fast
                            {--limit=5000 : Limit number of products to import}
                            {--skip-images : Skip downloading images}
                            {--category= : Import only specific category ID}';

    protected $description = 'Fast import products from acdc.co.za (extracts from listing pages)';

    private $baseUrl = 'https://acdc.co.za';
    private $imageBaseUrl = 'https://a365.acdc.co.za/Images/acdc-web-images';
    private $categoryMap = [];
    private $importedCount = 0;
    private $errorCount = 0;
    private $skippedCount = 0;

    // All category IDs including subcategories for comprehensive coverage
    private $allCategories = [
        // Audio & Visual Alarms
        4191 => ['name' => 'Audio & Visual Alarms', 'slug' => 'audio-visual-alarms'],
        4283 => ['name' => 'Beacons', 'slug' => 'beacons'],
        4550 => ['name' => 'LED Beacon', 'slug' => 'led-beacon'],
        4551 => ['name' => 'Incandescent Beacon', 'slug' => 'incandescent-beacon'],
        4552 => ['name' => 'Xenon Strobe Beacon', 'slug' => 'xenon-strobe-beacon'],
        4194 => ['name' => 'Sounders', 'slug' => 'sounders'],
        4324 => ['name' => 'Combination Sounder/Beacon', 'slug' => 'combination-sounder-beacon'],
        4262 => ['name' => 'Fire Detection', 'slug' => 'fire-detection'],
        4263 => ['name' => 'Signal Towers', 'slug' => 'signal-towers'],
        4208 => ['name' => 'Speakers and PA Systems', 'slug' => 'speakers-and-pa-systems'],

        // Automation Products
        4182 => ['name' => 'Automation Products', 'slug' => 'automation-products'],
        4224 => ['name' => 'Contactors', 'slug' => 'contactors'],
        4225 => ['name' => 'Counters', 'slug' => 'counters'],
        4227 => ['name' => 'Hour Meters', 'slug' => 'hour-meters'],
        4228 => ['name' => 'Level and Pump Control', 'slug' => 'level-and-pump-control'],
        4230 => ['name' => 'Power Monitors', 'slug' => 'power-monitors'],
        4231 => ['name' => 'Power Supplies', 'slug' => 'power-supplies'],
        4232 => ['name' => 'Process Control', 'slug' => 'process-control'],
        4233 => ['name' => 'Relays and Relay Bases', 'slug' => 'relays-and-relay-bases'],
        4236 => ['name' => 'Temperature Control', 'slug' => 'temperature-control'],
        4237 => ['name' => 'Timers and Time Switches', 'slug' => 'timers-and-time-switches'],

        // Circuit Breakers, Fuses & Switchgear
        4190 => ['name' => 'Circuit Breakers Fuses Switchgear', 'slug' => 'circuit-breakers-fuses-switchgear'],
        4265 => ['name' => 'Changeover Switches', 'slug' => 'changeover-switches'],
        4266 => ['name' => 'Circuit Breakers', 'slug' => 'circuit-breakers'],
        4267 => ['name' => 'Fuses', 'slug' => 'fuses'],
        4268 => ['name' => 'Isolators', 'slug' => 'isolators'],
        4269 => ['name' => 'Surge Protection', 'slug' => 'surge-protection'],

        // Enclosures & Fittings
        4169 => ['name' => 'Enclosures & Fittings', 'slug' => 'enclosures-fittings'],
        4299 => ['name' => 'Distribution Boards', 'slug' => 'distribution-boards'],
        4300 => ['name' => 'Electronic Enclosures', 'slug' => 'electronic-enclosures'],
        4301 => ['name' => 'Floor Standing Enclosures', 'slug' => 'floor-standing-enclosures'],
        4302 => ['name' => 'Wall Mount Enclosures', 'slug' => 'wall-mount-enclosures'],

        // Lighting
        4173 => ['name' => 'Lighting', 'slug' => 'lighting'],
        4304 => ['name' => 'LED Bulbs', 'slug' => 'led-bulbs'],
        4305 => ['name' => 'LED Downlights', 'slug' => 'led-downlights'],
        4306 => ['name' => 'LED Floodlights', 'slug' => 'led-floodlights'],
        4307 => ['name' => 'LED Panels', 'slug' => 'led-panels'],
        4308 => ['name' => 'LED Strip Lights', 'slug' => 'led-strip-lights'],
        4309 => ['name' => 'LED Tubes', 'slug' => 'led-tubes'],
        4310 => ['name' => 'Emergency Lighting', 'slug' => 'emergency-lighting'],
        4311 => ['name' => 'Outdoor Lighting', 'slug' => 'outdoor-lighting'],

        // Motors & Drives
        4180 => ['name' => 'Motors & Drives', 'slug' => 'motors-drives'],
        4313 => ['name' => 'Motor Starters', 'slug' => 'motor-starters'],
        4314 => ['name' => 'Variable Speed Drives', 'slug' => 'variable-speed-drives'],
        4315 => ['name' => 'Soft Starters', 'slug' => 'soft-starters'],

        // Plugs, Sockets & Adaptors
        4183 => ['name' => 'Plugs Sockets Adaptors', 'slug' => 'plugs-sockets-adaptors'],
        4317 => ['name' => 'Plug Tops', 'slug' => 'plug-tops'],
        4318 => ['name' => 'Wall Sockets', 'slug' => 'wall-sockets'],
        4319 => ['name' => 'Industrial Plugs', 'slug' => 'industrial-plugs'],
        4320 => ['name' => 'Adaptors', 'slug' => 'adaptors'],
        4321 => ['name' => 'Extension Leads', 'slug' => 'extension-leads'],

        // Sensors & Limit Switches
        4186 => ['name' => 'Sensors Limit Switches', 'slug' => 'sensors-limit-switches'],
        4323 => ['name' => 'Proximity Sensors', 'slug' => 'proximity-sensors'],
        4325 => ['name' => 'Photoelectric Sensors', 'slug' => 'photoelectric-sensors'],
        4327 => ['name' => 'Limit Switches', 'slug' => 'limit-switches'],

        // Solar & Inverters
        4176 => ['name' => 'Solar Inverters', 'slug' => 'solar-inverters'],
        4329 => ['name' => 'Solar Panels', 'slug' => 'solar-panels'],
        4330 => ['name' => 'Inverters', 'slug' => 'inverters'],
        4331 => ['name' => 'Batteries', 'slug' => 'batteries'],
        4332 => ['name' => 'Charge Controllers', 'slug' => 'charge-controllers'],

        // Wiring & Installation
        4175 => ['name' => 'Wiring Installation', 'slug' => 'wiring-installation'],
        4334 => ['name' => 'Cable', 'slug' => 'cable'],
        4335 => ['name' => 'Cable Ties', 'slug' => 'cable-ties'],
        4336 => ['name' => 'Conduit', 'slug' => 'conduit'],
        4337 => ['name' => 'Terminals', 'slug' => 'terminals'],
        4338 => ['name' => 'Glands', 'slug' => 'glands'],

        // Other categories
        4185 => ['name' => 'Automotive', 'slug' => 'automotive'],
        4181 => ['name' => 'Capacitors PFC', 'slug' => 'capacitors-pfc'],
        4171 => ['name' => 'Crane Vehicle Controls', 'slug' => 'crane-vehicle-controls'],
        4167 => ['name' => 'Digital Video Systems', 'slug' => 'digital-video-systems'],
        4184 => ['name' => 'Electronics', 'slug' => 'electronics'],
        4170 => ['name' => 'Fans Bug Killers Hygiene', 'slug' => 'fans-bug-killers-hygiene'],
        4187 => ['name' => 'Gewiss', 'slug' => 'gewiss'],
        4174 => ['name' => 'Hazardous Areas Mining', 'slug' => 'hazardous-areas-and-mining'],
        4179 => ['name' => 'Instruments Telemetry', 'slug' => 'instruments-telemetry'],
        4178 => ['name' => 'Level Control Pumps', 'slug' => 'level-control-and-pumps'],
        4172 => ['name' => 'Test Measurement', 'slug' => 'test-measurement'],
        4177 => ['name' => 'Tools Accessories', 'slug' => 'tools-accessories'],
    ];

    public function handle()
    {
        $this->info('Starting FAST ACDC.co.za product import...');
        $this->info('This version extracts data directly from listing pages for speed.');

        $limit = (int) $this->option('limit');
        $skipImages = $this->option('skip-images');
        $specificCategory = $this->option('category');

        // Create images directory
        if (!$skipImages) {
            $imagesPath = public_path('images/products/acdc');
            if (!file_exists($imagesPath)) {
                mkdir($imagesPath, 0755, true);
            }
        }

        $categories = $this->allCategories;

        if ($specificCategory) {
            if (isset($this->allCategories[$specificCategory])) {
                $categories = [$specificCategory => $this->allCategories[$specificCategory]];
            } else {
                $this->error("Category ID {$specificCategory} not found");
                return 1;
            }
        }

        $totalProducts = 0;
        $bar = $this->output->createProgressBar($limit);
        $bar->start();

        foreach ($categories as $categoryId => $categoryData) {
            $categoryName = $categoryData['name'];
            $categorySlug = $categoryData['slug'];

            if ($totalProducts >= $limit) break;

            $page = 1;
            $hasMore = true;
            $emptyPages = 0;

            while ($hasMore && $totalProducts < $limit) {
                try {
                    $products = $this->fetchAndParseListingPage($categoryId, $categorySlug, $page);

                    if (empty($products)) {
                        $emptyPages++;
                        if ($emptyPages >= 2) {
                            $hasMore = false;
                        }
                        $page++;
                        continue;
                    }

                    $emptyPages = 0;

                    foreach ($products as $productData) {
                        if ($totalProducts >= $limit) {
                            break 2;
                        }

                        try {
                            $imported = $this->importProductFromListing($productData, $categoryName, $skipImages);
                            if ($imported) {
                                $this->importedCount++;
                                $totalProducts++;
                                $bar->advance();
                            } else {
                                $this->skippedCount++;
                            }
                        } catch (\Exception $e) {
                            $this->errorCount++;
                        }
                    }

                    $page++;

                    // Small delay between pages
                    usleep(200000); // 200ms

                } catch (\Exception $e) {
                    $this->error("\n  Error in {$categoryName} page {$page}: " . $e->getMessage());
                    $page++;
                    $emptyPages++;
                    if ($emptyPages >= 3) {
                        $hasMore = false;
                    }
                }
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Import completed!");
        $this->info("Successfully imported: {$this->importedCount} products");
        $this->info("Skipped (existing): {$this->skippedCount}");
        $this->info("Errors: {$this->errorCount}");

        return 0;
    }

    private function fetchAndParseListingPage(int $categoryId, string $categorySlug, int $page): array
    {
        $url = "{$this->baseUrl}/{$categoryId}-{$categorySlug}?page={$page}";

        $response = Http::timeout(30)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
            ])
            ->get($url);

        if (!$response->successful()) {
            return [];
        }

        $html = $response->body();
        return $this->parseProductsFromHtml($html);
    }

    private function parseProductsFromHtml(string $html): array
    {
        $products = [];

        // Try to extract JSON-LD structured data first (most reliable)
        if (preg_match_all('/<script[^>]*type="application\/ld\+json"[^>]*>(.*?)<\/script>/is', $html, $jsonMatches)) {
            foreach ($jsonMatches[1] as $jsonStr) {
                $data = @json_decode($jsonStr, true);
                if ($data && isset($data['@type']) && $data['@type'] === 'ItemList' && isset($data['itemListElement'])) {
                    foreach ($data['itemListElement'] as $item) {
                        if (isset($item['item']) || isset($item['url'])) {
                            $itemData = $item['item'] ?? $item;
                            $product = [
                                'name' => $itemData['name'] ?? null,
                                'url' => $itemData['url'] ?? $item['url'] ?? null,
                                'sku' => $itemData['sku'] ?? null,
                                'price' => null,
                                'image_url' => $itemData['image'] ?? null,
                                'description' => $itemData['description'] ?? '',
                                'brand' => 'ACDC',
                            ];

                            // Extract price
                            if (isset($itemData['offers']['price'])) {
                                $product['price'] = (float) $itemData['offers']['price'];
                            }

                            // Extract product ID from URL
                            if ($product['url'] && preg_match('/\/(\d+)-[^\/]+\.html/', $product['url'], $idMatch)) {
                                $product['id'] = $idMatch[1];
                            }

                            if ($product['name'] && ($product['sku'] || $product['id'])) {
                                $products[] = $product;
                            }
                        }
                    }
                }
                // Also check for individual Product entries
                if ($data && isset($data['@type']) && $data['@type'] === 'Product') {
                    $product = [
                        'name' => $data['name'] ?? null,
                        'url' => $data['url'] ?? null,
                        'sku' => $data['sku'] ?? $data['mpn'] ?? null,
                        'price' => isset($data['offers']['price']) ? (float)$data['offers']['price'] : null,
                        'image_url' => $data['image'] ?? null,
                        'description' => $data['description'] ?? '',
                        'brand' => $data['brand']['name'] ?? 'ACDC',
                    ];
                    if ($product['name'] && $product['sku']) {
                        $products[] = $product;
                    }
                }
            }
        }

        // If no JSON-LD data, fallback to HTML parsing
        if (empty($products)) {
            // Parse product cards from HTML
            preg_match_all('/<article[^>]*class="[^"]*product-miniature[^"]*"[^>]*>(.*?)<\/article>/is', $html, $articleMatches);

            foreach ($articleMatches[1] as $articleHtml) {
                $product = [
                    'name' => null,
                    'url' => null,
                    'sku' => null,
                    'price' => null,
                    'image_url' => null,
                    'description' => '',
                    'brand' => 'ACDC',
                ];

                // Extract URL and name
                if (preg_match('/<a[^>]*href="([^"]+)"[^>]*class="[^"]*product-thumbnail[^"]*"[^>]*>/i', $articleHtml, $m)) {
                    $product['url'] = $m[1];
                }
                if (preg_match('/<h[123456][^>]*class="[^"]*product-title[^"]*"[^>]*>.*?<a[^>]*>([^<]+)</is', $articleHtml, $m)) {
                    $product['name'] = trim(html_entity_decode($m[1]));
                }

                // Extract image
                if (preg_match('/<img[^>]*src="([^"]+)"[^>]*class="[^"]*product[^"]*"/i', $articleHtml, $m)) {
                    $product['image_url'] = $m[1];
                } elseif (preg_match('/data-full-size-image-url="([^"]+)"/i', $articleHtml, $m)) {
                    $product['image_url'] = $m[1];
                }

                // Extract price
                if (preg_match('/itemprop="price"[^>]*content="([^"]+)"/i', $articleHtml, $m)) {
                    $product['price'] = (float) $m[1];
                } elseif (preg_match('/R\s*([\d,]+\.\d{2})/', $articleHtml, $m)) {
                    $product['price'] = (float) str_replace(',', '', $m[1]);
                }

                // Extract ID from URL
                if ($product['url'] && preg_match('/\/(\d+)-([^\/]+)\.html/', $product['url'], $m)) {
                    $product['id'] = $m[1];
                    // Use ID as SKU if no SKU found
                    if (!$product['sku']) {
                        $product['sku'] = 'ACDC-' . $m[1];
                    }
                }

                if ($product['name'] && ($product['sku'] || $product['id'])) {
                    $products[] = $product;
                }
            }
        }

        return $products;
    }

    private function importProductFromListing(array $productData, string $categoryName, bool $skipImages): bool
    {
        $sku = $productData['sku'] ?? ('ACDC-' . ($productData['id'] ?? uniqid()));
        $name = $productData['name'] ?? '';

        if (empty($name)) {
            return false;
        }

        // Check if already exists
        if (Product::where('sku', $sku)->exists()) {
            return false;
        }

        // Get or create category
        $category = $this->getOrCreateCategory($categoryName);

        // Download image if we have URL and not skipping
        $imageName = null;
        if (!$skipImages && !empty($productData['image_url'])) {
            $imageName = $this->downloadImage($productData['image_url'], $sku);
        }

        // Generate unique slug
        $slug = Str::slug($name . '-' . $sku);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Calculate prices
        $netPrice = $productData['price'] ?? 0;
        $listPrice = $netPrice > 0 ? $netPrice * 1.15 : 0;

        Product::create([
            'sku' => $sku,
            'name' => $name,
            'slug' => $slug,
            'category_id' => $category->id,
            'subcategory' => null,
            'brand' => $productData['brand'] ?? 'ACDC',
            'list_price' => $listPrice,
            'net_price' => $netPrice,
            'discount' => $listPrice > $netPrice ? round((($listPrice - $netPrice) / $listPrice) * 100) : 0,
            'stock' => 100,
            'warranty' => '12 months',
            'image' => $imageName ? 'acdc/' . $imageName : null,
            'description' => $productData['description'] ?? "Quality {$categoryName} product from ACDC Dynamics.",
            'is_featured' => false,
            'is_active' => true,
        ]);

        return true;
    }

    private function getOrCreateCategory(string $name): Category
    {
        $slug = Str::slug($name);

        if (isset($this->categoryMap[$slug])) {
            return $this->categoryMap[$slug];
        }

        $icons = [
            'audio' => 'fa-volume-up', 'alarm' => 'fa-bell', 'beacon' => 'fa-lightbulb',
            'automation' => 'fa-cogs', 'automotive' => 'fa-car', 'capacitor' => 'fa-bolt',
            'circuit' => 'fa-microchip', 'breaker' => 'fa-toggle-on', 'fuse' => 'fa-shield-alt',
            'switchgear' => 'fa-toggle-on', 'contactor' => 'fa-plug', 'relay' => 'fa-exchange-alt',
            'timer' => 'fa-clock', 'counter' => 'fa-sort-numeric-up', 'power' => 'fa-bolt',
            'enclosure' => 'fa-box', 'distribution' => 'fa-th', 'lighting' => 'fa-lightbulb',
            'led' => 'fa-lightbulb', 'flood' => 'fa-sun', 'motor' => 'fa-cog',
            'drive' => 'fa-tachometer-alt', 'plug' => 'fa-plug', 'socket' => 'fa-plug',
            'sensor' => 'fa-satellite-dish', 'solar' => 'fa-sun', 'inverter' => 'fa-bolt',
            'battery' => 'fa-battery-full', 'cable' => 'fa-ethernet', 'wiring' => 'fa-ethernet',
            'terminal' => 'fa-link', 'fan' => 'fa-fan', 'video' => 'fa-video',
            'test' => 'fa-vial', 'tool' => 'fa-wrench', 'pump' => 'fa-water',
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
        $cleanSku = preg_replace('/[^a-zA-Z0-9_-]/', '_', $sku);
        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
        $filename = $cleanSku . '.' . strtolower($extension);
        $localPath = public_path('images/products/acdc/' . $filename);

        if (file_exists($localPath)) {
            return $filename;
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->get($imageUrl);

            if ($response->successful()) {
                $imageData = $response->body();

                // Skip tiny images (placeholders)
                if (strlen($imageData) < 500) {
                    return null;
                }

                file_put_contents($localPath, $imageData);
                return $filename;
            }
        } catch (\Exception $e) {
            // Silent fail
        }

        return null;
    }
}
