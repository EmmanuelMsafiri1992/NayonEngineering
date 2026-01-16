<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportEmProducts extends Command
{
    protected $signature = 'import:em-products
                            {--limit=0 : Limit number of products to import (0 = all)}
                            {--skip-images : Skip downloading images}
                            {--offset=0 : Start from this offset}';

    protected $description = 'Import products from em.co.za API';

    private $apiBaseUrl = 'https://www.em.co.za/api/items';
    private $imageBaseUrl = 'https://www.em.co.za';
    private $batchSize = 50;
    private $categoryMap = [];
    private $importedCount = 0;
    private $errorCount = 0;

    public function handle()
    {
        $this->info('Starting EM.co.za product import...');

        $limit = (int) $this->option('limit');
        $skipImages = $this->option('skip-images');
        $offset = (int) $this->option('offset');

        // Create images directory if not exists
        if (!$skipImages) {
            $imagesPath = public_path('images/products/em');
            if (!file_exists($imagesPath)) {
                mkdir($imagesPath, 0755, true);
            }
        }

        // First, get total count
        $response = Http::timeout(30)->get($this->apiBaseUrl, [
            'limit' => 1,
            'fieldset' => 'details'
        ]);

        if (!$response->successful()) {
            $this->error('Failed to connect to EM.co.za API');
            return 1;
        }

        $data = $response->json();
        $totalProducts = $data['total'] ?? 0;
        $this->info("Total products available: {$totalProducts}");

        $toImport = $limit > 0 ? min($limit, $totalProducts - $offset) : $totalProducts - $offset;
        $this->info("Will import: {$toImport} products starting from offset {$offset}");

        $bar = $this->output->createProgressBar($toImport);
        $bar->start();

        $currentOffset = $offset;
        $imported = 0;

        while ($imported < $toImport) {
            $batchLimit = min($this->batchSize, $toImport - $imported);

            try {
                $products = $this->fetchProductBatch($currentOffset, $batchLimit);

                if (empty($products)) {
                    $this->warn("\nNo more products found at offset {$currentOffset}");
                    break;
                }

                foreach ($products as $productData) {
                    try {
                        $this->importProduct($productData, $skipImages);
                        $this->importedCount++;
                    } catch (\Exception $e) {
                        $this->errorCount++;
                        $this->warn("\nError importing product: " . ($productData['itemid'] ?? 'unknown') . " - " . $e->getMessage());
                    }

                    $imported++;
                    $bar->advance();

                    if ($limit > 0 && $imported >= $limit) {
                        break 2;
                    }
                }

                $currentOffset += $batchLimit;

                // Small delay to avoid rate limiting
                usleep(100000); // 100ms

            } catch (\Exception $e) {
                $this->error("\nBatch fetch error at offset {$currentOffset}: " . $e->getMessage());
                $currentOffset += $batchLimit;
                continue;
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Import completed!");
        $this->info("Successfully imported: {$this->importedCount} products");
        $this->info("Errors: {$this->errorCount}");
        $this->info("Categories created: " . count($this->categoryMap));

        return 0;
    }

    private function fetchProductBatch(int $offset, int $limit): array
    {
        $response = Http::timeout(60)->get($this->apiBaseUrl, [
            'offset' => $offset,
            'limit' => $limit,
            'fieldset' => 'details'
        ]);

        if (!$response->successful()) {
            throw new \Exception("API request failed with status: " . $response->status());
        }

        $data = $response->json();
        return $data['items'] ?? [];
    }

    private function importProduct(array $data, bool $skipImages): void
    {
        $sku = $data['itemid'] ?? null;
        if (!$sku) {
            throw new \Exception("No SKU found in product data");
        }

        // Get or create category
        $categoryName = $this->extractCategoryName($data);
        $category = $this->getOrCreateCategory($categoryName);

        // Extract subcategory
        $subcategory = $this->extractSubcategoryName($data);

        // Get price
        $price = $this->extractPrice($data);
        $listPrice = $price * 1.15; // Add 15% markup as list price

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

        // Build description with specs
        $description = $this->buildDescription($data);

        // Get product name - try multiple fields
        $name = $data['storedisplayname2'] ??
                $data['displayname'] ??
                $data['pagetitle'] ??
                $sku;

        // Clean up name if it looks like just a SKU
        if ($name === $sku && !empty($data['storedescription'])) {
            // Try to extract a better name from description
            $descWords = explode(' ', strip_tags($data['storedescription']));
            if (count($descWords) >= 3) {
                $name = implode(' ', array_slice($descWords, 0, 6));
            }
        }

        $slug = Str::slug($name . '-' . $sku);

        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->where('sku', '!=', $sku)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Update or create product
        Product::updateOrCreate(
            ['sku' => $sku],
            [
                'name' => $name,
                'slug' => $slug,
                'category_id' => $category->id,
                'subcategory' => $subcategory,
                'brand' => $data['custitem_bb1_em_brand'] ?? $data['vendorname'] ?? $data['manufacturer'] ?? 'ElectroMechanica',
                'list_price' => $listPrice,
                'net_price' => $price,
                'discount' => $listPrice > $price ? round((($listPrice - $price) / $listPrice) * 100) : 0,
                'stock' => $stock,
                'warranty' => $data['custitem_warranty'] ?? '12 months',
                'image' => $imageName ? 'em/' . $imageName : null,
                'description' => $description,
                'is_featured' => ($data['isfeatured'] ?? false) || $stock > 10,
                'is_active' => true,
            ]
        );
    }

    private function extractCategoryName(array $data): string
    {
        // Try to get category from various fields
        $category = $data['commercecategoryname'] ??
                   $data['custitem66'] ??
                   $data['custitem_category'] ??
                   $data['itemtype'] ??
                   'General';

        // If it's a path like "Products > Metering > Panel Mount", get the main category
        if (str_contains($category, '>')) {
            $parts = explode('>', $category);
            // Get the second level (first is usually just "Products")
            $category = trim($parts[1] ?? $parts[0]);
        }

        // Clean up category name
        $category = trim($category);

        // Map common itemtype values to better category names
        $categoryMappings = [
            'InvtPart' => 'Electrical Components',
            'inventory' => 'Electrical Components',
            'NonInvtPart' => 'Accessories',
            'noninventory' => 'Accessories',
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
            if (count($parts) >= 3) {
                return trim($parts[2]);
            }
        }

        return $data['custitem_subcategory'] ?? null;
    }

    private function getOrCreateCategory(string $name): Category
    {
        $slug = Str::slug($name);

        if (isset($this->categoryMap[$slug])) {
            return $this->categoryMap[$slug];
        }

        // Category icon mapping
        $icons = [
            'metering' => 'fa-tachometer-alt',
            'power' => 'fa-bolt',
            'automation' => 'fa-cogs',
            'switchgear' => 'fa-toggle-on',
            'lighting' => 'fa-lightbulb',
            'solar' => 'fa-sun',
            'cable' => 'fa-ethernet',
            'motor' => 'fa-fan',
            'sensor' => 'fa-satellite-dish',
            'enclosure' => 'fa-box',
            'circuit' => 'fa-microchip',
            'relay' => 'fa-exchange-alt',
            'timer' => 'fa-clock',
            'transformer' => 'fa-charging-station',
            'protection' => 'fa-shield-alt',
            'control' => 'fa-sliders-h',
            'default' => 'fa-plug',
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
        // Try different price fields
        $price = $data['onlinecustomerprice_detail']['onlinecustomerprice'] ??
                $data['pricelevel1'] ??
                $data['price'] ??
                $data['baseprice'] ??
                0;

        return (float) $price;
    }

    private function downloadImage(array $data, string $sku): ?string
    {
        // Try multiple image fields
        $imageUrl = null;

        // Check itemimages_detail first
        if (!empty($data['itemimages_detail']['urls'])) {
            $urls = $data['itemimages_detail']['urls'];
            $imageUrl = $urls[0]['url'] ?? null;
        }

        // Try other image fields
        if (!$imageUrl) {
            $imageUrl = $data['custitem_tt_item_image'] ??
                       $data['storedisplaythumbnail'] ??
                       $data['thumbnail'] ??
                       $data['custitem55'] ??
                       null;
        }

        if (!$imageUrl) {
            return null;
        }

        // Make URL absolute if relative
        if (str_starts_with($imageUrl, '/')) {
            $imageUrl = $this->imageBaseUrl . $imageUrl;
        }

        // Clean filename
        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
        $filename = Str::slug($sku) . '.' . $extension;
        $localPath = public_path('images/products/em/' . $filename);

        // Skip if already downloaded
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
            // Silently fail for images
        }

        return null;
    }

    private function buildDescription(array $data): string
    {
        $parts = [];

        // Add main description - try detailed first, then brief
        if (!empty($data['storedetaileddescription'])) {
            $parts[] = strip_tags($data['storedetaileddescription']);
        } elseif (!empty($data['storedescription'])) {
            $parts[] = strip_tags($data['storedescription']);
        } elseif (!empty($data['salesdescription'])) {
            $parts[] = strip_tags($data['salesdescription']);
        }

        // Add features
        if (!empty($data['custitem_features'])) {
            $parts[] = "\n\nFeatures:\n" . strip_tags($data['custitem_features']);
        }

        // Add specifications as table
        $specs = [];
        $specFields = [
            'custitem_voltage' => 'Voltage',
            'custitem_current' => 'Current',
            'custitem_power' => 'Power',
            'custitem_frequency' => 'Frequency',
            'custitem_dimensions' => 'Dimensions',
            'custitem_weight' => 'Weight',
            'custitem_operating_temp' => 'Operating Temperature',
            'custitem_protection' => 'Protection Rating',
            'custitem_certification' => 'Certifications',
        ];

        foreach ($specFields as $field => $label) {
            if (!empty($data[$field])) {
                $specs[] = "- {$label}: {$data[$field]}";
            }
        }

        if (!empty($specs)) {
            $parts[] = "\n\nSpecifications:\n" . implode("\n", $specs);
        }

        return implode("\n", $parts) ?: 'High-quality electrical product from ElectroMechanica.';
    }
}
