<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::create([
            'name' => 'PC Games',
            'slug' => 'pc-games',
            'description' => 'PC gaming category',
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Elden Ring',
            'slug' => 'elden-ring',
            'description' => 'Fantasy action RPG',
            'price' => 49.99,
            'stock' => 10,
            'platform' => 'PC',
            'image_url' => 'https://example.com/elden-ring.jpg',
        ]);
    }
}
