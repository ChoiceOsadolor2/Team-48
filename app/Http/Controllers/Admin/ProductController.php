<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function updateStock(Request $request, Product $product)
    {
        $data = $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update([
            'stock' => $data['stock'],
        ]);

        return redirect()->route('admin.products.index', array_filter([
            'q' => $request->input('q'),
            'stock' => $request->input('filter_stock'),
            'category' => $request->input('category_filter'),
        ], fn ($value) => $value !== null && $value !== ''))
            ->with('status', 'Stock updated for ' . $product->name . '.');
    }

    public function bulkAction(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', 'in:delete'],
            'selected' => ['required', 'array', 'min:1'],
            'selected.*' => ['integer', 'exists:products,id'],
        ]);

        $selectedIds = array_unique($data['selected']);

        Product::query()->whereIn('id', $selectedIds)->delete();

        return redirect()->route('admin.products.index')
            ->with('status', count($selectedIds) . ' products deleted successfully.');
    }

    public function index(Request $request)
{
    $categoryKey = $request->query('category');
    $search = trim((string) $request->query('q', ''));
    $stock = trim((string) $request->query('stock', ''));

    $categoryNames = [
        'Games' => [
            'Video Games',
        ],
        'Consoles and PCs' => [
            'Consoles and PCs',
        ],
        'Accessories' => [
            'Accessories',
        ],
        'Hardware' => [
            'Gaming Chairs and Desks',
            'Monitors and Displays',
        ],
    ];

    $query = Product::with('category')->orderBy('created_at', 'desc');

    if ($categoryKey && isset($categoryNames[$categoryKey])) {
        $names = $categoryNames[$categoryKey];

        $query->whereHas('category', function ($q) use ($names) {
            $q->whereIn('name', $names);
        });
    }

    if ($search !== '') {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('platform', 'like', "%{$search}%");
        });
    }

    if ($stock === 'in_stock') {
        $query->where('stock', '>', 0);
    } elseif ($stock === 'out_of_stock') {
        $query->where('stock', '<=', 0);
    } elseif ($stock === 'low_stock') {
        $query->where('stock', '>', 0)->where('stock', '<=', 5);
    }

    $products = $query->get();

    return view('admin.products.index', [
        'products'    => $products,
        'categoryKey' => $categoryKey,
        'search'      => $search,
        'stockFilter' => $stock,
    ]);
}

    public function stock()
    {
        $products = Product::with('category')
            ->orderByRaw('CASE WHEN stock = 0 THEN 0 ELSE 1 END')
            ->orderBy('stock')
            ->orderBy('name')
            ->get();

        return view('admin.products.stock', [
            'products' => $products,
            'inStockCount' => $products->where('stock', '>', 0)->count(),
            'outOfStockCount' => $products->where('stock', '<=', 0)->count(),
        ]);
    }


    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
    $data = $request->validate([
        'category_id' => ['required', 'exists:categories,id'],
        'name'        => ['required', 'string', 'max:255'],
        'description' => ['required', 'string'],
        'price'       => ['required', 'numeric', 'min:0'],
        'stock'       => ['required', 'integer', 'min:0'],
        'platform'    => ['nullable', 'string', 'max:255'],
        'image'       => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
    ]);

    $data['slug'] = Str::slug($data['name']);

    if ($request->hasFile('image')) {
        $data['image_url'] = $request->file('image')->store('products', 'public');
    } else {
        $data['image_url'] = null;
    }

    Product::create($data);

    return redirect()->route('admin.products.index')
        ->with('status', 'Product created successfully.');
}


    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
{
    $data = $request->validate([
        'category_id' => ['required', 'exists:categories,id'],
        'name'        => ['required', 'string', 'max:255'],
        'description' => ['required', 'string'],
        'price'       => ['required', 'numeric', 'min:0'],
        'stock'       => ['required', 'integer', 'min:0'],
        'platform'    => ['nullable', 'string', 'max:255'],
        'image'       => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
    ]);

    $data['slug'] = Str::slug($data['name']);

    if ($request->hasFile('image')) {
        if ($product->image_url && !str_starts_with($product->image_url, 'http')) {
            Storage::disk('public')->delete($product->image_url);
        }

        $data['image_url'] = $request->file('image')->store('products', 'public');
    } else {
        unset($data['image_url']);
    }

    $product->update($data);

    return redirect()->route('admin.products.index')
        ->with('status', 'Product updated successfully.');
}


    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('status', 'Product deleted successfully.');
    }
}
