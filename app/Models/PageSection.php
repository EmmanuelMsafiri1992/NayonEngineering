<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    protected $fillable = [
        'page_id',
        'type',
        'title',
        'content',
        'settings',
        'background_color',
        'background_image',
        'text_color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the page that owns the section.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Scope for active sections.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered sections.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get available section types.
     */
    public static function getTypes(): array
    {
        return [
            'hero' => [
                'name' => 'Hero Section',
                'icon' => 'fa-image',
                'description' => 'Large banner with title, subtitle, and call-to-action button',
            ],
            'text' => [
                'name' => 'Text Content',
                'icon' => 'fa-align-left',
                'description' => 'Rich text content with WYSIWYG editor',
            ],
            'text_image' => [
                'name' => 'Text with Image',
                'icon' => 'fa-columns',
                'description' => 'Text content alongside an image',
            ],
            'gallery' => [
                'name' => 'Image Gallery',
                'icon' => 'fa-images',
                'description' => 'Display multiple images in a grid',
            ],
            'features' => [
                'name' => 'Features Grid',
                'icon' => 'fa-th-large',
                'description' => 'Display features or services in cards',
            ],
            'cta' => [
                'name' => 'Call to Action',
                'icon' => 'fa-bullhorn',
                'description' => 'Prominent call-to-action section',
            ],
            'testimonials' => [
                'name' => 'Testimonials',
                'icon' => 'fa-quote-right',
                'description' => 'Customer testimonials carousel or grid',
            ],
            'faq' => [
                'name' => 'FAQ',
                'icon' => 'fa-question-circle',
                'description' => 'Frequently asked questions accordion',
            ],
            'contact' => [
                'name' => 'Contact Form',
                'icon' => 'fa-envelope',
                'description' => 'Contact form section',
            ],
            'products' => [
                'name' => 'Featured Products',
                'icon' => 'fa-box',
                'description' => 'Display selected products',
            ],
            'categories' => [
                'name' => 'Categories',
                'icon' => 'fa-folder',
                'description' => 'Display product categories',
            ],
            'video' => [
                'name' => 'Video',
                'icon' => 'fa-video',
                'description' => 'Embed a video (YouTube, Vimeo)',
            ],
            'map' => [
                'name' => 'Map',
                'icon' => 'fa-map-marker-alt',
                'description' => 'Google Maps embed',
            ],
            'html' => [
                'name' => 'Custom HTML',
                'icon' => 'fa-code',
                'description' => 'Custom HTML content',
            ],
            'divider' => [
                'name' => 'Divider',
                'icon' => 'fa-minus',
                'description' => 'Visual separator between sections',
            ],
            'spacer' => [
                'name' => 'Spacer',
                'icon' => 'fa-arrows-alt-v',
                'description' => 'Add vertical spacing',
            ],
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
     * Render the section view.
     */
    public function render()
    {
        $viewName = 'components.sections.' . $this->type;

        if (view()->exists($viewName)) {
            return view($viewName, ['section' => $this])->render();
        }

        // Fallback to default section view
        return view('components.sections.default', ['section' => $this])->render();
    }
}
