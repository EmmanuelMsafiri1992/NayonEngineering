<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateImagePaths extends Command
{
    protected $signature = 'products:update-images';

    protected $description = 'Update product image paths to match downloaded images in em/ folder';

    public function handle()
    {
        $this->info('Scanning for downloaded images...');

        $imagesPath = public_path('images/products/em');

        if (!is_dir($imagesPath)) {
            $this->error('Images directory not found: ' . $imagesPath);
            return 1;
        }

        // Get all image files
        $imageFiles = glob($imagesPath . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
        $this->info('Found ' . count($imageFiles) . ' images in em/ folder');

        // Build a map of SKU -> filename
        $imageMap = [];
        foreach ($imageFiles as $file) {
            $filename = basename($file);
            $sku = pathinfo($filename, PATHINFO_FILENAME);
            $imageMap[strtolower($sku)] = 'em/' . $filename;
        }

        // Update products using chunking to avoid memory issues
        $updated = 0;
        $total = Product::count();
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        Product::chunk(500, function ($products) use ($imageMap, &$updated, $bar) {
            foreach ($products as $product) {
                // Try direct match first
                $sku = strtolower($product->sku);

                // Also try slugified version for special characters
                $skuSlug = strtolower(Str::slug($product->sku));

                if (isset($imageMap[$sku])) {
                    $product->image = $imageMap[$sku];
                    $product->save();
                    $updated++;
                } elseif (isset($imageMap[$skuSlug])) {
                    $product->image = $imageMap[$skuSlug];
                    $product->save();
                    $updated++;
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        $this->info("Updated {$updated} products with image paths");
        $this->info('Total products: ' . Product::count());

        return 0;
    }
}
