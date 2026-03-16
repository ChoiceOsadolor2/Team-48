<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function view()
    {
        return view('wishlist.index');
    }

    public function index(): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'authenticated' => false,
                'product_ids' => [],
            ]);
        }

        return response()->json([
            'authenticated' => true,
            'product_ids' => WishlistItem::query()
                ->where('user_id', Auth::id())
                ->orderBy('product_id')
                ->pluck('product_id')
                ->map(fn ($id) => (int) $id)
                ->values(),
        ]);
    }

    public function store(Product $product): JsonResponse
    {
        $wishlistItem = WishlistItem::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        return response()->json([
            'success' => true,
            'wishlisted' => true,
            'wishlist_item_id' => $wishlistItem->id,
            'product_id' => $product->id,
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        WishlistItem::query()
            ->where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return response()->json([
            'success' => true,
            'wishlisted' => false,
            'product_id' => $product->id,
        ]);
    }

    public function products(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'products' => WishlistItem::query()
                ->where('user_id', Auth::id())
                ->with(['product.category'])
                ->latest()
                ->get()
                ->map(function (WishlistItem $item) {
                    $product = $item->product;

                    if (! $product) {
                        return null;
                    }

                    return [
                        'wishlist_item_id' => $item->id,
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'platform' => $product->platform,
                        'image_url' => $product->image_url,
                        'category' => [
                            'name' => $product->category?->name,
                        ],
                    ];
                })
                ->filter()
                ->values(),
        ]);
    }
}
