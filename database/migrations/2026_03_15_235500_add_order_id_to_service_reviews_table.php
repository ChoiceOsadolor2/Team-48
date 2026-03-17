<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('service_reviews', 'order_id')) {
            Schema::table('service_reviews', function (Blueprint $table) {
                $table->foreignId('order_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained()
                    ->nullOnDelete();
            });
        }

        $reviews = DB::table('service_reviews')->select('id', 'user_id')->get();

        foreach ($reviews as $review) {
            $orderId = DB::table('orders')
                ->where('user_id', $review->user_id)
                ->whereIn('status', ['completed', 'delivered'])
                ->orderByDesc('created_at')
                ->value('id');

            if ($orderId) {
                DB::table('service_reviews')
                    ->where('id', $review->id)
                    ->update(['order_id' => $orderId]);
            }
        }

        DB::table('service_reviews')
            ->whereNull('order_id')
            ->delete();

        if (! $this->hasIndex('service_reviews', 'service_reviews_user_id_index')) {
            Schema::table('service_reviews', function (Blueprint $table) {
                $table->index('user_id', 'service_reviews_user_id_index');
            });
        }

        if ($this->hasIndex('service_reviews', 'service_reviews_user_id_unique')) {
            Schema::table('service_reviews', function (Blueprint $table) {
                $table->dropUnique('service_reviews_user_id_unique');
            });
        }

        if (! $this->hasIndex('service_reviews', 'service_reviews_user_id_order_id_unique')) {
            Schema::table('service_reviews', function (Blueprint $table) {
                $table->unique(['user_id', 'order_id']);
            });
        }
    }

    public function down(): void
    {
        if ($this->hasIndex('service_reviews', 'service_reviews_user_id_order_id_unique')) {
            Schema::table('service_reviews', function (Blueprint $table) {
                $table->dropUnique(['user_id', 'order_id']);
            });
        }

        if (! $this->hasIndex('service_reviews', 'service_reviews_user_id_unique')) {
            Schema::table('service_reviews', function (Blueprint $table) {
                $table->unique('user_id');
            });
        }

        if ($this->hasIndex('service_reviews', 'service_reviews_user_id_index')) {
            Schema::table('service_reviews', function (Blueprint $table) {
                $table->dropIndex('service_reviews_user_id_index');
            });
        }

        if (Schema::hasColumn('service_reviews', 'order_id')) {
            Schema::table('service_reviews', function (Blueprint $table) {
                $table->dropConstrainedForeignId('order_id');
            });
        }
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list('{$table}')");

            foreach ($indexes as $index) {
                if (($index->name ?? null) === $indexName) {
                    return true;
                }
            }

            return false;
        }

        $indexes = DB::select("SHOW INDEX FROM {$table}");

        foreach ($indexes as $index) {
            if (($index->Key_name ?? null) === $indexName) {
                return true;
            }
        }

        return false;
    }
};
