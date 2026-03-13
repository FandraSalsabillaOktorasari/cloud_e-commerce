<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'image' => 'categories/laptops.jpg',
            ],
            [
                'name' => 'Desktops',
                'slug' => 'desktops',
                'image' => 'categories/desktops.jpg',
            ],
            [
                'name' => 'PC Components',
                'slug' => 'pc-components',
                'image' => 'categories/pc-components.jpg',
            ],
            [
                'name' => 'Peripherals',
                'slug' => 'peripherals',
                'image' => 'categories/peripherals.jpg',
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'image' => 'categories/accessories.jpg',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
