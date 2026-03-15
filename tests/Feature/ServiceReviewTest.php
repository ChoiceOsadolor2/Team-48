<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ServiceReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_reviews_index_returns_saved_reviews(): void
    {
        $user = User::factory()->create(['name' => 'Jamie Player']);
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 69.99,
            'status' => 'completed',
        ]);

        ServiceReview::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'rating' => 5,
            'title' => 'Amazing service',
            'message' => 'Everything arrived quickly and support was great.',
        ]);

        $this->getJson('/service-reviews')
            ->assertOk()
            ->assertJson([
                'success' => true,
                'reviews' => [[
                    'user_name' => 'Jamie Player',
                    'rating' => 5,
                    'title' => 'Amazing service',
                    'message' => 'Everything arrived quickly and support was great.',
                ]],
            ]);
    }

    public function test_guest_cannot_submit_service_review(): void
    {
        $this->postJson('/service-reviews', [
            'order_item_id' => 999,
            'rating' => 5,
            'title' => 'Blocked',
            'message' => 'Guests should not be able to post.',
        ])
            ->assertStatus(401);

        $this->assertDatabaseCount('service_reviews', 0);
    }

    public function test_user_can_fetch_service_review_context_for_completed_order_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['platform' => 'PlayStation 5']);
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 69.99,
            'status' => 'completed',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 69.99,
            'platform' => 'PlayStation 5',
        ]);

        $this->actingAs($user)
            ->get(route('service-reviews.context', $orderItem))
            ->assertOk()
            ->assertJson([
                'order_item_id' => $orderItem->id,
                'user_name' => $user->name,
                'already_reviewed' => false,
            ]);
    }

    public function test_user_cannot_fetch_service_review_context_for_processing_order_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 69.99,
            'status' => 'processing',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 69.99,
            'platform' => 'Universal',
        ]);

        $this->actingAs($user)
            ->get(route('service-reviews.context', $orderItem))
            ->assertForbidden();
    }

    public function test_signed_in_user_can_submit_service_review_for_completed_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 69.99,
            'status' => 'completed',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 69.99,
            'platform' => 'Xbox Series X/S',
        ]);

        $this->actingAs($user)
            ->postJson('/service-reviews', [
                'order_item_id' => $orderItem->id,
                'rating' => 4,
                'message' => 'The whole experience felt polished.',
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Service review submitted.',
            ]);

        $this->assertDatabaseHas('service_reviews', [
            'user_id' => $user->id,
            'rating' => 4,
            'title' => 'Service Review',
        ]);
    }

    public function test_user_cannot_submit_service_review_for_cancelled_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 69.99,
            'status' => 'cancelled',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 69.99,
            'platform' => 'Universal',
        ]);

        $this->actingAs($user)
            ->postJson('/service-reviews', [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'message' => 'This should not save.',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('service_reviews', 0);
    }

    public function test_user_cannot_submit_service_review_twice(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total' => 69.99,
            'status' => 'completed',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 69.99,
            'platform' => 'Universal',
        ]);

        ServiceReview::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'rating' => 3,
            'title' => 'Old review',
            'message' => 'Old message.',
        ]);

        $this->actingAs($user)
            ->postJson('/service-reviews', [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'message' => 'Should be rejected.',
            ])
            ->assertStatus(409);

        $this->assertDatabaseCount('service_reviews', 1);
        $this->assertDatabaseHas('service_reviews', [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'rating' => 3,
            'title' => 'Old review',
            'message' => 'Old message.',
        ]);
    }

    public function test_user_can_submit_one_service_review_for_each_completed_order(): void
    {
        $user = User::factory()->create();
        $productA = Product::factory()->create();
        $productB = Product::factory()->create();

        $firstOrder = Order::create([
            'user_id' => $user->id,
            'total' => 69.99,
            'status' => 'completed',
        ]);

        $secondOrder = Order::create([
            'user_id' => $user->id,
            'total' => 89.99,
            'status' => 'completed',
        ]);

        $firstOrderItem = OrderItem::create([
            'order_id' => $firstOrder->id,
            'product_id' => $productA->id,
            'quantity' => 1,
            'price' => 69.99,
            'platform' => 'Universal',
        ]);

        $secondOrderItem = OrderItem::create([
            'order_id' => $secondOrder->id,
            'product_id' => $productB->id,
            'quantity' => 1,
            'price' => 89.99,
            'platform' => 'Universal',
        ]);

        $this->actingAs($user)
            ->postJson('/service-reviews', [
                'order_item_id' => $firstOrderItem->id,
                'rating' => 5,
                'message' => 'First order was great.',
            ])
            ->assertOk();

        $this->actingAs($user)
            ->postJson('/service-reviews', [
                'order_item_id' => $secondOrderItem->id,
                'rating' => 4,
                'message' => 'Second order was great too.',
            ])
            ->assertOk();

        $this->assertDatabaseCount('service_reviews', 2);
        $this->assertDatabaseHas('service_reviews', [
            'user_id' => $user->id,
            'order_id' => $firstOrder->id,
            'message' => 'First order was great.',
        ]);
        $this->assertDatabaseHas('service_reviews', [
            'user_id' => $user->id,
            'order_id' => $secondOrder->id,
            'message' => 'Second order was great too.',
        ]);
    }
}
