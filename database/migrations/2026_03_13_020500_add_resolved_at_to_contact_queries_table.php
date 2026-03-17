<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('contact_queries')) {
            return;
        }

        Schema::table('contact_queries', function (Blueprint $table) {
            if (!Schema::hasColumn('contact_queries', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('message');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('contact_queries') || !Schema::hasColumn('contact_queries', 'resolved_at')) {
            return;
        }

        Schema::table('contact_queries', function (Blueprint $table) {
            $table->dropColumn('resolved_at');
        });
    }
};
