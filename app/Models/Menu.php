<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the menu items for the menu.
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    /**
     * Get all menu items including nested.
     */
    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    /**
     * Get active items.
     */
    public function activeItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Scope for active menus.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get menu by location.
     */
    public static function getByLocation(string $location)
    {
        return static::where('location', $location)->where('is_active', true)->first();
    }

    /**
     * Get available locations.
     */
    public static function getLocations(): array
    {
        return [
            'header' => 'Header Navigation',
            'footer' => 'Footer Navigation',
            'sidebar' => 'Sidebar',
            'mobile' => 'Mobile Menu',
        ];
    }
}
