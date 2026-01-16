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
        // Skip if products already exist (more than 100 products suggests already seeded)
        if (Product::count() > 100) {
            $this->command->info('Products already exist in database, skipping EM product seeding.');
            return;
        }

        $this->command->info('Starting EM product seeding...');

        // Disable foreign key checks for SQLite
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
            $sql = file_get_contents($categoriesFile);
            $statements = array_filter(explode(";\n", $sql));
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    try {
                        DB::statement($statement);
                    } catch (\Exception $e) {
                        // Skip on error, might be duplicate
                    }
                }
            }
            $this->command->info('Categories imported: ' . Category::count());
        }

        // Import products from JSON file
        $productsFile = database_path('seeders/products_export.json');
        if (file_exists($productsFile)) {
            $this->command->info('Importing products (this may take a few minutes)...');

            $products = json_decode(file_get_contents($productsFile), true);
            $total = count($products);
            $imported = 0;
            $errors = 0;

            $bar = $this->command->getOutput()->createProgressBar($total);
            $bar->start();

            // Insert in batches
            $batch = [];
            $batchSize = 100;

            foreach ($products as $product) {
                $batch[] = [
                    'sku' => $product['sku'],
                    'name' => $product['name'],
                    'slug' => $product['slug'],
                    'category_id' => $product['category_id'],
                    'subcategory' => $product['subcategory'],
                    'brand' => $product['brand'],
                    'list_price' => $product['list_price'],
                    'net_price' => $product['net_price'],
                    'discount' => $product['discount'],
                    'stock' => $product['stock'],
                    'warranty' => $product['warranty'],
                    'image' => $product['image'],
                    'description' => $product['description'],
                    'is_featured' => $product['is_featured'],
                    'is_active' => $product['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= $batchSize) {
                    try {
                        Product::insert($batch);
                        $imported += count($batch);
                    } catch (\Exception $e) {
                        $errors += count($batch);
                    }
                    $batch = [];
                    $bar->advance($batchSize);
                }
            }

            // Insert remaining
            if (!empty($batch)) {
                try {
                    Product::insert($batch);
                    $imported += count($batch);
                } catch (\Exception $e) {
                    $errors += count($batch);
                }
                $bar->advance(count($batch));
            }

            $bar->finish();

            $this->command->newLine();
            $this->command->info("Products imported: {$imported}");
            if ($errors > 0) {
                $this->command->warn("Errors: {$errors}");
            }
        } else {
            $this->command->warn('Products export file not found: ' . $productsFile);
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
}
