<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            ['id' => 1, 'name' => 'Audio & Visual Alarms', 'subcategories' => ['Beacons', 'Combination Sounder/Beacon', 'Fire Detection', 'Signal Towers', 'Sounders', 'Speakers & PA Systems', 'Traffic Lights'], 'icon' => 'fas fa-bell'],
            ['id' => 2, 'name' => 'Automation Products', 'subcategories' => ['Contactors', 'Counters', 'Hour Meters', 'Level/Pump Control', 'Power Monitors', 'Power Supplies', 'Process Control', 'Relays', 'Smart Control', 'Temperature Control', 'Timers'], 'icon' => 'fas fa-cogs'],
            ['id' => 3, 'name' => 'Circuit Breakers & Switchgear', 'subcategories' => ['Changeover Switches', 'Contactors', 'Fuses', 'Isolators', 'MCBs', 'RCDs', 'Surge Protection'], 'icon' => 'fas fa-bolt'],
            ['id' => 4, 'name' => 'Enclosures & Fittings', 'subcategories' => ['Distribution Boards', 'Electronic Enclosures', 'Floor/Wall Mount', 'Meter Boxes', 'Pushbutton Stations', 'Weather Proof Boxes'], 'icon' => 'fas fa-box'],
            ['id' => 5, 'name' => 'Lighting', 'subcategories' => ['LED Bulbs', 'LED Tubes', 'Flood Lights', 'Down Lights', 'Commercial Lighting', 'Decorative Lighting', 'Solar Lighting'], 'icon' => 'fas fa-lightbulb'],
            ['id' => 6, 'name' => 'Power Supplies & Transformers', 'subcategories' => ['Back-Up Power/UPS', 'Batteries', 'Battery Chargers', 'DC Converters', 'Power Supplies', 'Transformers', 'Voltage Regulators'], 'icon' => 'fas fa-car-battery'],
            ['id' => 7, 'name' => 'Solar', 'subcategories' => ['Solar Panels', 'Inverters', 'Batteries', 'Charge Controllers', 'Mounting Systems', 'Solar Lighting', 'Solar Pumping'], 'icon' => 'fas fa-solar-panel'],
            ['id' => 8, 'name' => 'Installation & Wiring', 'subcategories' => ['Switches & Sockets', 'Plug Tops', 'Cable & Wire', 'Conduit', 'Terminals', 'Electrical Tape'], 'icon' => 'fas fa-plug'],
            ['id' => 9, 'name' => 'Test Instruments & Tools', 'subcategories' => ['Multimeters', 'Clamp Meters', 'Hand Tools', 'Power Tools', 'Safety Equipment'], 'icon' => 'fas fa-tools'],
            ['id' => 10, 'name' => 'Level Control & Pumps', 'subcategories' => ['Float Switches', 'Flow Switches', 'Level Sensors', 'Pumps', 'Pump Accessories'], 'icon' => 'fas fa-water'],
        ];

        foreach ($categories as $index => $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'subcategories' => $cat['subcategories'],
                'icon' => $cat['icon'],
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }

        // Products
        $products = [
            ['sku' => 'RA300BK', 'name' => '16A Black Std Plug Top Rubber', 'category_id' => 8, 'subcategory' => 'Plug Tops', 'brand' => 'ACDC', 'list_price' => 24.00, 'net_price' => 13.04, 'discount' => 46, 'stock' => 93568, 'description' => '16A Black Standard Plug Top with Rubber construction for durability and safety.'],
            ['sku' => 'LEDT8-A4FR-DL', 'name' => '230VAC 18W Daylight Frosted 1200mm LED T8 Tube', 'category_id' => 5, 'subcategory' => 'LED Tubes', 'brand' => 'ACDC', 'list_price' => 68.00, 'net_price' => 25.22, 'discount' => 63, 'stock' => 73581, 'warranty' => '2 Years', 'description' => 'Energy efficient 18W LED T8 tube with daylight frosted finish. 1200mm length.'],
            ['sku' => 'LEDT8-A5FR-DL', 'name' => '230VAC 22W Daylight Frosted 1500mm LED T8 Tube', 'category_id' => 5, 'subcategory' => 'LED Tubes', 'brand' => 'ACDC', 'list_price' => 80.00, 'net_price' => 31.30, 'discount' => 61, 'stock' => 81108, 'warranty' => '2 Years', 'description' => 'Energy efficient 22W LED T8 tube with daylight frosted finish. 1500mm length.'],
            ['sku' => '3M-74712', 'name' => '1710 Black General Purpose PVC Electrical Tape', 'category_id' => 8, 'subcategory' => 'Electrical Tape', 'brand' => '3M Electrical', 'list_price' => 41.00, 'net_price' => 21.74, 'discount' => 47, 'stock' => 6772, 'description' => '3M 1710 Black PVC Electrical Tape for general purpose electrical insulation.'],
            ['sku' => 'B7402', 'name' => '2x16A Switched Socket Outlet with White Cover', 'category_id' => 8, 'subcategory' => 'Switches & Sockets', 'brand' => 'ACDC', 'list_price' => 104.00, 'net_price' => 51.30, 'discount' => 51, 'stock' => 40166, 'description' => 'Double 16A switched socket outlet with white cover plate.'],
            ['sku' => 'HS-E27-10W-DL', 'name' => '230VAC 9W Daylight E27 LED Bulb', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 30.00, 'net_price' => 11.30, 'discount' => 62, 'stock' => 45000, 'description' => '9W Daylight LED bulb with E27 screw base. Energy efficient replacement.'],
            ['sku' => 'HS-B22-10W-DL', 'name' => '230VAC 9W Daylight B22 LED Bulb', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 34.00, 'net_price' => 11.30, 'discount' => 67, 'stock' => 52000, 'description' => '9W Daylight LED bulb with B22 bayonet base. Energy efficient replacement.'],
            ['sku' => 'SMD-GU10-6W-DL', 'name' => '6W GU10 Daylight Down Light', 'category_id' => 5, 'subcategory' => 'Down Lights', 'brand' => 'ACDC', 'list_price' => 30.00, 'net_price' => 8.70, 'discount' => 71, 'stock' => 38000, 'description' => '6W GU10 Daylight LED down light. Perfect for recessed lighting.'],
            ['sku' => 'RA310BK', 'name' => 'Janus Coupler Double Black 15A', 'category_id' => 8, 'subcategory' => 'Plug Tops', 'brand' => 'ACDC', 'list_price' => 41.00, 'net_price' => 21.74, 'discount' => 47, 'stock' => 28000, 'description' => '15A Janus double coupler in black finish.'],
            ['sku' => 'FL-100W-CW', 'name' => '220-240V 100W Cool White LED Aluminium Flood Light IP65', 'category_id' => 5, 'subcategory' => 'Flood Lights', 'brand' => 'ACDC', 'list_price' => 369.00, 'net_price' => 199.13, 'discount' => 46, 'stock' => 5200, 'description' => '100W LED Flood Light with aluminium body. IP65 rated for outdoor use.'],
            ['sku' => 'HS-B22-10W-CW', 'name' => '230VAC 9W Cool White LED Lamp B22', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 34.00, 'net_price' => 11.30, 'discount' => 67, 'stock' => 48000, 'description' => '9W Cool White LED lamp with B22 bayonet base.'],
            ['sku' => 'SA1-20', 'name' => '20A 1-Pole 4.5kA C Curve Mini Rail MCB', 'category_id' => 3, 'subcategory' => 'MCBs', 'brand' => 'ACDC', 'list_price' => 104.00, 'net_price' => 56.52, 'discount' => 46, 'stock' => 32000, 'description' => '20A Single pole miniature circuit breaker with C curve. 4.5kA breaking capacity.'],
            ['sku' => 'LEDT8-A2FR-DL', 'name' => '230VAC 9W Daylight LED T8 Tube 550mm', 'category_id' => 5, 'subcategory' => 'LED Tubes', 'brand' => 'ACDC', 'list_price' => 49.00, 'net_price' => 18.26, 'discount' => 63, 'stock' => 42000, 'description' => '9W Daylight LED T8 tube. 550mm length for compact fittings.'],
            ['sku' => 'LEDT8-A5FR-CW', 'name' => '230VAC 22W Cool White LED T8 Tube 1500mm', 'category_id' => 5, 'subcategory' => 'LED Tubes', 'brand' => 'ACDC', 'list_price' => 80.00, 'net_price' => 31.30, 'discount' => 61, 'stock' => 55000, 'description' => '22W Cool White LED T8 tube. 1500mm length.'],
            ['sku' => 'SMD-GU10-6W-WW', 'name' => '230VAC 6W GU10 Warm White Down Light', 'category_id' => 5, 'subcategory' => 'Down Lights', 'brand' => 'ACDC', 'list_price' => 30.00, 'net_price' => 8.70, 'discount' => 71, 'stock' => 35000, 'description' => '6W GU10 Warm White LED down light.'],
            ['sku' => 'LED-GU10-6W-DL/5', 'name' => '230VAC 6W GU10 Daylight Down Light - 5 Pack', 'category_id' => 5, 'subcategory' => 'Down Lights', 'brand' => 'ACDC', 'list_price' => 148.00, 'net_price' => 42.61, 'discount' => 71, 'stock' => 12000, 'description' => 'Pack of 5 x 6W GU10 Daylight LED down lights. Great value.'],
            ['sku' => '3M-74715', 'name' => '1710 White General Purpose PVC Electrical Tape', 'category_id' => 8, 'subcategory' => 'Electrical Tape', 'brand' => '3M Electrical', 'list_price' => 41.00, 'net_price' => 21.74, 'discount' => 47, 'stock' => 5500, 'description' => '3M 1710 White PVC Electrical Tape for general purpose electrical insulation.'],
            ['sku' => 'FL-20W-CW', 'name' => '220-240VAC 20W LED Cool White Mini Flood Light', 'category_id' => 5, 'subcategory' => 'Flood Lights', 'brand' => 'ACDC', 'list_price' => 129.00, 'net_price' => 68.70, 'discount' => 47, 'stock' => 8500, 'description' => 'Compact 20W LED mini flood light. Cool white output.'],
            ['sku' => 'HS-B22-15W-DL', 'name' => '230VAC 15W B22 Daylight LED Bulb', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 39.00, 'net_price' => 13.04, 'discount' => 67, 'stock' => 42000, 'description' => '15W Daylight LED bulb with B22 bayonet base. High brightness.'],
            ['sku' => 'SA1-10', 'name' => '10A 1-Pole 4.5kA C Curve Mini Rail MCB', 'category_id' => 3, 'subcategory' => 'MCBs', 'brand' => 'ACDC', 'list_price' => 104.00, 'net_price' => 56.52, 'discount' => 46, 'stock' => 38000, 'description' => '10A Single pole miniature circuit breaker with C curve. 4.5kA breaking capacity.'],
            ['sku' => 'T-LED-7W-E27-CW', 'name' => '230VAC 7W Cool White LED Bulb E27 4200k', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 28.00, 'net_price' => 10.43, 'discount' => 63, 'stock' => 55000, 'description' => '7W Cool White LED bulb with E27 screw base. 4200K colour temperature.'],
            ['sku' => 'B7002', 'name' => '2-Lever 1-Way Switch 2x4 with White Cover Plate', 'category_id' => 8, 'subcategory' => 'Switches & Sockets', 'brand' => 'ACDC', 'list_price' => 45.00, 'net_price' => 24.35, 'discount' => 46, 'stock' => 28000, 'description' => '2-Lever 1-way light switch. 2x4 module with white cover plate.'],
            ['sku' => 'HS-E27-15W-WW', 'name' => '230VAC 15W Warm White LED Lamp E27', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 39.00, 'net_price' => 13.04, 'discount' => 67, 'stock' => 35000, 'description' => '15W Warm White LED bulb with E27 screw base.'],
            ['sku' => 'SAE2A-63', 'name' => '63A 30mA 2P RCD No Overload Type A', 'category_id' => 3, 'subcategory' => 'RCDs', 'brand' => 'ACDC', 'list_price' => 622.00, 'net_price' => 346.96, 'discount' => 44, 'stock' => 8500, 'description' => '63A 2-Pole Residual Current Device. 30mA sensitivity, Type A.'],
            ['sku' => 'CP-FD-18W-DL', 'name' => '90-260VAC 18W LED Magnetic Retrofit Module Daylight', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 122.00, 'net_price' => 60.00, 'discount' => 51, 'stock' => 15000, 'description' => '18W LED Magnetic retrofit module. Daylight colour. Wide voltage input.'],
            ['sku' => 'CON-20/25', 'name' => 'PVC Conduit Pipe 20mm - Bundle of 25 x 4M', 'category_id' => 8, 'subcategory' => 'Conduit', 'brand' => 'ACDC', 'list_price' => 716.00, 'net_price' => 251.00, 'discount' => 65, 'stock' => 2500, 'description' => 'Bundle of 25 x 4 metre 20mm PVC conduit pipes.'],
            ['sku' => 'LED-A60-7W-B22-DL/2', 'name' => '230VAC 7W Daylight A60 B22 LED Lamp - 2 Pack', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 55.00, 'net_price' => 20.87, 'discount' => 62, 'stock' => 22000, 'description' => 'Pack of 2 x 7W Daylight LED lamps with B22 bayonet base.'],
            ['sku' => 'HS-B22-10W-WW', 'name' => '230VAC 9W Warm White LED Lamp B22', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 34.00, 'net_price' => 11.30, 'discount' => 67, 'stock' => 42000, 'description' => '9W Warm White LED lamp with B22 bayonet base.'],
            ['sku' => 'FL-150W-CW', 'name' => '220-240V 150W Cool White LED Flood Light IP65', 'category_id' => 5, 'subcategory' => 'Flood Lights', 'brand' => 'ACDC', 'list_price' => 668.00, 'net_price' => 346.96, 'discount' => 48, 'stock' => 3200, 'description' => '150W LED Flood Light. IP65 rated for outdoor use. Cool white output.'],
            ['sku' => 'CP-C018', 'name' => 'CLOU 80A CITIQ Prepaid Electricity Sub Meter', 'category_id' => 2, 'subcategory' => 'Power Monitors', 'brand' => 'Citiq Prepaid', 'list_price' => 122.00, 'net_price' => 94.78, 'discount' => 22, 'stock' => 5500, 'description' => '80A Prepaid electricity sub meter by CITIQ.'],
            ['sku' => 'SDBEDIN-12', 'name' => 'White DIN DB 12-Way Surface with Door', 'category_id' => 4, 'subcategory' => 'Distribution Boards', 'brand' => 'ACDC', 'list_price' => 194.00, 'net_price' => 129.57, 'discount' => 33, 'stock' => 8500, 'description' => '12-Way DIN rail distribution board. Surface mount with door.'],
            ['sku' => 'SA1-32', 'name' => '32A 1-Pole 4.5kA C Curve Mini Rail MCB', 'category_id' => 3, 'subcategory' => 'MCBs', 'brand' => 'ACDC', 'list_price' => 104.00, 'net_price' => 56.52, 'discount' => 46, 'stock' => 28000, 'description' => '32A Single pole miniature circuit breaker with C curve. 4.5kA breaking capacity.'],
            ['sku' => 'LED-GU10-6W-WW/5', 'name' => '230VAC 6W GU10 Warm White Down Light - 5 Pack', 'category_id' => 5, 'subcategory' => 'Down Lights', 'brand' => 'ACDC', 'list_price' => 148.00, 'net_price' => 42.61, 'discount' => 71, 'stock' => 10000, 'description' => 'Pack of 5 x 6W GU10 Warm White LED down lights.'],
            ['sku' => 'T-LED-7W-B22-WW', 'name' => '230VAC 7W Warm White LED Bulb B22 2700k', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 28.00, 'net_price' => 10.43, 'discount' => 63, 'stock' => 48000, 'description' => '7W Warm White LED bulb with B22 bayonet base. 2700K colour temperature.'],
            ['sku' => 'HS-B22-15W-CW', 'name' => '230VAC 15W Cool White LED Lamp B22', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 39.00, 'net_price' => 13.04, 'discount' => 67, 'stock' => 38000, 'description' => '15W Cool White LED lamp with B22 bayonet base.'],
            ['sku' => 'SA1-15', 'name' => '15A 1-Pole 4.5kA C Curve Mini Rail MCB', 'category_id' => 3, 'subcategory' => 'MCBs', 'brand' => 'ACDC', 'list_price' => 104.00, 'net_price' => 56.52, 'discount' => 46, 'stock' => 32000, 'description' => '15A Single pole miniature circuit breaker with C curve. 4.5kA breaking capacity.'],
            ['sku' => 'W901 WH', 'name' => '1.5mm x 2 Core + E Flat White Cable - 100m', 'category_id' => 8, 'subcategory' => 'Cable & Wire', 'brand' => 'ACDC', 'list_price' => 1712.00, 'net_price' => 1018.00, 'discount' => 41, 'stock' => 850, 'description' => '100 metre roll of 1.5mm 2 core + earth flat white cable.'],
            ['sku' => 'LEDT8-A2FR-CW', 'name' => '230VAC 9W Cool White LED T8 Tube 550mm', 'category_id' => 5, 'subcategory' => 'LED Tubes', 'brand' => 'ACDC', 'list_price' => 49.00, 'net_price' => 18.26, 'discount' => 63, 'stock' => 38000, 'description' => '9W Cool White LED T8 tube. 550mm length for compact fittings.'],
            ['sku' => 'NB250-PCL', 'name' => '6 Inch PVC Ceiling Light Fitting - Clear', 'category_id' => 5, 'subcategory' => 'Commercial Lighting', 'brand' => 'ACDC', 'list_price' => 38.00, 'net_price' => 25.22, 'discount' => 34, 'stock' => 18000, 'description' => '6 inch PVC ceiling light fitting with clear cover.'],
            ['sku' => 'ES-CR2032-BP2', 'name' => 'Energizer Lithium Coin 2032 Blister - 2 Pack', 'category_id' => 6, 'subcategory' => 'Batteries', 'brand' => 'Energizer', 'list_price' => 46.00, 'net_price' => 37.95, 'discount' => 18, 'stock' => 12000, 'description' => 'Pack of 2 Energizer CR2032 lithium coin batteries.'],
            ['sku' => 'T-LED-7W-E27-WW', 'name' => '230VAC 7W Warm White LED Bulb E27 2700k', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 28.00, 'net_price' => 10.43, 'discount' => 63, 'stock' => 52000, 'description' => '7W Warm White LED bulb with E27 screw base. 2700K colour temperature.'],
            ['sku' => 'DIM-GU10-5W-WW-5Y', 'name' => '230VAC 5W GU10 Warm White Dimmable LED - 5 Year Warranty', 'category_id' => 5, 'subcategory' => 'Down Lights', 'brand' => 'ACDC', 'list_price' => 66.00, 'net_price' => 27.83, 'discount' => 58, 'stock' => 15000, 'warranty' => '5 Years', 'description' => '5W Dimmable GU10 LED down light. Warm white. 5 year warranty.'],
            ['sku' => 'BH-01', 'name' => 'Bulk Head Fitting B22 Holder', 'category_id' => 5, 'subcategory' => 'Commercial Lighting', 'brand' => 'ACDC', 'list_price' => 46.00, 'net_price' => 33.91, 'discount' => 26, 'stock' => 22000, 'description' => 'Bulk head light fitting with B22 holder.'],
            ['sku' => 'B7406', 'name' => '1x16A + 1X New SA Switch Socket', 'category_id' => 8, 'subcategory' => 'Switches & Sockets', 'brand' => 'ACDC', 'list_price' => 95.00, 'net_price' => 51.30, 'discount' => 46, 'stock' => 25000, 'description' => 'Combination 16A socket and SA switch socket outlet.'],
            ['sku' => 'SDBEDIN-8', 'name' => 'White DIN DB 8-Way Surface', 'category_id' => 4, 'subcategory' => 'Distribution Boards', 'brand' => 'ACDC', 'list_price' => 133.00, 'net_price' => 86.09, 'discount' => 35, 'stock' => 12000, 'description' => '8-Way DIN rail distribution board. Surface mount.'],
            ['sku' => 'TK-03-20W', 'name' => 'Solar 20W LED Floodlight Kit', 'category_id' => 7, 'subcategory' => 'Solar Lighting', 'brand' => 'ACDC', 'list_price' => 542.00, 'net_price' => 381.74, 'discount' => 30, 'stock' => 3500, 'description' => 'Complete solar flood light kit with 20W LED light and solar panel.'],
            ['sku' => 'E91BP4-MAX', 'name' => 'Energizer Max Battery AA - 4 Pack', 'category_id' => 6, 'subcategory' => 'Batteries', 'brand' => 'Energizer', 'list_price' => 101.00, 'net_price' => 83.33, 'discount' => 18, 'stock' => 8500, 'description' => 'Pack of 4 Energizer Max AA batteries.'],
            ['sku' => 'LEDWPT8-218', 'name' => '2X18W LED Fitting 4FT IP65', 'category_id' => 5, 'subcategory' => 'Commercial Lighting', 'brand' => 'ACDC', 'list_price' => 368.00, 'net_price' => 207.83, 'discount' => 44, 'stock' => 6500, 'description' => 'Dual 18W LED fitting. 4 foot length. IP65 waterproof rated.'],
            ['sku' => '3M-74713', 'name' => '1710 Yellow General Purpose PVC Electrical Tape', 'category_id' => 8, 'subcategory' => 'Electrical Tape', 'brand' => '3M Electrical', 'list_price' => 41.00, 'net_price' => 21.74, 'discount' => 47, 'stock' => 4800, 'description' => '3M 1710 Yellow PVC Electrical Tape for phase identification.'],
            ['sku' => 'B7400', 'name' => '16A Switched Socket Outlet', 'category_id' => 8, 'subcategory' => 'Switches & Sockets', 'brand' => 'ACDC', 'list_price' => 87.00, 'net_price' => 42.61, 'discount' => 51, 'stock' => 35000, 'description' => 'Single 16A switched socket outlet.'],
            ['sku' => 'LEDWPT8-222', 'name' => '2X22W LED Fitting 5FT IP65', 'category_id' => 5, 'subcategory' => 'Commercial Lighting', 'brand' => 'ACDC', 'list_price' => 446.00, 'net_price' => 251.30, 'discount' => 44, 'stock' => 5200, 'description' => 'Dual 22W LED fitting. 5 foot length. IP65 waterproof rated.'],
            ['sku' => 'T-LED-5W-B22-DL', 'name' => '5W Daylight LED Bulb B22', 'category_id' => 5, 'subcategory' => 'LED Bulbs', 'brand' => 'ACDC', 'list_price' => 28.00, 'net_price' => 9.57, 'discount' => 66, 'stock' => 55000, 'description' => '5W Daylight LED bulb with B22 bayonet base.'],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, [
                'slug' => Str::slug($product['name']),
                'is_active' => true,
                'is_featured' => $product['discount'] > 50,
            ]));
        }
    }
}
