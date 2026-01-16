<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user only if it doesn't exist (without factory for production)
        if (!User::where('email', 'admin@nayon.co.tz')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@nayon.co.tz',
                'password' => Hash::make('password123'),
            ]);
        }

        // Seed products, categories, and pages
        $this->call([
            ProductSeeder::class,
            PageSeeder::class,
            EmProductSeeder::class,
        ]);
    }
}
