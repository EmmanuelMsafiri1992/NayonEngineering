<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            // Only load settings for non-admin views to avoid overhead
            // Admin views load settings directly
            if (!$view->offsetExists('siteSettings')) {
                try {
                    $settings = Setting::getAll();
                    $view->with('siteSettings', $settings);
                } catch (\Exception $e) {
                    // Database might not be ready during migrations
                    $view->with('siteSettings', []);
                }
            }
        });
    }
}
