<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'template',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'is_published',
        'show_in_header',
        'show_in_footer',
        'is_homepage',
        'sort_order',
        'show_header',
        'show_footer',
        'show_breadcrumbs',
        'layout_width',
        'custom_css',
        'custom_js',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_header' => 'boolean',
        'show_in_footer' => 'boolean',
        'is_homepage' => 'boolean',
        'show_header' => 'boolean',
        'show_footer' => 'boolean',
        'show_breadcrumbs' => 'boolean',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the sections for the page.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }

    /**
     * Get active sections for the page.
     */
    public function activeSections(): HasMany
    {
        return $this->hasMany(PageSection::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Scope for published pages.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for pages in header menu.
     */
    public function scopeInHeader($query)
    {
        return $query->where('show_in_header', true)
            ->where('is_published', true)
            ->orderBy('sort_order');
    }

    /**
     * Scope for pages in footer menu.
     */
    public function scopeInFooter($query)
    {
        return $query->where('show_in_footer', true)
            ->where('is_published', true)
            ->orderBy('sort_order');
    }

    /**
     * Scope for ordered pages.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Get the homepage.
     */
    public static function homepage()
    {
        return static::where('is_homepage', true)->where('is_published', true)->first();
    }

    /**
     * Set this page as homepage.
     */
    public function setAsHomepage(): void
    {
        // Remove homepage flag from all other pages
        static::where('id', '!=', $this->id)->update(['is_homepage' => false]);
        $this->update(['is_homepage' => true]);
    }

    /**
     * Get available templates.
     */
    public static function getTemplates(): array
    {
        return [
            'default' => 'Default',
            'full-width' => 'Full Width',
            'sidebar-left' => 'Sidebar Left',
            'sidebar-right' => 'Sidebar Right',
            'landing' => 'Landing Page',
            'blank' => 'Blank (No Header/Footer)',
        ];
    }

    /**
     * Get meta title with fallback.
     */
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    /**
     * Get the full URL for the page.
     */
    public function getUrlAttribute(): string
    {
        if ($this->is_homepage) {
            return url('/');
        }
        return url('/page/' . $this->slug);
    }
}
