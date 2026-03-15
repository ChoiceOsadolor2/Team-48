<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('refund_requests')) {
            Schema::create('refund_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
                $table->string('status')->default('pending');
                $table->text('reason');
                $table->timestamp('reviewed_at')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->unique('order_item_id');
                $table->index('status');
            });
        }

        if (!Schema::hasTable('contact_queries')) {
            return;
        }

        DB::table('contact_queries')
            ->where('subject', 'like', 'Refund request - Order VX-%')
            ->orderBy('id')
            ->get()
            ->each(function ($query) {
                $orderItemId = null;
                $reason = '';

                if (preg_match('/Order item:\s*#(\d+)/', (string) $query->message, $itemMatch)) {
                    $orderItemId = (int) $itemMatch[1];
                }

                if (preg_match('/Reason:\s*(.+)$/s', (string) $query->message, $reasonMatch)) {
                    $reason = trim($reasonMatch[1]);
                }

                if (! $orderItemId || $reason === '') {
                    return;
                }

                $orderItem = DB::table('order_items')
                    ->join('orders', 'orders.id', '=', 'order_items.order_id')
                    ->select('order_items.id', 'order_items.order_id', 'orders.user_id')
                    ->where('order_items.id', $orderItemId)
                    ->first();

                if (! $orderItem) {
                    return;
                }

                $exists = DB::table('refund_requests')
                    ->where('order_item_id', $orderItemId)
                    ->exists();

                if ($exists) {
                    return;
                }

                DB::table('refund_requests')->insert([
                    'user_id' => $orderItem->user_id,
                    'order_id' => $orderItem->order_id,
                    'order_item_id' => $orderItemId,
                    'status' => 'pending',
                    'reason' => $reason,
                    'reviewed_at' => null,
                    'reviewed_by' => null,
                    'created_at' => $query->created_at ?? now(),
                    'updated_at' => $query->updated_at ?? now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('refund_requests');
    }
};
