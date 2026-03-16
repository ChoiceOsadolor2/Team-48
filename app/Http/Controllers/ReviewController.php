<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Review;
use App\Support\InputSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function context(OrderItem $orderItem): JsonResponse
    {
        $orderItem->loadMissing(['order', 'product', 'review']);

        if (!$orderItem->order || $orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $this->canReviewOrderItem($orderItem)) {
            abort(403);
        }

        return response()->json([
            'order_item_id' => $orderItem->id,
            'product_name' => $orderItem->product->name ?? 'Unknown Product',
            'platform' => $orderItem->platform ?: ($orderItem->product->platform ?? 'Universal'),
            'already_reviewed' => (bool) $orderItem->review,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->merge([
            'title' => InputSanitizer::singleLine($request->input('title')),
            'message' => InputSanitizer::multiLine($request->input('message')),
        ]);

        $data = $request->validate([
            'order_item_id' => ['required', 'integer', 'exists:order_items,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $orderItem = OrderItem::with(['order', 'product', 'review'])->findOrFail($data['order_item_id']);

        if (!$orderItem->order || $orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $this->canReviewOrderItem($orderItem)) {
            abort(403);
        }

        if ($orderItem->review) {
            return response()->json([
                'message' => 'You have already reviewed this item.',
            ], 409);
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'product_id' => $orderItem->product_id,
            'order_item_id' => $orderItem->id,
            'platform' => $orderItem->platform ?: ($orderItem->product->platform ?? 'Universal'),
            'rating' => (int) $data['rating'],
            'title' => $data['title'],
            'message' => $data['message'],
        ]);

        return response()->json([
            'success' => true,
            'review_id' => $review->id,
        ]);
    }

    private function canReviewOrderItem(OrderItem $orderItem): bool
    {
        $status = strtolower((string) optional($orderItem->order)->status);

        return in_array($status, ['completed', 'delivered'], true);
    }
}
