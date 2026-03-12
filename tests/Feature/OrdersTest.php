<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_cancel_their_processing_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 30,
            'status' => 'processing',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 15,
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/orders')
            ->post(route('orders.cancel', $order));

        $response
            ->assertRedirect('/orders')
            ->assertSessionHas('status', 'Order cancelled successfully.');

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(7, $product->fresh()->stock);
    }

    public function test_user_cannot_cancel_non_processing_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 30,
            'status' => 'delivered',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 15,
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/orders')
            ->post(route('orders.cancel', $order));

        $response
            ->assertRedirect('/orders')
            ->assertSessionHas('status', 'Only processing orders can be cancelled.');

        $this->assertSame('delivered', $order->fresh()->status);
        $this->assertSame(5, $product->fresh()->stock);
    }
}
