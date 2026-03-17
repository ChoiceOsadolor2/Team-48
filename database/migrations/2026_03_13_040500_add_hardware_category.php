<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('categories')->where('slug', 'hardware')->exists();

        if (! $exists) {
            DB::table('categories')->insert([
                'name' => 'Hardware',
                'slug' => 'hardware',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('categories')->where('slug', 'hardware')->delete();
    }
};
