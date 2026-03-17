<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_wishlist_index_returns_empty_state(): void
    {
        $this->getJson('/wishlist/json')
            ->assertOk()
            ->assertJson([
                'authenticated' => false,
                'product_ids' => [],
            ]);
    }

    public function test_user_can_add_and_remove_wishlist_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)
            ->postJson(route('wishlist.store', $product))
            ->assertOk()
            ->assertJson([
                'success' => true,
                'wishlisted' => true,
                'product_id' => $product->id,
            ]);

        $this->assertDatabaseHas('wishlist_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user)
            ->deleteJson(route('wishlist.destroy', $product))
            ->assertOk()
            ->assertJson([
                'success' => true,
                'wishlisted' => false,
                'product_id' => $product->id,
            ]);

        $this->assertDatabaseMissing('wishlist_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_duplicate_adds_do_not_create_multiple_rows(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        WishlistItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user)
            ->postJson(route('wishlist.store', $product))
            ->assertOk();

        $this->assertDatabaseCount('wishlist_items', 1);
    }
}
