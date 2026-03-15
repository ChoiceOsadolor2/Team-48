<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $categories = [
            ['name' => 'Furniture', 'slug' => 'furniture'],
            ['name' => 'Merchandise', 'slug' => 'merchandise'],
            ['name' => 'Trading Cards', 'slug' => 'trading-cards'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('categories')
            ->whereIn('slug', ['furniture', 'merchandise', 'trading-cards'])
            ->delete();
    }
};
