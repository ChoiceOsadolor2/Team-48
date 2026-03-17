<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\ServiceReview;
use App\Support\InputSanitizer;
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
                    'rating' => (float) $review->rating,
                    'title' => $review->title,
                    'message' => $review->message,
                    'created_at' => optional($review->created_at)->format('M d Y'),
                ];
            })->values(),
        ]);
    }

    public function context(OrderItem $orderItem): JsonResponse
    {
        $orderItem->loadMissing(['order', 'product']);

        if (! $orderItem->order || $orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $this->canReviewOrderItem($orderItem)) {
            abort(403);
        }

        return response()->json([
            'order_item_id' => $orderItem->id,
            'user_name' => Auth::user()?->name ?? 'Veltrix customer',
            'already_reviewed' => ServiceReview::query()
                ->where('user_id', Auth::id())
                ->where('order_id', $orderItem->order_id)
                ->exists(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'message' => 'Please sign in to leave a review.',
            ], 401);
        }

        $request->merge([
            'message' => InputSanitizer::multiLine($request->input('message')),
        ]);

        $data = $request->validate([
            'order_item_id' => ['required', 'integer', 'exists:order_items,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $orderItem = OrderItem::with(['order', 'product'])->findOrFail($data['order_item_id']);

        if (! $orderItem->order || $orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $this->canReviewOrderItem($orderItem)) {
            abort(403);
        }

        if (ServiceReview::query()
            ->where('user_id', Auth::id())
            ->where('order_id', $orderItem->order_id)
            ->exists()) {
            return response()->json([
                'message' => 'You have already submitted a service review for this order.',
            ], 409);
        }

        $review = ServiceReview::create([
            'user_id' => Auth::id(),
            'order_id' => $orderItem->order_id,
            'rating' => (int) $data['rating'],
            'title' => 'Service Review',
            'message' => $data['message'],
        ]);

        $review->loadMissing('user:id,name');

        return response()->json([
            'success' => true,
            'message' => 'Service review submitted.',
            'review' => [
                'id' => $review->id,
                'user_name' => $review->user?->name ?? 'Veltrix customer',
                'rating' => (float) $review->rating,
                'title' => $review->title,
                'message' => $review->message,
                'created_at' => optional($review->created_at)->format('M d Y'),
            ],
        ]);
    }

    private function canReviewOrderItem(OrderItem $orderItem): bool
    {
        $status = strtolower((string) optional($orderItem->order)->status);

        return in_array($status, ['completed', 'delivered'], true);
    }
}
