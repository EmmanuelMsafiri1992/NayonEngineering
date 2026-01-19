<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearWatermarkedImages extends Command
{
    protected $signature = 'products:clear-watermarked {--delete-files : Also delete the watermarked image files} {--force : Skip confirmation prompts}';

    protected $description = 'Clear image references for products with watermarked images (em/ folder)';

    public function handle()
    {
        $this->info('Finding products with watermarked images (em/ folder)...');

        $count = Product::where('image', 'like', 'em/%')->count();

        if ($count === 0) {
            $this->info('No products with watermarked images found.');
            return 0;
        }

        $this->warn("Found {$count} products with watermarked images.");

        if (!$this->option('force') && !$this->confirm('Do you want to clear the image references for these products?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        // Clear image references
        $this->info('Clearing image references...');
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $cleared = 0;
        Product::where('image', 'like', 'em/%')->chunk(500, function ($products) use (&$cleared, $bar) {
            foreach ($products as $product) {
                $product->image = null;
                $product->save();
                $cleared++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        $this->info("Cleared image references for {$cleared} products.");

        // Optionally delete the image files
        if ($this->option('delete-files')) {
            $this->deleteWatermarkedFiles();
        } else {
            $this->info('Image files were NOT deleted. Run with --delete-files to also remove the files.');
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

        if ($fileCount === 0) {
            $this->info('No files to delete.');
            return;
        }

        $this->warn("Found {$fileCount} files in em/ folder.");

        if (!$this->option('force') && !$this->confirm('Do you want to delete these watermarked image files?')) {
            $this->info('File deletion cancelled.');
            return;
        }

        $this->info('Deleting watermarked image files...');
        $bar = $this->output->createProgressBar($fileCount);
        $bar->start();

        $deleted = 0;
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
                $deleted++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Remove the empty em/ directory
        if (is_dir($emPath) && count(glob($emPath . '/*')) === 0) {
            rmdir($emPath);
            $this->info('Removed empty em/ directory.');
        }

        $this->info("Deleted {$deleted} watermarked image files.");
    }
}
