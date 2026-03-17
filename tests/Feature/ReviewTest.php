<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_fetch_review_context_for_their_purchased_item(): void
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

        $response = $this
            ->actingAs($user)
            ->get(route('reviews.context', $orderItem));

        $response
            ->assertOk()
            ->assertJson([
                'order_item_id' => $orderItem->id,
                'product_name' => $product->name,
                'platform' => 'PlayStation 5',
                'already_reviewed' => false,
            ]);
    }

    public function test_user_cannot_fetch_review_context_for_another_users_item(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::create([
            'user_id' => $owner->id,
            'total' => 69.99,
            'status' => 'completed',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 69.99,
            'platform' => 'Xbox One',
        ]);

        $this->actingAs($otherUser)
            ->get(route('reviews.context', $orderItem))
            ->assertForbidden();
    }

    public function test_user_can_store_review_for_their_purchased_item(): void
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

        $response = $this
            ->actingAs($user)
            ->postJson(route('reviews.store'), [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'title' => 'Great game',
                'message' => 'Really enjoyed this one.',
            ]);

        $response
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'order_item_id' => $orderItem->id,
            'product_id' => $product->id,
            'platform' => 'Xbox Series X/S',
            'rating' => 5,
            'title' => 'Great game',
        ]);
    }

    public function test_user_cannot_store_review_for_another_users_purchase(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::create([
            'user_id' => $owner->id,
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

        $this->actingAs($otherUser)
            ->postJson(route('reviews.store'), [
                'order_item_id' => $orderItem->id,
                'rating' => 4,
                'title' => 'Blocked',
                'message' => 'This should not save.',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_same_order_item_cannot_be_reviewed_twice(): void
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

        Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_item_id' => $orderItem->id,
            'platform' => 'Universal',
            'rating' => 5,
            'title' => 'First',
            'message' => 'Already reviewed.',
        ]);

        $this->actingAs($user)
            ->postJson(route('reviews.store'), [
                'order_item_id' => $orderItem->id,
                'rating' => 3,
                'title' => 'Second',
                'message' => 'Should be rejected.',
            ])
            ->assertStatus(409);
    }

    public function test_product_reviews_endpoint_returns_saved_reviews(): void
    {
        $user = User::factory()->create(['name' => 'Review User']);
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
            'platform' => 'PlayStation 5',
        ]);

        Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_item_id' => $orderItem->id,
            'platform' => 'PlayStation 5',
            'rating' => 5,
            'title' => 'Excellent',
            'message' => 'Worth every penny.',
        ]);

        $this->get(route('products.reviews', $product))
            ->assertOk()
            ->assertJson([
                'success' => true,
                'reviews' => [[
                    'user_name' => 'Review User',
                    'platform' => 'PlayStation 5',
                    'rating' => 5,
                    'title' => 'Excellent',
                    'message' => 'Worth every penny.',
                ]],
            ]);
    }

    public function test_user_cannot_fetch_review_context_for_processing_order(): void
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
            ->get(route('reviews.context', $orderItem))
            ->assertForbidden();
    }

    public function test_user_cannot_store_review_for_cancelled_order(): void
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
            ->postJson(route('reviews.store'), [
                'order_item_id' => $orderItem->id,
                'rating' => 5,
                'title' => 'Blocked',
                'message' => 'This should not save.',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('reviews', 0);
    }
}
