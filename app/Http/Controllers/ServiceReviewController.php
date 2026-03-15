<?php

namespace App\Http\Controllers;

use App\Models\ServiceReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ServiceReviewController extends Controller
{
    public function index(): JsonResponse
    {
        if (! Schema::hasTable('service_reviews')) {
            return response()->json([
                'success' => true,
                'reviews' => [],
            ]);
        }

        $reviews = ServiceReview::query()
            ->with('user:id,name')
            ->latest()
            ->take(12)
            ->get();

        return response()->json([
            'success' => true,
            'reviews' => $reviews->map(function (ServiceReview $review) {
                return [
                    'id' => $review->id,
                    'user_name' => $review->user?->name ?? 'Veltrix customer',
                    'rating' => (int) $review->rating,
                    'title' => $review->title,
                    'message' => $review->message,
                    'created_at' => optional($review->created_at)->format('M d Y'),
                ];
            })->values(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'message' => 'Please sign in to leave a review.',
            ], 401);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $review = ServiceReview::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'rating' => (int) $data['rating'],
                'title' => trim($data['title']),
                'message' => trim($data['message']),
            ]
        );

        $review->loadMissing('user:id,name');

        return response()->json([
            'success' => true,
            'message' => $review->wasRecentlyCreated
                ? 'Service review submitted.'
                : 'Service review updated.',
            'review' => [
                'id' => $review->id,
                'user_name' => $review->user?->name ?? 'Veltrix customer',
                'rating' => (int) $review->rating,
                'title' => $review->title,
                'message' => $review->message,
                'created_at' => optional($review->created_at)->format('M d Y'),
            ],
        ]);
    }
}
