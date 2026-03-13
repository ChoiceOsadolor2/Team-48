<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected array $shopCategoryMap = [
        'Video Games' => ['Video Games'],
        'Consoles and PCs' => ['Consoles and PCs'],
        'Accessories' => ['Accessories'],
        'Gaming Chairs and Desks' => ['Gaming Chairs and Desks'],
        'Monitors and Displays' => ['Monitors and Displays'],
        'Hardware' => ['Gaming Chairs and Desks', 'Monitors and Displays'],
    ];

    // GET products
    public function index(Request $request)
    {
        $q = Product::with('category');

        $this->applyFilters($q, $request);

        return response()->json([
            'success' => true,
            'products' => $q->get(),
            'filters' => [
                'category' => $request->query('category'),
                'q' => trim((string) $request->query('q', '')),
                'availability' => $request->query('availability'),
                'min_price' => $request->query('min_price'),
                'max_price' => $request->query('max_price'),
                'sort' => $request->query('sort', 'default'),
            ],
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
        $term = trim((string) $request->input('q', ''));

        if ($term === '') {
            return response()->json([
                'success' => false,
                'message' => 'No search term provided',
            ], 400);
        }

        $query = Product::with('category');
        $this->applyFilters($query, $request, true);

        return response()->json([
            'success' => true,
            'results' => $query->limit(12)->get(),
        ]);
    }

    protected function applyFilters($query, Request $request, bool $forceTerm = false): void
    {
        $term = trim((string) $request->input('q', ''));
        $category = trim((string) $request->input('category', ''));
        $availability = trim((string) $request->input('availability', ''));
        $sort = trim((string) $request->input('sort', 'default'));

        if ($term !== '' || $forceTerm) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('platform', 'like', "%{$term}%");
            });
        }

        if ($category !== '') {
            $names = $this->shopCategoryMap[$category] ?? [$category];

            $query->whereHas('category', function ($q) use ($category, $names) {
                $q->whereIn('name', $names)
                    ->orWhere('slug', $category);
            });
        }

        if ($request->filled('min_price') && is_numeric($request->input('min_price'))) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price') && is_numeric($request->input('max_price'))) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        if ($availability === 'in_stock') {
            $query->where('stock', '>', 0);
        } elseif ($availability === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        }

        switch ($sort) {
            case 'price_low':
                $query->orderBy('price');
                break;
            case 'price_high':
                $query->orderByDesc('price');
                break;
            case 'name_asc':
                $query->orderBy('name');
                break;
            case 'name_desc':
                $query->orderByDesc('name');
                break;
            case 'newest':
                $query->orderByDesc('created_at');
                break;
            case 'stock_high':
                $query->orderByDesc('stock');
                break;
            default:
                $query->latest();
                break;
        }
    }
}
