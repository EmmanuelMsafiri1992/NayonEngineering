<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FetchProductImages extends Command
{
    protected $signature = 'products:fetch-images
                            {--limit=100 : Limit number of products to process}
                            {--offset=0 : Skip first N products}
                            {--brand= : Only process specific brand}
                            {--min-size=5000 : Minimum image file size in bytes}
                            {--fast : Skip slow web search, only use ACDC sources}
                            {--dry-run : Show what would be downloaded without actually downloading}';

    protected $description = 'Search and download high-quality product images from multiple sources';

    private $acdcCdnBase = 'https://a365.acdc.co.za/Images/acdc-web-images';
    private $imageDir;
    private $stats = [
        'processed' => 0,
        'found' => 0,
        'downloaded' => 0,
        'failed' => 0,
        'skipped' => 0,
    ];
    private $fastMode = false;

    // Known image folder patterns on ACDC CDN
    private $acdcFolders = ['ima1', 'S', 'images', 'Products', 'product-images'];

    // Known version suffixes used by ACDC
    private $versionSuffixes = ['', '_version-2', '-ver2', '-ver-2', '_ver-2', '-NEW2', '-Ver9', '-ver8'];

    public function handle()
    {
        $limit = (int) $this->option('limit');
        $offset = (int) $this->option('offset');
        $brand = $this->option('brand');
        $minSize = (int) $this->option('min-size');
        $dryRun = $this->option('dry-run');
        $fastMode = $this->option('fast');

        $this->imageDir = public_path('images/products/acdc');
        $this->fastMode = $fastMode;

        if (!file_exists($this->imageDir)) {
            mkdir($this->imageDir, 0755, true);
        }

        $this->info('Fetching product images from multiple sources...');
        $this->info("Settings: limit={$limit}, offset={$offset}, min-size={$minSize}");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No files will be downloaded');
        }
        $this->newLine();

        // Query products without images
        $query = Product::whereNull('image')
            ->orWhere('image', '')
            ->orderBy('id');

        if ($brand) {
            $query->where('brand', 'like', "%{$brand}%");
        }

        $products = $query->skip($offset)->take($limit)->get();

        $this->info("Found {$products->count()} products without images to process");
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $this->stats['processed']++;

            $imageUrl = $this->findImageForProduct($product);

            if ($imageUrl) {
                $this->stats['found']++;

                if (!$dryRun) {
                    $result = $this->downloadImage($product, $imageUrl, $minSize);
                    if ($result) {
                        $this->stats['downloaded']++;
                    } else {
                        $this->stats['failed']++;
                    }
                } else {
                    $this->newLine();
                    $this->line("  Would download: {$product->sku} -> {$imageUrl}");
                }
            } else {
                $this->stats['skipped']++;
            }

            $bar->advance();

            // Rate limiting
            usleep(100000); // 100ms between products
        }

        $bar->finish();
        $this->newLine(2);

        $this->printStats();

        return 0;
    }

    /**
     * Try to find an image URL for the given product
     */
    private function findImageForProduct(Product $product): ?string
    {
        $sku = $product->sku;
        $name = $product->name;
        $brand = $product->brand;

        // Strategy 1: Try ACDC website search first (most reliable for finding exact product)
        if ($brand === 'ACDC' || $brand === 'Acdc') {
            if ($url = $this->tryAcdcWebsite($sku, $name)) {
                return $url;
            }
        }

        // Strategy 2: Try quick CDN patterns (common ones only)
        if ($url = $this->tryAcdcCdnQuick($sku)) {
            return $url;
        }

        // Strategy 3: Full CDN pattern search
        if ($url = $this->tryAcdcCdn($sku)) {
            return $url;
        }

        // Strategy 4: Search ACDC site directly
        if ($brand === 'ACDC' && ($url = $this->searchAcdcSite($sku, $name))) {
            return $url;
        }

        // Strategy 5: Try web search (skip in fast mode)
        if (!$this->fastMode) {
            if ($url = $this->tryGoogleImageSearch($sku, $name, $brand)) {
                return $url;
            }
        }

        return null;
    }

    /**
     * Quick CDN check with most common patterns only
     */
    private function tryAcdcCdnQuick(string $sku): ?string
    {
        $cleanSku = $this->cleanSkuForFilename($sku);

        // Try most common patterns first
        $quickPatterns = [
            "{$this->acdcCdnBase}/ima1/{$cleanSku}.jpg",
            "{$this->acdcCdnBase}/ima1/{$cleanSku}_version-2.jpg",
            "{$this->acdcCdnBase}/ima1/{$cleanSku}-ver2.jpg",
            "{$this->acdcCdnBase}/S/{$cleanSku}.jpg",
        ];

        foreach ($quickPatterns as $url) {
            if ($this->urlExists($url)) {
                return $url;
            }
        }

        return null;
    }

    /**
     * Try various URL patterns on ACDC CDN
     */
    private function tryAcdcCdn(string $sku): ?string
    {
        $cleanSku = $this->cleanSkuForFilename($sku);
        $extensions = ['jpg', 'JPG', 'jpeg', 'png', 'webp'];

        foreach ($this->acdcFolders as $folder) {
            foreach ($this->versionSuffixes as $suffix) {
                foreach ($extensions as $ext) {
                    $url = "{$this->acdcCdnBase}/{$folder}/{$cleanSku}{$suffix}.{$ext}";

                    if ($this->urlExists($url)) {
                        return $url;
                    }
                }
            }
        }

        // Try without folder (root level)
        foreach ($this->versionSuffixes as $suffix) {
            foreach ($extensions as $ext) {
                $url = "{$this->acdcCdnBase}/{$cleanSku}{$suffix}.{$ext}";

                if ($this->urlExists($url)) {
                    return $url;
                }
            }
        }

        // Try common variations of the SKU
        $variations = $this->getSkuVariations($sku);
        foreach ($variations as $variation) {
            foreach ($this->acdcFolders as $folder) {
                foreach ($extensions as $ext) {
                    $url = "{$this->acdcCdnBase}/{$folder}/{$variation}.{$ext}";
                    if ($this->urlExists($url)) {
                        return $url;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Try to scrape ACDC website for product image
     */
    private function tryAcdcWebsite(string $sku, string $name): ?string
    {
        // Search ACDC website for the product
        $searchUrl = 'https://acdc.co.za/module/ambjolisearch/jolisearch';

        try {
            $response = Http::timeout(10)
                ->asForm()
                ->post($searchUrl, [
                    's' => $sku,
                    'ajax' => 1,
                ]);

            if ($response->successful()) {
                $html = $response->body();

                // Look for product image URLs in the response
                if (preg_match('/https?:\/\/a365\.acdc\.co\.za\/Images\/[^"\'>\s]+\.(jpg|jpeg|png|webp)/i', $html, $match)) {
                    $imageUrl = $match[0];
                    if ($this->urlExists($imageUrl)) {
                        return $imageUrl;
                    }
                }
            }
        } catch (\Exception $e) {
            // Silent fail, try next strategy
        }

        return null;
    }

    /**
     * Search ACDC site directly
     */
    private function searchAcdcSite(string $sku, string $name): ?string
    {
        try {
            // Try direct product page URL pattern
            $slug = Str::slug($name);
            $cleanSku = preg_replace('/[^a-zA-Z0-9]/', '', $sku);

            $possibleUrls = [
                "https://acdc.co.za/search?s={$sku}",
            ];

            foreach ($possibleUrls as $searchUrl) {
                $response = Http::timeout(10)->get($searchUrl);

                if ($response->successful()) {
                    $html = $response->body();

                    // Extract image URLs
                    if (preg_match_all('/https?:\/\/a365\.acdc\.co\.za\/Images\/acdc-web-images\/[^"\'>\s]+\.(jpg|jpeg|png|webp)/i', $html, $matches)) {
                        foreach ($matches[0] as $imageUrl) {
                            // Skip tiny thumbnails
                            if (strpos($imageUrl, 'thumb') === false && strpos($imageUrl, 'small') === false) {
                                if ($this->urlExists($imageUrl)) {
                                    return $imageUrl;
                                }
                            }
                        }
                    }
                }

                usleep(200000); // 200ms delay
            }
        } catch (\Exception $e) {
            // Silent fail
        }

        return null;
    }

    /**
     * Try Google Image Search (using Custom Search API or scraping)
     */
    private function tryGoogleImageSearch(string $sku, string $name, string $brand): ?string
    {
        // Build search query
        $query = "{$brand} {$sku} {$name} product image";
        $query = preg_replace('/\s+/', ' ', $query);

        try {
            // Use DuckDuckGo image search (more permissive)
            $searchUrl = 'https://duckduckgo.com/?q=' . urlencode($query) . '&iax=images&ia=images';

            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->get($searchUrl);

            if ($response->successful()) {
                $html = $response->body();

                // Look for high-quality image URLs
                if (preg_match_all('/"(https?:\/\/[^"]+\.(?:jpg|jpeg|png|webp))"/i', $html, $matches)) {
                    foreach ($matches[1] as $imageUrl) {
                        // Skip known watermark sources
                        if ($this->isWatermarkFreeSource($imageUrl)) {
                            if ($this->urlExists($imageUrl)) {
                                return $imageUrl;
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Silent fail
        }

        // Try Bing Image Search as fallback
        try {
            $bingUrl = 'https://www.bing.com/images/search?q=' . urlencode("{$brand} {$sku}") . '&form=HDRSC2';

            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->get($bingUrl);

            if ($response->successful()) {
                $html = $response->body();

                // Extract image URLs from Bing results
                if (preg_match_all('/murl&quot;:&quot;(https?:\/\/[^&]+\.(?:jpg|jpeg|png))/', $html, $matches)) {
                    foreach ($matches[1] as $imageUrl) {
                        $imageUrl = html_entity_decode($imageUrl);
                        if ($this->isWatermarkFreeSource($imageUrl) && $this->urlExists($imageUrl)) {
                            return $imageUrl;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Silent fail
        }

        return null;
    }

    /**
     * Check if URL is from a likely watermark-free source
     */
    private function isWatermarkFreeSource(string $url): bool
    {
        $trustedDomains = [
            'acdc.co.za',
            'a365.acdc.co.za',
            'cdn.shopify.com',
            'images.unsplash.com',
            'gewiss.com',
            'schneider-electric.com',
            'abb.com',
            'legrand.com',
            'philips.com',
            'osram.com',
            'eaton.com',
        ];

        $blockedDomains = [
            'em.co.za', // Known watermarked
            'alibaba.com',
            'aliexpress.com',
            'pinterest.com',
            'facebook.com',
            'instagram.com',
        ];

        foreach ($blockedDomains as $blocked) {
            if (strpos($url, $blocked) !== false) {
                return false;
            }
        }

        foreach ($trustedDomains as $trusted) {
            if (strpos($url, $trusted) !== false) {
                return true;
            }
        }

        return true; // Allow other sources by default
    }

    /**
     * Download image and save to disk
     */
    private function downloadImage(Product $product, string $url, int $minSize): bool
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->get($url);

            if (!$response->successful()) {
                return false;
            }

            $content = $response->body();
            $size = strlen($content);

            // Check minimum size (skip placeholders/thumbnails)
            if ($size < $minSize) {
                return false;
            }

            // Determine file extension from content type or URL
            $contentType = $response->header('Content-Type');
            $extension = $this->getExtensionFromContentType($contentType)
                ?? $this->getExtensionFromUrl($url)
                ?? 'jpg';

            // Generate filename from SKU
            $cleanSku = $this->cleanSkuForFilename($product->sku);
            $filename = "{$cleanSku}.{$extension}";
            $filepath = "{$this->imageDir}/{$filename}";

            // Save file
            file_put_contents($filepath, $content);

            // Update product in database
            $product->image = "acdc/{$filename}";
            $product->save();

            return true;

        } catch (\Exception $e) {
            $this->newLine();
            $this->error("  Error downloading {$product->sku}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Check if a URL exists and returns valid image
     */
    private function urlExists(string $url): bool
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])
                ->head($url);

            if (!$response->successful()) {
                return false;
            }

            // Check content type
            $contentType = $response->header('Content-Type') ?? '';
            if (!str_contains($contentType, 'image')) {
                return false;
            }

            // Check content length (skip tiny files)
            $contentLength = (int) ($response->header('Content-Length') ?? 0);
            if ($contentLength > 0 && $contentLength < 1000) {
                return false;
            }

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Clean SKU for use as filename
     */
    private function cleanSkuForFilename(string $sku): string
    {
        // Replace spaces and special chars
        return preg_replace('/[^a-zA-Z0-9\-_]/', '-', $sku);
    }

    /**
     * Get various SKU variations to try
     */
    private function getSkuVariations(string $sku): array
    {
        $variations = [];
        $clean = $this->cleanSkuForFilename($sku);

        // Remove color/variant suffixes
        $base = preg_replace('/[-_](DL|CW|WW|BK|WH|BL|RD|GN)$/i', '', $sku);
        if ($base !== $sku) {
            $variations[] = $this->cleanSkuForFilename($base);
        }

        // Remove pack size indicators
        $base = preg_replace('/\/\d+$/', '', $sku);
        if ($base !== $sku) {
            $variations[] = $this->cleanSkuForFilename($base);
        }

        // Try without hyphens
        $variations[] = str_replace('-', '', $clean);

        // Try with underscores instead of hyphens
        $variations[] = str_replace('-', '_', $clean);

        return array_unique($variations);
    }

    /**
     * Get file extension from content type
     */
    private function getExtensionFromContentType(?string $contentType): ?string
    {
        if (!$contentType) return null;

        $map = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];

        foreach ($map as $type => $ext) {
            if (str_contains($contentType, $type)) {
                return $ext;
            }
        }

        return null;
    }

    /**
     * Get file extension from URL
     */
    private function getExtensionFromUrl(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) return null;

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']) ? $ext : null;
    }

    /**
     * Print statistics
     */
    private function printStats(): void
    {
        $this->info('=== Image Fetch Statistics ===');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Products Processed', $this->stats['processed']],
                ['Images Found', $this->stats['found']],
                ['Successfully Downloaded', $this->stats['downloaded']],
                ['Download Failed', $this->stats['failed']],
                ['No Image Found', $this->stats['skipped']],
            ]
        );

        if ($this->stats['downloaded'] > 0) {
            $this->info("Images saved to: {$this->imageDir}");
        }
    }
}
