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

        // Update products
        $updated = 0;
        $products = Product::all();
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $sku = strtolower(Str::slug($product->sku));

            if (isset($imageMap[$sku])) {
                $product->image = $imageMap[$sku];
                $product->save();
                $updated++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Updated {$updated} products with image paths");
        $this->info('Total products: ' . Product::count());

        return 0;
    }
}
