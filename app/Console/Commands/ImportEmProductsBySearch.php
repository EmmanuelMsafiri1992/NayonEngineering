<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportEmProductsBySearch extends Command
{
    protected $signature = 'import:em-search
                            {--skip-images : Skip downloading images}
                            {--categories-only : Only import from category list}';

    protected $description = 'Import products from em.co.za by searching categories';

    private $apiBaseUrl = 'https://www.em.co.za/api/items';
    private $imageBaseUrl = 'https://www.em.co.za';
    private $categoryMap = [];
    private $importedCount = 0;
    private $skippedCount = 0;
    private $errorCount = 0;

    // Search terms that correspond to major product categories
    private $searchTerms = [
        // Switching & Protection
        'contactor',
        'relay',
        'circuit breaker',
        'mcb',
        'mccb',
        'fuse',
        'overload',
        'switch',
        'isolator',

        // Metering & Monitoring
        'meter',
        'energy meter',
        'power analyser',
        'current transformer',
        'ammeter',
        'voltmeter',

        // Automation
        'plc',
        'hmi',
        'sensor',
        'proximity',
        'photoelectric',
        'encoder',
        'timer',
        'counter',
        'controller',

        // Motor Control
        'motor starter',
        'soft starter',
        'vfd',
        'inverter',
        'drive',

        // Power Quality
        'capacitor',
        'power factor',
        'surge',
        'ups',
        'transformer',

        // Enclosures & Wiring
        'enclosure',
        'terminal',
        'connector',
        'cable',
        'conduit',
        'din rail',

        // Lighting & Signaling
        'pilot light',
        'pushbutton',
        'indicator',
        'beacon',
        'tower light',

        // Industrial
        'socket',
        'plug',
        'panel',
        'busbar',

        // Additional categories
        'lovato',
        'finder',
        'hager',
        'socomec',
        'schneider',
        'siemens',
        'abb',
    ];

    public function handle()
    {
        $this->info('Starting EM.co.za search-based product import...');
        $this->info('This will search for products using ' . count($this->searchTerms) . ' search terms');

        $skipImages = $this->option('skip-images');
        $existingSkus = Product::pluck('sku')->flip()->toArray();

        $this->info('Existing products in database: ' . count($existingSkus));

        // Create images directory if not exists
        if (!$skipImages) {
            $imagesPath = public_path('images/products/em');
            if (!file_exists($imagesPath)) {
                mkdir($imagesPath, 0755, true);
            }
        }

        $bar = $this->output->createProgressBar(count($this->searchTerms));
        $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% | %message%");
        $bar->start();

        foreach ($this->searchTerms as $searchTerm) {
            $bar->setMessage("Searching: {$searchTerm}");

            try {
                $this->importBySearch($searchTerm, $existingSkus, $skipImages);
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->warn("\nError searching '{$searchTerm}': " . $e->getMessage());
            }

            $bar->advance();
            usleep(200000); // 200ms delay between searches
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Search import completed!");
        $this->info("New products imported: {$this->importedCount}");
        $this->info("Products already existed: {$this->skippedCount}");
        $this->info("Errors: {$this->errorCount}");
        $this->info("Total products in database: " . Product::count());

        return 0;
    }

    private function importBySearch(string $searchTerm, array &$existingSkus, bool $skipImages): void
    {
        $offset = 0;
        $limit = 50;
        $maxResults = 2000; // Limit per search term

        while ($offset < $maxResults) {
            $response = Http::timeout(60)->get($this->apiBaseUrl, [
                'q' => $searchTerm,
                'offset' => $offset,
                'limit' => $limit,
                'fieldset' => 'details'
            ]);

            if (!$response->successful()) {
                break;
            }

            $data = $response->json();
            $items = $data['items'] ?? [];

            if (empty($items)) {
                break;
            }

            foreach ($items as $productData) {
                $sku = $productData['itemid'] ?? null;
                if (!$sku) continue;

                // Skip if already imported
                if (isset($existingSkus[$sku])) {
                    $this->skippedCount++;
                    continue;
                }

                try {
                    $this->importProduct($productData, $skipImages);
                    $existingSkus[$sku] = true;
                    $this->importedCount++;
                } catch (\Exception $e) {
                    $this->errorCount++;
                }
            }

            $offset += $limit;

            // Stop if we got less than limit (no more results)
            if (count($items) < $limit) {
                break;
            }

            usleep(100000); // 100ms delay
        }
    }

    private function importProduct(array $data, bool $skipImages): void
    {
        $sku = $data['itemid'];

        // Get or create category
        $categoryName = $this->extractCategoryName($data);
        $category = $this->getOrCreateCategory($categoryName);

        // Extract subcategory
        $subcategory = $this->extractSubcategoryName($data);

        // Get price
        $price = $this->extractPrice($data);
        $listPrice = $price * 1.15;

        // Get stock
        $stock = (int) ($data['quantityavailable'] ?? 0);
        if ($stock <= 0 && ($data['isinstock'] ?? false)) {
            $stock = 1;
        }

        // Get or download image
        $imageName = null;
        if (!$skipImages) {
            $imageName = $this->downloadImage($data, $sku);
        }

        // Build description
        $description = $this->buildDescription($data);

        // Get product name
        $name = $data['storedisplayname2'] ??
                $data['displayname'] ??
                $data['pagetitle'] ??
                $sku;

        $slug = Str::slug($name . '-' . $sku);

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->where('sku', '!=', $sku)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        Product::updateOrCreate(
            ['sku' => $sku],
            [
                'name' => $name,
                'slug' => $slug,
                'category_id' => $category->id,
                'subcategory' => $subcategory,
                'brand' => $data['custitem_bb1_em_brand'] ?? $data['vendorname'] ?? 'ElectroMechanica',
                'list_price' => $listPrice,
                'net_price' => $price,
                'discount' => $listPrice > $price ? round((($listPrice - $price) / $listPrice) * 100) : 0,
                'stock' => $stock,
                'warranty' => '12 months',
                'image' => $imageName ? 'em/' . $imageName : null,
                'description' => $description,
                'is_featured' => $stock > 10,
                'is_active' => true,
            ]
        );
    }

    private function extractCategoryName(array $data): string
    {
        $category = $data['commercecategoryname'] ??
                   $data['custitem66'] ??
                   $data['custitem_category'] ??
                   'General';

        if (str_contains($category, '>')) {
            $parts = explode('>', $category);
            $category = trim($parts[1] ?? $parts[0]);
        }

        $category = trim($category);

        $categoryMappings = [
            'InvtPart' => 'Electrical Components',
            'Catalogue' => 'Catalogue Products',
            'BOM Component' => 'Components',
            'Customer Request' => 'Custom Products',
            'Marketing Collateral' => 'Marketing Materials',
        ];

        if (isset($categoryMappings[$category])) {
            $category = $categoryMappings[$category];
        }

        if (empty($category) || $category === 'Products') {
            $category = 'Electrical Equipment';
        }

        return $category;
    }

    private function extractSubcategoryName(array $data): ?string
    {
        $category = $data['commercecategoryname'] ?? '';
        if (str_contains($category, '>')) {
            $parts = explode('>', $category);
            return count($parts) >= 3 ? trim($parts[2]) : null;
        }
        return null;
    }

    private function getOrCreateCategory(string $name): Category
    {
        $slug = Str::slug($name);

        if (isset($this->categoryMap[$slug])) {
            return $this->categoryMap[$slug];
        }

        $icons = [
            'metering' => 'fa-tachometer-alt',
            'power' => 'fa-bolt',
            'automation' => 'fa-cogs',
            'switchgear' => 'fa-toggle-on',
            'switch' => 'fa-toggle-on',
            'lighting' => 'fa-lightbulb',
            'solar' => 'fa-sun',
            'cable' => 'fa-ethernet',
            'motor' => 'fa-fan',
            'sensor' => 'fa-satellite-dish',
            'enclosure' => 'fa-box',
            'circuit' => 'fa-microchip',
            'relay' => 'fa-exchange-alt',
            'timer' => 'fa-clock',
            'contactor' => 'fa-plug',
            'component' => 'fa-puzzle-piece',
            'custom' => 'fa-wrench',
            'catalogue' => 'fa-book',
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

    private function extractPrice(array $data): float
    {
        $price = $data['onlinecustomerprice_detail']['onlinecustomerprice'] ??
                $data['pricelevel1'] ??
                $data['price'] ??
                $data['baseprice'] ??
                0;

        return (float) $price;
    }

    private function downloadImage(array $data, string $sku): ?string
    {
        $imageUrl = null;

        if (!empty($data['itemimages_detail']['urls'])) {
            $imageUrl = $data['itemimages_detail']['urls'][0]['url'] ?? null;
        }

        if (!$imageUrl) {
            $imageUrl = $data['custitem_tt_item_image'] ??
                       $data['storedisplaythumbnail'] ??
                       null;
        }

        if (!$imageUrl) return null;

        if (str_starts_with($imageUrl, '/')) {
            $imageUrl = $this->imageBaseUrl . $imageUrl;
        }

        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
        $filename = Str::slug($sku) . '.' . $extension;
        $localPath = public_path('images/products/em/' . $filename);

        if (file_exists($localPath)) {
            return $filename;
        }

        try {
            $response = Http::timeout(30)->get($imageUrl);
            if ($response->successful()) {
                file_put_contents($localPath, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return null;
    }

    private function buildDescription(array $data): string
    {
        $parts = [];

        if (!empty($data['storedetaileddescription'])) {
            $parts[] = strip_tags($data['storedetaileddescription']);
        } elseif (!empty($data['storedescription'])) {
            $parts[] = strip_tags($data['storedescription']);
        }

        return implode("\n", $parts) ?: 'High-quality electrical product from ElectroMechanica.';
    }
}
