<?php

namespace App\Models;

use App\Services\CurrencyService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'slug',
        'category_id',
        'subcategory',
        'brand',
        'list_price',
        'net_price',
        'discount',
        'stock',
        'warranty',
        'image',
        'description',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'list_price' => 'decimal:2',
        'net_price' => 'decimal:2',
        'discount' => 'integer',
        'stock' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('sku', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('brand', 'like', "%{$term}%");
        });
    }

    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('net_price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('net_price', '<=', $max);
        }
        return $query;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getFormattedPriceAttribute(): string
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->formatPrice($this->net_price);
    }

    public function getFormattedListPriceAttribute(): string
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->formatPrice($this->list_price);
    }

    public function getSavingsAttribute(): float
    {
        return $this->list_price - $this->net_price;
    }

    public function getPriceWithVatAttribute(): float
    {
        return $this->net_price * 1.15;
    }

    public function getFormattedPriceWithVatAttribute(): string
    {
        $currencyService = app(CurrencyService::class);
        return $currencyService->formatPrice($this->price_with_vat);
    }

    public function getImageUrlAttribute(): string
    {
        // Check for local images first
        if ($this->image && file_exists(public_path('images/products/' . $this->image))) {
            return asset('images/products/' . $this->image);
        }

        if ($this->image && file_exists(public_path('storage/products/' . $this->image))) {
            return asset('storage/products/' . $this->image);
        }

        // Use ACDC product images based on SKU
        $sku = $this->sku;
        $baseUrl = 'https://a365.acdc.co.za/Images/acdc-web-images/';

        // Map SKUs to their exact ACDC image filenames (verified URLs)
        $imageMap = [
            // Plug Tops & Couplers
            'RA300BK' => 'ima1/RA300BK_version-2.jpg',
            'RA310BK' => 'ima1/RA310BK-ver2.jpg',

            // LED T8 Tubes
            'LEDT8-A4FR-DL' => 'ima1/LEDT8-A4FR-DL-ver2.jpg',
            'LEDT8-A5FR-DL' => 'ima1/LEDT8-A4FR-DL-ver2.jpg',
            'LEDT8-A4FR-CW' => 'ima1/LEDT8-A4FR-DL-ver2.jpg',
            'LEDT8-A2FR-DL' => 'ima1/LEDT8-A4FR-DL-ver2.jpg',
            'LEDT8-A2FR-CW' => 'ima1/LEDT8-A4FR-DL-ver2.jpg',
            'LEDT8-A5FR-CW' => 'ima1/LEDT8-A4FR-DL-ver2.jpg',

            // Electrical Tape
            '3M-74712' => 'ima1/PVC-ELEC-TAPE.jpg',
            '3M-74714' => 'ima1/PVC-ELEC-TAPE.jpg',
            '3M-74715' => 'ima1/PVC-ELEC-TAPE.jpg',
            '3M-74713' => 'ima1/PVC-ELEC-TAPE.jpg',

            // Switches & Sockets
            'B7402' => 'ima1/B7402.jpg',
            'B7002' => 'ima1/B7002.jpg',
            'B7400' => 'ima1/B7400.jpg',
            'B7406' => 'ima1/B7406.jpg',

            // LED Bulbs E27
            'HS-E27-10W-DL' => 'ima1/HS-E27-version-2.jpg',
            'HS-E27-10W-CW' => 'ima1/HS-E27-version-2.jpg',
            'HS-E27-15W-DL' => 'ima1/HS-E27-15W-ver-2.jpg',
            'HS-E27-15W-WW' => 'ima1/HS-E27-15W-ver-2.jpg',
            'T-LED-7W-E27-CW' => 'ima1/HS-E27-version-2.jpg',
            'T-LED-7W-E27-WW' => 'ima1/HS-E27-version-2.jpg',

            // LED Bulbs B22
            'HS-B22-10W-DL' => 'ima1/HS-B22-10W-NEW2.JPG',
            'HS-B22-10W-CW' => 'ima1/HS-B22-10W-NEW2.JPG',
            'HS-B22-10W-WW' => 'ima1/HS-B22-10W-NEW2.JPG',
            'HS-B22-15W-DL' => 'ima1/HS-B22-10W-NEW2.JPG',
            'HS-B22-15W-CW' => 'ima1/HS-B22-10W-NEW2.JPG',
            'T-LED-7W-B22-WW' => 'ima1/HS-B22-10W-NEW2.JPG',
            'T-LED-5W-B22-DL' => 'ima1/HS-B22-10W-NEW2.JPG',
            'LED-A60-7W-B22-DL/2' => 'ima1/HS-B22-10W-NEW2.JPG',

            // GU10 Down Lights
            'SMD-GU10-6W-DL' => 'S/SMD-GU10.jpg',
            'SMD-GU10-6W-CW' => 'S/SMD-GU10.jpg',
            'SMD-GU10-6W-WW' => 'S/SMD-GU10.jpg',
            'LED-GU10-6W-DL/5' => 'S/SMD-GU10.jpg',
            'LED-GU10-6W-WW/5' => 'S/SMD-GU10.jpg',
            'DIM-GU10-5W-WW-5Y' => 'S/SMD-GU10.jpg',

            // Flood Lights
            'FL-10W-CW' => 'ima1/FL-10W-ver8.jpg',
            'FL-20W-CW' => 'ima1/FL-50W-Ver9.jpg',
            'FL-50W-CW' => 'ima1/FL-50W-Ver9.jpg',
            'FL-100W-CW' => 'ima1/FL-50W-Ver9.jpg',
            'FL-150W-CW' => 'ima1/FL-50W-Ver9.jpg',

            // MCBs & Circuit Breakers
            'SA1-10' => 'ima1/SA1-20.jpg',
            'SA1-15' => 'ima1/SA1-20.jpg',
            'SA1-20' => 'ima1/SA1-20.jpg',
            'SA1-32' => 'ima1/SA1-20.jpg',
            'SAE2A-63' => 'ima1/SAE2A-63.jpg',

            // Distribution Boards
            'SDBEDIN-8' => 'ima1/SDBEDIN-12.jpg',
            'SDBEDIN-12' => 'ima1/SDBEDIN-12.jpg',

            // Commercial Lighting
            'NB250-PCL' => 'ima1/NB250-PCL.jpg',
            'BH-01' => 'ima1/BH-01.jpg',
            'LEDWPT8-218' => 'ima1/LEDWPT8-218.jpg',
            'LEDWPT8-222' => 'ima1/LEDWPT8-218.jpg',
            'CP-FD-18W-DL' => 'ima1/CP-FD-18W.jpg',

            // Solar
            'TK-03-20W' => 'ima1/TK-03-20W.jpg',

            // Batteries
            'ES-CR2032-BP2' => 'ima1/ES-CR2032-BP2.jpg',
            'E91BP4-MAX' => 'ima1/E91BP4-MAX.jpg',

            // Cable & Conduit
            'W901 WH' => 'ima1/W901-WH.jpg',
            'CON-20/25' => 'ima1/CON-20.jpg',

            // Power Monitors
            'CP-C018' => 'ima1/CP-C018.jpg',
        ];

        // Return mapped image URL
        if (isset($imageMap[$sku])) {
            return $baseUrl . '/' . $imageMap[$sku];
        }

        // Fallback: generate branded placeholder with product category color
        $categoryColors = [
            1 => 'e74c3c', // Audio - Red
            2 => '3498db', // Automation - Blue
            3 => 'f39c12', // Circuit Breakers - Orange
            4 => '9b59b6', // Enclosures - Purple
            5 => 'f1c40f', // Lighting - Yellow
            6 => '1abc9c', // Power Supplies - Teal
            7 => '27ae60', // Solar - Green
            8 => '34495e', // Installation - Dark Gray
            9 => 'e67e22', // Tools - Orange
            10 => '2980b9', // Pumps - Blue
        ];

        $color = $categoryColors[$this->category_id] ?? '0079c1';
        $initials = $this->getProductInitials();

        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&size=300&background=' . $color . '&color=fff&bold=true&format=svg';
    }

    /**
     * Get short initials from product name for placeholder
     */
    private function getProductInitials(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach ($words as $word) {
            if (strlen($word) > 0 && ctype_alpha($word[0])) {
                $initials .= strtoupper($word[0]);
                if (strlen($initials) >= 3) break;
            }
        }

        return $initials ?: substr($this->sku, 0, 3);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
