<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Video Games', 'slug' => 'video-games'],
            ['name' => 'Consoles and PCs', 'slug' => 'consoles-and-pcs'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
            ['name' => 'Monitors and Displays', 'slug' => 'monitors-and-displays'],
            ['name' => 'Gaming Chairs and Desks', 'slug' => 'gaming-chairs-and-desks'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name']]
            );
        }
    }
}
