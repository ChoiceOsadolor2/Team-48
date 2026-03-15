<?php

namespace Tests\Feature;

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

        ServiceReview::create([
            'user_id' => $user->id,
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
            'rating' => 5,
            'title' => 'Blocked',
            'message' => 'Guests should not be able to post.',
        ])
            ->assertStatus(401);

        $this->assertDatabaseCount('service_reviews', 0);
    }

    public function test_signed_in_user_can_submit_service_review(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/service-reviews', [
                'rating' => 4,
                'title' => 'Great support',
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
            'title' => 'Great support',
        ]);
    }

    public function test_signed_in_user_updates_existing_service_review(): void
    {
        $user = User::factory()->create();

        ServiceReview::create([
            'user_id' => $user->id,
            'rating' => 3,
            'title' => 'Old review',
            'message' => 'Old message.',
        ]);

        $this->actingAs($user)
            ->postJson('/service-reviews', [
                'rating' => 5,
                'title' => 'Updated review',
                'message' => 'Much better now.',
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Service review updated.',
            ]);

        $this->assertDatabaseCount('service_reviews', 1);
        $this->assertDatabaseHas('service_reviews', [
            'user_id' => $user->id,
            'rating' => 5,
            'title' => 'Updated review',
            'message' => 'Much better now.',
        ]);
    }
}
