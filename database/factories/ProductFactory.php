<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->words(3, true),
            'slug' => Str::slug($this->faker->unique()->words(3, true)),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 5, 200),
            'stock' => $this->faker->numberBetween(1, 50),
            'platform' => $this->faker->randomElement(['PC', 'PlayStation', 'Xbox', 'Switch']),
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}
