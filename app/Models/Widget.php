<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = [
        'name',
        'type',
        'location',
        'title',
        'content',
        'settings',
        'background_color',
        'background_image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active widgets.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered widgets.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get widgets by location.
     */
    public static function getByLocation(string $location)
    {
        return static::where('location', $location)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get available widget types.
     */
    public static function getTypes(): array
    {
        return [
            'banner' => [
                'name' => 'Banner',
                'icon' => 'fa-image',
                'description' => 'Image banner with optional link',
            ],
            'slider' => [
                'name' => 'Image Slider',
                'icon' => 'fa-images',
                'description' => 'Carousel of images',
            ],
            'featured_products' => [
                'name' => 'Featured Products',
                'icon' => 'fa-star',
                'description' => 'Display featured products',
            ],
            'categories' => [
                'name' => 'Categories',
                'icon' => 'fa-folder',
                'description' => 'Display product categories',
            ],
            'newsletter' => [
                'name' => 'Newsletter',
                'icon' => 'fa-envelope',
                'description' => 'Newsletter signup form',
            ],
            'text' => [
                'name' => 'Text Block',
                'icon' => 'fa-align-left',
                'description' => 'Rich text content',
            ],
            'html' => [
                'name' => 'Custom HTML',
                'icon' => 'fa-code',
                'description' => 'Custom HTML content',
            ],
            'recent_products' => [
                'name' => 'Recent Products',
                'icon' => 'fa-clock',
                'description' => 'Display recently added products',
            ],
            'brands' => [
                'name' => 'Brands',
                'icon' => 'fa-tags',
                'description' => 'Display brand logos',
            ],
            'social_links' => [
                'name' => 'Social Links',
                'icon' => 'fa-share-alt',
                'description' => 'Social media links',
            ],
        ];
    }

    /**
     * Get available locations.
     */
    public static function getLocations(): array
    {
        return [
            'homepage_hero' => 'Homepage - Hero Section',
            'homepage_after_hero' => 'Homepage - After Hero',
            'homepage_featured' => 'Homepage - Featured Section',
            'homepage_middle' => 'Homepage - Middle Section',
            'homepage_bottom' => 'Homepage - Bottom Section',
            'sidebar_top' => 'Sidebar - Top',
            'sidebar_bottom' => 'Sidebar - Bottom',
            'footer_widgets' => 'Footer - Widget Area',
        ];
    }

    /**
     * Get the setting value with default.
     */
    public function getSetting($key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Render the widget view.
     */
    public function render()
    {
        $viewName = 'components.widgets.' . $this->type;

        if (view()->exists($viewName)) {
            return view($viewName, ['widget' => $this])->render();
        }

        // Fallback to default widget view
        return view('components.widgets.default', ['widget' => $this])->render();
    }
}
