<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET products
    public function index(Request $request)
    {
        $catSlug = $request->query('category'); // ?category=pc-games

        $q = Product::with('category'); // eager load category

        if ($catSlug) {
            $q->whereHas('category', function ($query) use ($catSlug) {
                $query->where('slug', $catSlug);
            });
        }

        return response()->json([
            'success' => true,
            'products' => $q->get(),
        ]);
    }

    // GET products or slug
    public function show($slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }

    // GET /search
    public function search(Request $request)
    {
        $term = $request->input('q');

        if (!$term) {
            return response()->json([
                'success' => false,
                'message' => 'No search term provided',
            ], 400);
        }

        $products = Product::with('category')
            ->where('name', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%")
            ->get();

        return response()->json([
            'success' => true,
            'results' => $products,
        ]);
    }
}
