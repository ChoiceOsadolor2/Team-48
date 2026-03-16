<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Support\InputSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    private function platformOptions(): array
    {
        return [
            'Universal',
            'Nintendo Switch 2',
            'Nintendo Switch',
            'PlayStation 5',
            'PlayStation 4',
            'Xbox Series X/S',
            'Xbox One',
        ];
    }

    private function normalizePlatforms(?array $platforms): ?string
    {
        $selected = collect($platforms ?? [])
            ->map(fn ($platform) => trim((string) $platform))
            ->filter()
            ->unique()
            ->values();

        return $selected->isEmpty() ? null : $selected->implode(', ');
    }

    private function normalizePlatformStockMap(?array $platforms, ?array $stockByPlatform): array
    {
        $selectedPlatforms = collect($platforms ?? [])
            ->map(fn ($platform) => trim((string) $platform))
            ->filter()
            ->unique()
            ->values();

        if ($selectedPlatforms->isEmpty()) {
            return [];
        }

        $rawStockMap = collect($stockByPlatform ?? []);

        return $selectedPlatforms
            ->mapWithKeys(function (string $platform) use ($rawStockMap) {
                return [$platform => max(0, (int) $rawStockMap->get($platform, 0))];
            })
            ->all();
    }

    private function syncPlatformStocks(Product $product, array $platformStockMap): void
    {
        $product->platformStocks()->delete();

        foreach ($platformStockMap as $platform => $stock) {
            $product->platformStocks()->create([
                'platform' => $platform,
                'stock' => $stock,
            ]);
        }
    }

    private function homepageCategoryNames(): array
    {
        return [
            'Video Games',
            'Consoles and PCs',
            'Accessories',
            'Hardware',
            'Furniture',
            'Merchandise',
            'Trading Cards',
        ];
    }

    private function adminFormCategories()
    {
        $orderedNames = $this->homepageCategoryNames();

        foreach ($orderedNames as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }

        return Category::query()
            ->whereIn('name', $orderedNames)
            ->get()
            ->sortBy(fn (Category $category) => array_search($category->name, $orderedNames, true))
            ->values();
    }

    public function updateStock(Request $request, Product $product)
    {
        if ($product->hasPlatformSpecificStock()) {
            return redirect()->route('admin.products.edit', $product)
                ->with('status', 'This product uses platform-specific stock. Update it from the edit page.');
        }

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
            'Games' => ['Video Games'],
            'Consoles and PCs' => ['Consoles and PCs'],
            'Accessories' => ['Accessories'],
            'Hardware' => ['Hardware', 'Monitors and Displays'],
            'Furniture' => ['Furniture', 'Gaming Chairs and Desks'],
            'Merchandise' => ['Merchandise'],
            'Trading Cards' => ['Trading Cards'],
        ];

        $query = Product::with(['category', 'platformStocks'])->orderByDesc('created_at');

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

        $products = $query->paginate(15)->appends($request->query());

        return view('admin.products.index', [
            'products' => $products,
            'categoryKey' => $categoryKey,
            'search' => $search,
            'stockFilter' => $stock,
        ]);
    }

    public function stock()
    {
        $products = Product::with(['category', 'platformStocks'])
            ->get()
            ->sortBy([
                fn ($product) => (int) ($product->stock !== 0),
                fn ($product) => (int) $product->stock,
                fn ($product) => mb_strtolower((string) $product->name),
            ])
            ->values();

        return view('admin.products.stock', [
            'products' => $products,
            'inStockCount' => $products->where('stock', '>', 0)->count(),
            'outOfStockCount' => $products->where('stock', '<=', 0)->count(),
        ]);
    }

    public function lowStockCenter(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $severity = trim((string) $request->query('severity', ''));

        $products = Product::with(['category', 'platformStocks'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', '%' . $search . '%')
                        ->orWhere('platform', 'like', '%' . $search . '%');
                });
            })
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->orderBy('name')
            ->get();

        if ($severity === 'critical') {
            $products = $products->where('stock', '<=', 2)->values();
        } elseif ($severity === 'warning') {
            $products = $products->whereBetween('stock', [3, 5])->values();
        } elseif ($severity === 'watch') {
            $products = $products->whereBetween('stock', [6, 10])->values();
        }

        return view('admin.products.low-stock-center', [
            'products' => $products,
            'search' => $search,
            'severity' => $severity,
            'criticalCount' => $products->where('stock', '<=', 2)->count(),
            'warningCount' => $products->whereBetween('stock', [3, 5])->count(),
            'watchCount' => $products->whereBetween('stock', [6, 10])->count(),
        ]);
    }

    public function create()
    {
        $categories = $this->adminFormCategories();
        $platformOptions = $this->platformOptions();

        return view('admin.products.create', compact('categories', 'platformOptions'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'name' => InputSanitizer::singleLine($request->input('name')),
            'description' => InputSanitizer::multiLine($request->input('description')),
        ]);

        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'platform' => ['nullable', 'array'],
            'platform.*' => ['string', Rule::in($this->platformOptions())],
            'platform_stock' => ['nullable', 'array'],
            'platform_stock.*' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        $platforms = $data['platform'] ?? null;
        $data['slug'] = Str::slug($data['name']);
        $data['platform'] = $this->normalizePlatforms($platforms);

        $platformStockMap = $this->normalizePlatformStockMap($platforms, $request->input('platform_stock', []));
        if (!empty($platformStockMap)) {
            $data['stock'] = array_sum($platformStockMap);
        }

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products', 'public');
        } else {
            $data['image_url'] = null;
        }

        $product = Product::create($data);
        $this->syncPlatformStocks($product, $platformStockMap);

        return redirect()->route('admin.products.index')
            ->with('status', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('platformStocks');
        $categories = $this->adminFormCategories();
        $platformOptions = $this->platformOptions();

        return view('admin.products.edit', compact('product', 'categories', 'platformOptions'));
    }

    public function update(Request $request, Product $product)
    {
        $request->merge([
            'name' => InputSanitizer::singleLine($request->input('name')),
            'description' => InputSanitizer::multiLine($request->input('description')),
        ]);

        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'platform' => ['nullable', 'array'],
            'platform.*' => ['string', Rule::in($this->platformOptions())],
            'platform_stock' => ['nullable', 'array'],
            'platform_stock.*' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        $platforms = $data['platform'] ?? null;
        $data['slug'] = Str::slug($data['name']);
        $data['platform'] = $this->normalizePlatforms($platforms);

        $platformStockMap = $this->normalizePlatformStockMap($platforms, $request->input('platform_stock', []));
        if (!empty($platformStockMap)) {
            $data['stock'] = array_sum($platformStockMap);
        }

        if ($request->hasFile('image')) {
            if ($product->image_url && ! str_starts_with($product->image_url, 'http')) {
                Storage::disk('public')->delete($product->image_url);
            }

            $data['image_url'] = $request->file('image')->store('products', 'public');
        } else {
            unset($data['image_url']);
        }

        $product->update($data);
        $this->syncPlatformStocks($product, $platformStockMap);

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
