# Nayon Engineering - E-commerce Platform

Professional e-commerce platform for industrial electrical products, spares, and supplies.

## Features

- **Product Catalog** - Browse products by category with filtering and search
- **Shopping Cart** - Add products, manage quantities, checkout
- **Wishlist** - Save products for later
- **Responsive Design** - Mobile-friendly light theme
- **Product Images** - Real product images from ACDC catalog
- **Categories** - Audio & Visual, Automation, Circuit Breakers, Lighting, Solar, and more

## Tech Stack

- **Framework:** Laravel 11
- **Database:** MySQL/SQLite
- **Frontend:** Blade templates, Custom CSS
- **Deployment:** GitHub Actions (auto-deploy on push)

## Installation

```bash
# Clone repository
git clone https://github.com/EmmanuelMsafiri1992/NayonEngineering.git

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations and seed data
php artisan migrate --seed

# Start development server
php artisan serve
```

## Deployment

This project uses GitHub Actions for automated deployment. On every push to the `main` branch:

1. Code is pulled to the server
2. Files are synced to production directory
3. Composer dependencies are installed
4. Database migrations run
5. Caches are optimized

## Product Categories

- Audio & Visual Alarms
- Automation Products
- Circuit Breakers & Switchgear
- Enclosures & Fittings
- Lighting (LED Bulbs, Tubes, Flood Lights)
- Power Supplies & Transformers
- Solar Products
- Installation & Wiring
- Test Instruments & Tools
- Level Control & Pumps

## License

Proprietary - All rights reserved.
