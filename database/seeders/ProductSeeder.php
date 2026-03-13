<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // ── Laptops (category_id: 1) ──
            [
                'category_id' => 1, 'brand' => 'ASUS', 'name' => 'ASUS ROG Strix G16', 'slug' => 'asus-rog-strix-g16',
                'description' => 'Powerful gaming laptop with Intel Core i9-13980HX, NVIDIA GeForce RTX 4070, 16" QHD+ 240Hz display, and advanced thermal design for peak performance.',
                'specifications' => ['Processor' => 'Intel Core i9-13980HX', 'GPU' => 'NVIDIA GeForce RTX 4070 8GB', 'RAM' => '16GB DDR5 4800MHz', 'Storage' => '1TB NVMe SSD', 'Display' => '16" QHD+ 240Hz IPS', 'Battery' => '90Wh', 'OS' => 'Windows 11 Home'],
                'price' => 24999000, 'stock' => 15, 'image' => 'products/asus-rog-strix-g16.jpg', 'is_trending' => true, 'view_count' => 350, 'sold_count' => 45,
            ],
            [
                'category_id' => 1, 'brand' => 'Apple', 'name' => 'MacBook Pro 14" M3 Pro', 'slug' => 'macbook-pro-14-m3-pro',
                'description' => 'Apple MacBook Pro with M3 Pro chip, delivering extraordinary performance for demanding workflows like video editing and 3D rendering.',
                'specifications' => ['Processor' => 'Apple M3 Pro (12-core CPU)', 'GPU' => '18-core GPU', 'RAM' => '18GB Unified Memory', 'Storage' => '512GB SSD', 'Display' => '14.2" Liquid Retina XDR', 'Battery' => 'Up to 17 hours', 'OS' => 'macOS Sonoma'],
                'price' => 31999000, 'stock' => 10, 'image' => 'products/macbook-pro-14-m3-pro.jpg', 'is_trending' => true, 'view_count' => 520, 'sold_count' => 80,
            ],
            [
                'category_id' => 1, 'brand' => 'Lenovo', 'name' => 'Lenovo ThinkPad X1 Carbon Gen 11', 'slug' => 'lenovo-thinkpad-x1-carbon-gen11',
                'description' => 'Ultra-light business laptop with 13th Gen Intel Core processor, stunning 14" 2.8K OLED display, and legendary ThinkPad durability.',
                'specifications' => ['Processor' => 'Intel Core i7-1365U', 'GPU' => 'Intel Iris Xe Graphics', 'RAM' => '16GB LPDDR5', 'Storage' => '512GB NVMe SSD', 'Display' => '14" 2.8K OLED', 'Battery' => '57Wh', 'OS' => 'Windows 11 Pro'],
                'price' => 27499000, 'stock' => 8, 'image' => 'products/lenovo-thinkpad-x1-carbon.jpg', 'is_trending' => false, 'view_count' => 180, 'sold_count' => 32,
            ],
            [
                'category_id' => 1, 'brand' => 'Acer', 'name' => 'Acer Nitro V 15', 'slug' => 'acer-nitro-v-15',
                'description' => 'Budget-friendly gaming laptop with Intel Core i5-13420H, NVIDIA RTX 4050, and a 15.6" Full HD 144Hz display.',
                'specifications' => ['Processor' => 'Intel Core i5-13420H', 'GPU' => 'NVIDIA GeForce RTX 4050 6GB', 'RAM' => '16GB DDR5', 'Storage' => '512GB NVMe SSD', 'Display' => '15.6" FHD 144Hz IPS', 'Battery' => '57.5Wh', 'OS' => 'Windows 11 Home'],
                'price' => 13499000, 'stock' => 25, 'image' => 'products/acer-nitro-v-15.jpg', 'is_trending' => false, 'view_count' => 290, 'sold_count' => 60,
            ],
            [
                'category_id' => 1, 'brand' => 'HP', 'name' => 'HP Spectre x360 14', 'slug' => 'hp-spectre-x360-14',
                'description' => 'Premium 2-in-1 convertible laptop with Intel Core Ultra 7, 14" 2.8K OLED touch display, and gem-cut design in Nightfall Black.',
                'specifications' => ['Processor' => 'Intel Core Ultra 7 155H', 'GPU' => 'Intel Arc Graphics', 'RAM' => '16GB LPDDR5X', 'Storage' => '1TB NVMe SSD', 'Display' => '14" 2.8K OLED Touch', 'Battery' => '68Wh', 'OS' => 'Windows 11 Home'],
                'price' => 22999000, 'stock' => 7, 'image' => 'products/hp-spectre-x360-14.jpg', 'is_trending' => true, 'view_count' => 315, 'sold_count' => 28,
            ],

            // ── Desktops (category_id: 2) ──
            [
                'category_id' => 2, 'brand' => 'Custom Build', 'name' => 'Custom Gaming PC RTX 4080', 'slug' => 'custom-gaming-pc-rtx-4080',
                'description' => 'Pre-built high-end gaming desktop with Intel i7-14700K, RTX 4080 Super, liquid cooling, and RGB lighting throughout.',
                'specifications' => ['Processor' => 'Intel Core i7-14700K', 'GPU' => 'NVIDIA GeForce RTX 4080 Super 16GB', 'RAM' => '32GB DDR5 5600MHz', 'Storage' => '1TB NVMe SSD + 2TB HDD', 'Cooling' => '240mm AIO Liquid Cooler', 'PSU' => '850W 80+ Gold', 'Case' => 'NZXT H7 Flow'],
                'price' => 35999000, 'stock' => 5, 'image' => 'products/custom-gaming-pc-rtx4080.jpg', 'is_trending' => true, 'view_count' => 410, 'sold_count' => 12,
            ],
            [
                'category_id' => 2, 'brand' => 'Custom Build', 'name' => 'Office Desktop PC Pro', 'slug' => 'office-desktop-pc-pro',
                'description' => 'Reliable office desktop with Intel i5-13400, 16GB RAM, and 512GB SSD for smooth productivity and multitasking.',
                'specifications' => ['Processor' => 'Intel Core i5-13400', 'GPU' => 'Intel UHD Graphics 730', 'RAM' => '16GB DDR4 3200MHz', 'Storage' => '512GB NVMe SSD', 'PSU' => '500W 80+ Bronze', 'Connectivity' => 'Wi-Fi 6, Bluetooth 5.2'],
                'price' => 9499000, 'stock' => 2, 'image' => 'products/office-desktop-pro.jpg', 'is_trending' => false, 'view_count' => 95, 'sold_count' => 28,
            ],
            [
                'category_id' => 2, 'brand' => 'Custom Build', 'name' => 'Streaming & Content Creator PC', 'slug' => 'streaming-content-creator-pc',
                'description' => 'A powerful desktop built for streamers and content creators with AMD Ryzen 9 7900X, RTX 4070, and 64GB RAM for seamless multitasking.',
                'specifications' => ['Processor' => 'AMD Ryzen 9 7900X', 'GPU' => 'NVIDIA GeForce RTX 4070 12GB', 'RAM' => '64GB DDR5 5200MHz', 'Storage' => '2TB NVMe SSD', 'Cooling' => '360mm AIO Liquid Cooler', 'PSU' => '750W 80+ Gold', 'Case' => 'Corsair 4000D Airflow'],
                'price' => 28999000, 'stock' => 4, 'image' => 'products/streaming-creator-pc.jpg', 'is_trending' => false, 'view_count' => 220, 'sold_count' => 8,
            ],

            // ── PC Components (category_id: 3) ──
            [
                'category_id' => 3, 'brand' => 'NVIDIA', 'name' => 'NVIDIA GeForce RTX 4070 Ti Super', 'slug' => 'nvidia-rtx-4070-ti-super',
                'component_type' => 'gpu', 'tdp_watts' => 285,
                'description' => 'High-performance graphics card for 1440p and 4K gaming with DLSS 3 and ray tracing support.',
                'specifications' => ['CUDA Cores' => '8448', 'Memory' => '16GB GDDR6X', 'Boost Clock' => '2610 MHz', 'Memory Bus' => '256-bit', 'TDP' => '285W', 'Connector' => '16-pin'],
                'price' => 13499000, 'stock' => 12, 'image' => 'products/rtx-4070-ti-super.jpg', 'is_trending' => true, 'view_count' => 680, 'sold_count' => 95,
            ],
            [
                'category_id' => 3, 'brand' => 'AMD', 'name' => 'AMD Ryzen 7 7800X3D', 'slug' => 'amd-ryzen-7-7800x3d',
                'component_type' => 'cpu', 'socket_type' => 'AM5', 'tdp_watts' => 120,
                'description' => 'The ultimate gaming processor with 3D V-Cache technology, 8 cores, 16 threads, and blazing-fast single-thread performance.',
                'specifications' => ['Cores / Threads' => '8 / 16', 'Base Clock' => '4.2 GHz', 'Boost Clock' => '5.0 GHz', 'Cache' => '96MB (L3 3D V-Cache)', 'TDP' => '120W', 'Socket' => 'AM5'],
                'price' => 7299000, 'stock' => 18, 'image' => 'products/ryzen-7-7800x3d.jpg', 'is_trending' => true, 'view_count' => 520, 'sold_count' => 110,
            ],
            [
                'category_id' => 3, 'brand' => 'Samsung', 'name' => 'Samsung 990 Pro 2TB NVMe SSD', 'slug' => 'samsung-990-pro-2tb',
                'component_type' => 'storage',
                'description' => 'Ultra-fast PCIe 4.0 NVMe SSD delivering sequential read speeds up to 7,450 MB/s for gaming and creative workloads.',
                'specifications' => ['Capacity' => '2TB', 'Interface' => 'PCIe 4.0 x4, NVMe 2.0', 'Sequential Read' => '7,450 MB/s', 'Sequential Write' => '6,900 MB/s', 'Form Factor' => 'M.2 2280', 'Endurance' => '1,200 TBW'],
                'price' => 3299000, 'stock' => 30, 'image' => 'products/samsung-990-pro-2tb.jpg', 'is_trending' => false, 'view_count' => 210, 'sold_count' => 75,
            ],
            [
                'category_id' => 3, 'brand' => 'Corsair', 'name' => 'Corsair Vengeance DDR5 32GB Kit', 'slug' => 'corsair-vengeance-ddr5-32gb',
                'component_type' => 'ram', 'memory_type' => 'DDR5',
                'description' => 'High-performance DDR5 memory kit (2x16GB) at 5600MHz with Intel XMP 3.0 support and sleek heat spreader design.',
                'specifications' => ['Capacity' => '32GB (2x16GB)', 'Speed' => 'DDR5-5600MHz', 'Latency' => 'CL36', 'Voltage' => '1.25V', 'Compatibility' => 'Intel XMP 3.0, AMD EXPO'],
                'price' => 1899000, 'stock' => 3, 'image' => 'products/corsair-vengeance-ddr5.jpg', 'is_trending' => false, 'view_count' => 150, 'sold_count' => 88,
            ],
            [
                'category_id' => 3, 'brand' => 'ASUS', 'name' => 'ASUS ROG Strix B650E-F Motherboard', 'slug' => 'asus-rog-strix-b650e-f',
                'component_type' => 'motherboard', 'socket_type' => 'AM5', 'chipset' => 'B650E', 'memory_type' => 'DDR5', 'form_factor' => 'ATX',
                'description' => 'High-end AM5 motherboard with PCIe 5.0, DDR5, WiFi 6E, and 16+2 power stages for AMD Ryzen 7000 processors.',
                'specifications' => ['Socket' => 'AMD AM5', 'Chipset' => 'B650E', 'RAM' => '4x DDR5 up to 6400MHz', 'Storage' => '3x M.2, 4x SATA III', 'Networking' => 'WiFi 6E + 2.5Gb LAN', 'Form Factor' => 'ATX'],
                'price' => 4799000, 'stock' => 10, 'image' => 'products/asus-rog-strix-b650e.jpg', 'is_trending' => false, 'view_count' => 175, 'sold_count' => 35,
            ],
            [
                'category_id' => 3, 'brand' => 'Corsair', 'name' => 'Corsair RM850x 850W PSU', 'slug' => 'corsair-rm850x-850w',
                'component_type' => 'psu', 'tdp_watts' => 850,
                'description' => 'Fully modular 80+ Gold power supply with zero-RPM fan mode, premium Japanese capacitors, and ATX 3.0 support.',
                'specifications' => ['Wattage' => '850W', 'Efficiency' => '80+ Gold', 'Modular' => 'Fully Modular', 'Fan' => '135mm Magnetic Levitation', 'Standard' => 'ATX 3.0, PCIe 5.0', 'Warranty' => '10 Years'],
                'price' => 2199000, 'stock' => 15, 'image' => 'products/corsair-rm850x.jpg', 'is_trending' => false, 'view_count' => 120, 'sold_count' => 45,
            ],

            // ── Peripherals (category_id: 4) ──
            [
                'category_id' => 4, 'brand' => 'Logitech', 'name' => 'Logitech G Pro X Superlight 2', 'slug' => 'logitech-g-pro-x-superlight-2',
                'description' => 'Ultra-lightweight wireless gaming mouse at just 60g with HERO 2 sensor, 44-hour battery life, and competition-grade precision.',
                'specifications' => ['Sensor' => 'HERO 2 (32K DPI)', 'Weight' => '60g', 'Battery' => '95 hours', 'Connectivity' => 'LIGHTSPEED Wireless', 'Switches' => 'LIGHTFORCE Hybrid', 'Polling Rate' => '2000Hz (optional)'],
                'price' => 2299000, 'stock' => 35, 'image' => 'products/logitech-gpro-superlight2.jpg', 'is_trending' => true, 'view_count' => 390, 'sold_count' => 120,
            ],
            [
                'category_id' => 4, 'brand' => 'Keychron', 'name' => 'Keychron Q1 Pro Mechanical Keyboard', 'slug' => 'keychron-q1-pro',
                'description' => 'Premium wireless mechanical keyboard with full aluminum body, hot-swappable switches, gasket mount design, and QMK/VIA support.',
                'specifications' => ['Layout' => '75% (84 keys)', 'Switches' => 'Gateron Jupiter Banana', 'Connectivity' => 'Bluetooth 5.1 / USB-C / 2.4GHz', 'Battery' => '4000mAh', 'Backlight' => 'South-facing RGB', 'Mount' => 'Gasket'],
                'price' => 3599000, 'stock' => 20, 'image' => 'products/keychron-q1-pro.jpg', 'is_trending' => false, 'view_count' => 175, 'sold_count' => 42,
            ],
            [
                'category_id' => 4, 'brand' => 'LG', 'name' => 'LG UltraGear 27GP850-B 27" Monitor', 'slug' => 'lg-ultragear-27gp850b',
                'description' => '27-inch QHD Nano IPS gaming monitor with 165Hz refresh rate, 1ms response time, and NVIDIA G-Sync / AMD FreeSync Premium.',
                'specifications' => ['Panel' => '27" QHD (2560x1440) Nano IPS', 'Refresh Rate' => '165Hz (OC 180Hz)', 'Response Time' => '1ms GtG', 'HDR' => 'VESA DisplayHDR 400', 'Sync' => 'G-Sync Compatible / FreeSync Premium', 'Ports' => '2x HDMI 2.0, 1x DP 1.4, USB 3.0 Hub'],
                'price' => 6199000, 'stock' => 10, 'image' => 'products/lg-ultragear-27gp850b.jpg', 'is_trending' => false, 'view_count' => 230, 'sold_count' => 35,
            ],
            [
                'category_id' => 4, 'brand' => 'Samsung', 'name' => 'Samsung Odyssey G7 32" Curved Monitor', 'slug' => 'samsung-odyssey-g7-32',
                'description' => '32-inch curved QHD gaming monitor with 240Hz refresh rate, 1000R curvature, and Quantum Dot VA panel for deep blacks and vivid colors.',
                'specifications' => ['Panel' => '32" QHD (2560x1440) VA Quantum Dot', 'Refresh Rate' => '240Hz', 'Response Time' => '1ms GtG', 'HDR' => 'VESA DisplayHDR 600', 'Curvature' => '1000R', 'Ports' => '2x HDMI 2.0, 1x DP 1.4, USB Hub'],
                'price' => 8499000, 'stock' => 6, 'image' => 'products/samsung-odyssey-g7-32.jpg', 'is_trending' => true, 'view_count' => 340, 'sold_count' => 22,
            ],

            // ── Accessories (category_id: 5) ──
            [
                'category_id' => 5, 'brand' => 'HyperX', 'name' => 'HyperX Cloud III Wireless Headset', 'slug' => 'hyperx-cloud-iii-wireless',
                'description' => 'Premium wireless gaming headset with DTS Headphone:X Spatial Audio, 53mm drivers, and up to 120 hours of battery life.',
                'specifications' => ['Driver' => '53mm with Neodymium magnets', 'Frequency' => '10Hz - 21kHz', 'Battery' => 'Up to 120 hours', 'Connectivity' => '2.4GHz Wireless USB-C dongle', 'Microphone' => 'Detachable noise-cancelling', 'Surround' => 'DTS Headphone:X'],
                'price' => 2499000, 'stock' => 25, 'image' => 'products/hyperx-cloud-iii-wireless.jpg', 'is_trending' => false, 'view_count' => 145, 'sold_count' => 58,
            ],
            [
                'category_id' => 5, 'brand' => 'Baseus', 'name' => 'Baseus 100W USB-C Laptop Charger', 'slug' => 'baseus-100w-usb-c-charger',
                'description' => 'Compact GaN charger with 100W USB-C PD output, perfect for charging laptops, tablets, and phones simultaneously.',
                'specifications' => ['Total Output' => '100W', 'Ports' => '2x USB-C, 1x USB-A', 'Technology' => 'GaN III', 'Input' => '100-240V~50/60Hz', 'Weight' => '195g', 'Cable' => '1.5m USB-C cable included'],
                'price' => 599000, 'stock' => 50, 'image' => 'products/baseus-100w-charger.jpg', 'is_trending' => false, 'view_count' => 88, 'sold_count' => 140,
            ],
            [
                'category_id' => 5, 'brand' => 'NZXT', 'name' => 'NZXT Puck Headset Mount', 'slug' => 'nzxt-puck-headset-mount',
                'description' => 'Magnetic headset mount that attaches to any steel PC case. Includes cable management channel for a clean desk setup.',
                'specifications' => ['Material' => 'Silicone + Magnet', 'Compatibility' => 'Any steel surface', 'Cable Management' => 'Built-in channel', 'Colors' => 'Black, White, Purple', 'Weight' => '92g'],
                'price' => 349000, 'stock' => 1, 'image' => 'products/nzxt-puck.jpg', 'is_trending' => false, 'view_count' => 45, 'sold_count' => 30,
            ],
            [
                'category_id' => 5, 'brand' => 'Razer', 'name' => 'Razer Laptop Stand Chroma V2', 'slug' => 'razer-laptop-stand-chroma-v2',
                'description' => 'Ergonomic aluminum laptop stand with Razer Chroma RGB underglow, USB-C hub (3x USB-A, 1x USB-C), and anti-slip feet.',
                'specifications' => ['Material' => 'Anodized Aluminum', 'Ports' => '3x USB-A 3.2, 1x USB-C', 'RGB' => 'Razer Chroma (16.8M colors)', 'Max Laptop Size' => 'Up to 17"', 'Angle' => '18 degree ergonomic tilt', 'Weight' => '680g'],
                'price' => 1899000, 'stock' => 12, 'image' => 'products/razer-laptop-stand-chroma.jpg', 'is_trending' => false, 'view_count' => 110, 'sold_count' => 18,
            ],
        ];

        // Flag PC Components category
        \App\Models\Category::where('id', 3)->update(['is_pc_component' => true]);

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );
        }
    }
}
