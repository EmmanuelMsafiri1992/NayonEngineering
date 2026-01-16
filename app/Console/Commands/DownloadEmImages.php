<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DownloadEmImages extends Command
{
    protected $signature = 'em:download-images
                            {--limit=0 : Limit number of images to download (0 = all)}
                            {--batch=50 : Batch size for API requests}';

    protected $description = 'Download product images from em.co.za for imported products';

    private $apiBaseUrl = 'https://www.em.co.za/api/items';
    private $imageBaseUrl = 'https://www.em.co.za';
    private $downloadedCount = 0;
    private $skippedCount = 0;
    private $errorCount = 0;

    public function handle()
    {
        $this->info('Starting image download from em.co.za...');

        $limit = (int) $this->option('limit');
        $batchSize = (int) $this->option('batch');

        // Create images directory
        $imagesPath = public_path('images/products/em');
        if (!file_exists($imagesPath)) {
            mkdir($imagesPath, 0755, true);
        }

        // Get products that need images (no image or placeholder)
        $query = Product::whereNull('image')
            ->orWhere('image', '')
            ->orWhere('image', 'like', 'em/%');

        $total = $limit > 0 ? min($limit, $query->count()) : $query->count();
        $this->info("Products to process: {$total}");

        $bar = $this->output->createProgressBar($total);
        $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% | Downloaded: %message%");
        $bar->setMessage('0');
        $bar->start();

        $processed = 0;
        $offset = 0;

        while ($processed < $total) {
            // Fetch product details from API in batches
            $batchLimit = min($batchSize, $total - $processed);

            try {
                $response = Http::timeout(60)->get($this->apiBaseUrl, [
                    'offset' => $offset,
                    'limit' => $batchLimit,
                    'fieldset' => 'details'
                ]);

                if (!$response->successful()) {
                    $offset += $batchLimit;
                    $processed += $batchLimit;
                    $bar->advance($batchLimit);
                    continue;
                }

                $items = $response->json()['items'] ?? [];

                foreach ($items as $item) {
                    $sku = $item['itemid'] ?? null;
                    if (!$sku) continue;

                    // Find product in database
                    $product = Product::where('sku', $sku)->first();
                    if (!$product) continue;

                    // Try to download image
                    $imageName = $this->downloadImage($item, $sku);

                    if ($imageName) {
                        $product->image = 'em/' . $imageName;
                        $product->save();
                        $this->downloadedCount++;
                    } else {
                        $this->skippedCount++;
                    }

                    $processed++;
                    $bar->setMessage((string) $this->downloadedCount);
                    $bar->advance();

                    if ($limit > 0 && $processed >= $limit) {
                        break 2;
                    }
                }

                $offset += $batchLimit;
                usleep(200000); // 200ms delay between batches

            } catch (\Exception $e) {
                $this->errorCount++;
                $offset += $batchLimit;
                $processed += $batchLimit;
                $bar->advance($batchLimit);
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Image download completed!");
        $this->info("Downloaded: {$this->downloadedCount}");
        $this->info("Skipped (no image available): {$this->skippedCount}");
        $this->info("Errors: {$this->errorCount}");

        return 0;
    }

    private function downloadImage(array $data, string $sku): ?string
    {
        // Try multiple image fields
        $imageUrl = null;

        // Check itemimages_detail first
        if (!empty($data['itemimages_detail']['urls'])) {
            $urls = $data['itemimages_detail']['urls'];
            // Get the largest/main image
            foreach ($urls as $url) {
                if (!empty($url['url'])) {
                    $imageUrl = $url['url'];
                    break;
                }
            }
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
        $filename = Str::slug($sku) . '.' . strtolower($extension);
        $localPath = public_path('images/products/em/' . $filename);

        // Skip if already downloaded
        if (file_exists($localPath) && filesize($localPath) > 1000) {
            return $filename;
        }

        try {
            $response = Http::timeout(30)->get($imageUrl);

            if ($response->successful() && strlen($response->body()) > 1000) {
                file_put_contents($localPath, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return null;
    }
}
