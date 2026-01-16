<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CategorizeProducts extends Command
{
    protected $signature = 'products:categorize';
    protected $description = 'Re-categorize products based on their names and descriptions';

    // Keywords to category mappings
    private $categoryKeywords = [
        'Contactors & Motor Starters' => ['contactor', 'motor starter', 'starter', 'dol'],
        'Relays & Sockets' => ['relay', 'socket relay', 'finder'],
        'Circuit Breakers & MCBs' => ['circuit breaker', 'mcb', 'mccb', 'breaker', 'miniature circuit'],
        'Fuses & Protection' => ['fuse', 'fuse holder', 'protection', 'surge'],
        'Overload Relays' => ['overload', 'thermal overload', 'overload relay'],
        'Switches & Isolators' => ['switch', 'isolator', 'disconnect', 'cam switch'],
        'Energy Meters' => ['energy meter', 'kwh meter', 'power meter', 'electricity meter'],
        'Panel Meters' => ['panel meter', 'ammeter', 'voltmeter', 'digital meter', 'analog meter'],
        'Power Analysers' => ['power analyser', 'power analyzer', 'power quality', 'power monitor'],
        'Current Transformers' => ['current transformer', 'ct', 'rogowski'],
        'PLCs & Controllers' => ['plc', 'controller', 'programmable', 'cpu module'],
        'HMI & Displays' => ['hmi', 'touch panel', 'display', 'operator panel'],
        'Sensors & Proximity' => ['sensor', 'proximity', 'photoelectric', 'inductive', 'capacitive'],
        'Encoders' => ['encoder', 'rotary encoder', 'incremental'],
        'Timers & Counters' => ['timer', 'counter', 'time relay', 'timing'],
        'Soft Starters' => ['soft starter', 'softstarter'],
        'Variable Frequency Drives' => ['vfd', 'inverter', 'variable speed', 'frequency drive', 'vsd'],
        'Capacitors & Power Factor' => ['capacitor', 'power factor', 'pfc'],
        'UPS & Power Supply' => ['ups', 'power supply', 'uninterruptible'],
        'Transformers' => ['transformer', 'voltage transformer'],
        'Enclosures & Cabinets' => ['enclosure', 'cabinet', 'box', 'housing'],
        'Terminals & Connectors' => ['terminal', 'connector', 'terminal block', 'plug', 'junction'],
        'Cable & Wiring' => ['cable', 'wire', 'conduit', 'wiring', 'gland'],
        'DIN Rail & Mounting' => ['din rail', 'mounting', 'rail', 'busbar'],
        'Pilot Lights & Indicators' => ['pilot light', 'indicator', 'led indicator', 'signal'],
        'Pushbuttons & Controls' => ['pushbutton', 'push button', 'selector', 'control station'],
        'Beacons & Alarms' => ['beacon', 'alarm', 'tower light', 'siren', 'buzzer'],
        'Industrial Plugs & Sockets' => ['industrial plug', 'industrial socket', 'cee'],
        'Safety Equipment' => ['safety', 'emergency stop', 'light curtain', 'interlock'],
        'Temperature Control' => ['temperature', 'thermostat', 'temperature controller'],
        'Level & Flow' => ['level', 'flow', 'float switch', 'level sensor'],
        'Pressure Sensing' => ['pressure', 'pressure switch', 'pressure transmitter'],
    ];

    private $categoryMap = [];

    public function handle()
    {
        $this->info('Starting product categorization...');

        // Get products in generic categories
        $genericCategories = ['Catalogue Products', 'Catalogue', 'Electrical Components', 'Electrical Equipment', 'General'];
        $products = Product::whereHas('category', function ($q) use ($genericCategories) {
            $q->whereIn('name', $genericCategories);
        })->get();

        $this->info("Found {$products->count()} products in generic categories");

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $recategorized = 0;

        foreach ($products as $product) {
            $newCategory = $this->determineCategory($product);

            if ($newCategory && $newCategory->id !== $product->category_id) {
                $product->category_id = $newCategory->id;
                $product->save();
                $recategorized++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Clean up empty categories
        $this->info('Cleaning up empty categories...');
        $deleted = Category::whereDoesntHave('products')->delete();

        $this->info("Recategorized: {$recategorized} products");
        $this->info("Deleted empty categories: {$deleted}");

        // Show new category distribution
        $this->newLine();
        $this->info('Updated category distribution:');
        foreach (Category::withCount('products')->orderBy('products_count', 'desc')->limit(20)->get() as $cat) {
            $this->line("  - {$cat->name}: {$cat->products_count}");
        }

        return 0;
    }

    private function determineCategory(Product $product): ?Category
    {
        $searchText = strtolower($product->name . ' ' . $product->description . ' ' . ($product->subcategory ?? ''));

        foreach ($this->categoryKeywords as $categoryName => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($searchText, strtolower($keyword))) {
                    return $this->getOrCreateCategory($categoryName);
                }
            }
        }

        // If no match, try brand-based categorization
        $brand = strtolower($product->brand ?? '');
        if (str_contains($brand, 'finder')) {
            return $this->getOrCreateCategory('Relays & Sockets');
        }
        if (str_contains($brand, 'lovato')) {
            return $this->getOrCreateCategory('Motor Control');
        }
        if (str_contains($brand, 'hager')) {
            return $this->getOrCreateCategory('Circuit Breakers & MCBs');
        }
        if (str_contains($brand, 'socomec')) {
            return $this->getOrCreateCategory('Energy Meters');
        }

        return null; // Keep in current category
    }

    private function getOrCreateCategory(string $name): Category
    {
        $slug = Str::slug($name);

        if (isset($this->categoryMap[$slug])) {
            return $this->categoryMap[$slug];
        }

        $icons = [
            'contactor' => 'fa-plug',
            'relay' => 'fa-exchange-alt',
            'circuit' => 'fa-microchip',
            'breaker' => 'fa-microchip',
            'fuse' => 'fa-shield-alt',
            'overload' => 'fa-exclamation-triangle',
            'switch' => 'fa-toggle-on',
            'meter' => 'fa-tachometer-alt',
            'analyser' => 'fa-chart-line',
            'transformer' => 'fa-charging-station',
            'plc' => 'fa-microchip',
            'hmi' => 'fa-desktop',
            'sensor' => 'fa-satellite-dish',
            'encoder' => 'fa-sync',
            'timer' => 'fa-clock',
            'starter' => 'fa-play-circle',
            'drive' => 'fa-cogs',
            'capacitor' => 'fa-battery-full',
            'ups' => 'fa-battery-half',
            'enclosure' => 'fa-box',
            'terminal' => 'fa-link',
            'cable' => 'fa-ethernet',
            'din' => 'fa-bars',
            'pilot' => 'fa-lightbulb',
            'pushbutton' => 'fa-hand-pointer',
            'beacon' => 'fa-bell',
            'safety' => 'fa-shield-alt',
            'temperature' => 'fa-thermometer-half',
            'level' => 'fa-water',
            'pressure' => 'fa-tachometer-alt',
            'motor' => 'fa-fan',
        ];

        $icon = 'fa-plug';
        $lowerName = strtolower($name);
        foreach ($icons as $keyword => $iconClass) {
            if (str_contains($lowerName, $keyword)) {
                $icon = $iconClass;
                break;
            }
        }

        $category = Category::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'icon' => $icon,
                'sort_order' => Category::max('sort_order') + 1,
                'is_active' => true,
            ]
        );

        $this->categoryMap[$slug] = $category;
        return $category;
    }
}
