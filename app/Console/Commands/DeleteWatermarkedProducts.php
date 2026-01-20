<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class DeleteWatermarkedProducts extends Command
{
    protected $signature = 'products:delete-watermarked {--force : Skip confirmation prompts} {--delete-files : Also delete the em/ folder with watermarked images}';

    protected $description = 'Delete all products that have watermarked images (em/ folder prefix)';

    public function handle()
    {
        $this->info('Finding products with watermarked images (em/ folder)...');

        $count = Product::where('image', 'like', 'em/%')->count();
        $totalProducts = Product::count();

        if ($count === 0) {
            $this->info('No products with watermarked images found.');
            return 0;
        }

        $this->warn("Found {$count} products with watermarked images out of {$totalProducts} total products.");
        $this->info("Products to KEEP (without watermarks): " . ($totalProducts - $count));

        if (!$this->option('force') && !$this->confirm('Do you want to DELETE these products permanently?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        // Delete products with watermarked images
        $this->info('Deleting products with watermarked images...');
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $deleted = 0;
        Product::where('image', 'like', 'em/%')->chunk(500, function ($products) use (&$deleted, $bar) {
            foreach ($products as $product) {
                $product->delete();
                $deleted++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        $this->info("Deleted {$deleted} products with watermarked images.");
        $this->info("Remaining products: " . Product::count());

        // Optionally delete the image files
        if ($this->option('delete-files')) {
            $this->deleteWatermarkedFiles();
        }

        return 0;
    }

    protected function deleteWatermarkedFiles()
    {
        $emPath = public_path('images/products/em');

        if (!is_dir($emPath)) {
            $this->warn('em/ folder not found.');
            return;
        }

        $files = glob($emPath . '/*');
        $fileCount = count($files);

        $this->info("Deleting {$fileCount} watermarked image files...");

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Remove the empty em/ directory
        if (is_dir($emPath)) {
            rmdir($emPath);
            $this->info('Removed em/ directory.');
        }
    }
}
