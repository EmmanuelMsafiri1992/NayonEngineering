<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmProductSeeder extends Seeder
{
    public function run(): void
    {
        // Skip only if EM products already exist
        $emProductCount = Product::where('brand', 'like', '%Electro%')
            ->orWhere('brand', 'like', '%EM%')
            ->orWhere('brand', 'like', '%Finder%')
            ->orWhere('brand', 'like', '%Lovato%')
            ->orWhere('brand', 'like', '%Hager%')
            ->orWhere('brand', 'like', '%Socomec%')
            ->count();

        if ($emProductCount > 1000) {
            $this->command->info("EM products already exist ({$emProductCount} found), skipping seeding.");
            return;
        }

        $this->command->info("Found {$emProductCount} existing EM products, proceeding with import...");
        $this->command->info('Starting EM product seeding...');

        // Disable foreign key checks
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // Import categories from SQL file
        $categoriesFile = database_path('seeders/categories.sql');
        if (file_exists($categoriesFile)) {
            $this->command->info('Importing categories...');
            $this->executeSqlFile($categoriesFile);
            $this->command->info('Categories imported: ' . Category::count());
        }

        // Import products from SQL file
        $productsFile = database_path('seeders/products.sql');
        if (file_exists($productsFile)) {
            $this->command->info('Importing products (this may take a few minutes)...');
            $imported = $this->executeSqlFile($productsFile);
            $this->command->info("Products SQL statements executed: {$imported}");
        } else {
            $this->command->warn('Products SQL file not found: ' . $productsFile);
        }

        // Re-enable foreign key checks
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('EM product seeding completed!');
        $this->command->info('Total products in database: ' . Product::count());
        $this->command->info('Total categories in database: ' . Category::count());
    }

    private function executeSqlFile(string $filePath): int
    {
        $count = 0;
        $handle = fopen($filePath, 'r');

        if (!$handle) {
            return 0;
        }

        $statement = '';
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if (empty($line) || strpos($line, '--') === 0) {
                continue;
            }

            $statement .= $line;

            // If statement is complete (ends with semicolon)
            if (substr($statement, -1) === ';') {
                try {
                    DB::statement($statement);
                    $count++;
                } catch (\Exception $e) {
                    // Skip errors (likely duplicates)
                }
                $statement = '';

                // Progress indicator
                if ($count % 1000 === 0) {
                    $this->command->info("Progress: {$count} statements executed...");
                }
            }
        }

        fclose($handle);
        return $count;
    }
}
