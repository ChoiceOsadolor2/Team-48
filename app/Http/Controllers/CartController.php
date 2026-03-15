<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    private function normalizeCartEntry(mixed $entry): array
    {
        if (is_array($entry)) {
            return [
                'quantity' => max(0, (int) ($entry['quantity'] ?? 1)),
                'platform' => filled($entry['platform'] ?? null) ? trim((string) $entry['platform']) : null,
            ];
        }

        return [
            'quantity' => max(0, (int) $entry),
            'platform' => null,
        ];
    }

    private function platformOptionsForProduct(Product $product): array
    {
        $rawPlatform = trim((string) ($product->platform ?? ''));

        if ($rawPlatform === '') {
            return ['Universal'];
        }

        $platforms = collect(explode(',', $rawPlatform))
            ->map(fn ($platform) => trim((string) $platform))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return !empty($platforms) ? $platforms : ['Universal'];
    }

    private function resolvePlatformSelection(Product $product, ?string $requestedPlatform = null, ?string $currentPlatform = null): string
    {
        $options = $this->platformOptionsForProduct($product);

        foreach ([$requestedPlatform, $currentPlatform] as $candidate) {
            $candidate = trim((string) $candidate);
            if ($candidate !== '' && in_array($candidate, $options, true)) {
                return $candidate;
            }
        }

        return $options[0] ?? 'Universal';
    }

    private function availableStockFor(Product $product, ?string $platform = null): int
    {
        return $product->stockForPlatform($platform);
    }

    public function index()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return view('cart.index', [
                'items' => collect(),
                'total' => 0,
            ]);
        }

        $productIds = array_keys($cart);
        $products = Product::with('platformStocks')->whereIn('id', $productIds)->get()->keyBy('id');
        $missingProductIds = array_diff($productIds, $products->keys()->all());

        if (!empty($missingProductIds)) {
            foreach ($missingProductIds as $missingProductId) {
                unset($cart[$missingProductId]);
            }

            Session::put('cart', $cart);
        }

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $entryData) {
            if (!isset($products[$productId])) {
                continue;
            }

            $entry = $this->normalizeCartEntry($entryData);
            $product  = $products[$productId];
            $subtotal = $product->price * $entry['quantity'];

            $items[] = [
                'product'  => $product,
                'quantity' => $entry['quantity'],
                'platform' => $this->resolvePlatformSelection($product, $entry['platform']),
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        return view('cart.index', [
            'items' => collect($items),
            'total' => $total,
        ]);
    }

    public function add(Request $request, Product $product)
{
    $validated = $request->validate([
        'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
        'platform' => ['nullable', 'string', Rule::in($this->platformOptionsForProduct($product))],
    ]);

    $qty = (int) ($validated['quantity'] ?? 1);
    if ($qty < 1) {
        $qty = 1;
    }

    $cart = Session::get('cart', []);
    $currentEntry = $this->normalizeCartEntry($cart[$product->id] ?? 0);
    $selectedPlatform = $this->resolvePlatformSelection(
        $product,
        $validated['platform'] ?? null,
        $currentEntry['platform']
    );
    $newQty = $currentEntry['quantity'] + $qty;

        $availableStock = $this->availableStockFor($product, $selectedPlatform);

        if ($availableStock <= 0) {
            $message = "'{$product->name}' is out of stock.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'cart' => $cart,
            ], 422);
        }

        return back()->with('stock_error', $message);
    }

    if ($newQty > $availableStock) {
        $message = "You can only add up to {$availableStock} unit(s) of '{$product->name}' for {$selectedPlatform}.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'cart' => $cart,
            ], 422);
        }

        return back()->with('stock_error', $message);
    }

    $cart[$product->id] = [
        'quantity' => $newQty,
        'platform' => $selectedPlatform,
    ];

    Session::put('cart', $cart);

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'cart'    => $cart,
        ]);
    }

    return redirect()->route('cart.index')
        ->with('status', 'Item added to cart.');
}

   public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'quantity' => ['nullable', 'integer', 'min:0', 'max:10'],
        'platform' => ['nullable', 'string', Rule::in($this->platformOptionsForProduct($product))],
    ]);

    $qty = (int) ($validated['quantity'] ?? 1);
    $cart = Session::get('cart', []);
    $currentEntry = $this->normalizeCartEntry($cart[$product->id] ?? 0);
    $selectedPlatform = $this->resolvePlatformSelection(
        $product,
        $validated['platform'] ?? null,
        $currentEntry['platform']
    );

    if ($qty <= 0) {
        unset($cart[$product->id]);
        Session::put('cart', $cart);

        if ($request->wantsJson()) {
            return $this->json($request);
        }

        return back()->with('status', 'Item removed.');
    }

    $availableStock = $this->availableStockFor($product, $selectedPlatform);

    if ($qty > $availableStock) {
        $message = "Only {$availableStock} unit(s) of '{$product->name}' are available for {$selectedPlatform}.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'cart' => $cart,
            ], 422);
        }

        return back()->with('stock_error', $message);
    }

    $cart[$product->id] = [
        'quantity' => $qty,
        'platform' => $selectedPlatform,
    ];

    Session::put('cart', $cart);

    if ($request->wantsJson()) {
        return $this->json($request);
    }

    return back()->with('status', 'Cart updated.');
}

    public function remove(Product $product)
    {
        $cart = Session::get('cart', []);

        unset($cart[$product->id]);

        Session::put('cart', $cart);

        return back()->with('status', 'Item removed.');
    }

    public function updateJson(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:0', 'max:10'],
            'platform' => ['nullable', 'string', Rule::in($this->platformOptionsForProduct($product))],
        ]);

        $qty = (int) ($validated['quantity'] ?? 1);
        $cart = Session::get('cart', []);
        $currentEntry = $this->normalizeCartEntry($cart[$product->id] ?? 0);
        $selectedPlatform = $this->resolvePlatformSelection(
            $product,
            $validated['platform'] ?? null,
            $currentEntry['platform']
        );
        $availableStock = $this->availableStockFor($product, $selectedPlatform);

        if ($qty <= 0) {
            unset($cart[$product->id]);
        } elseif ($qty > $availableStock) {
            return response()->json([
                'success' => false,
                'message' => "Only {$availableStock} unit(s) of '{$product->name}' are available for {$selectedPlatform}.",
                'cart' => $cart,
            ], 422);
        } else {
            $cart[$product->id] = [
                'quantity' => $qty,
                'platform' => $selectedPlatform,
            ];
        }

        Session::put('cart', $cart);

        return $this->json($request);
    }


    public function removeJson(Product $product, Request $request)
    {    
        $this->remove($product);

        return $this->json($request);
    }


    public function clear()
    {
        Session::forget('cart');

        return back()->with('status', 'Cart cleared.');
    }

    public function json(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'items' => [],
                'total' => 0,
            ]);
        }

        $productIds = array_keys($cart);
        $products = Product::with('platformStocks')->whereIn('id', $productIds)->get()->keyBy('id');
        $missingProductIds = array_diff($productIds, $products->keys()->all());

        if (!empty($missingProductIds)) {
            foreach ($missingProductIds as $missingProductId) {
                unset($cart[$missingProductId]);
            }

            Session::put('cart', $cart);
        }

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $entryData) {
            if (!isset($products[$productId])) {
                continue;
            }

            $entry = $this->normalizeCartEntry($entryData);
            $product  = $products[$productId];
            $platform = $this->resolvePlatformSelection($product, $entry['platform']);
            $availableStock = $this->availableStockFor($product, $platform);
            $subtotal = $product->price * $entry['quantity'];

            $items[] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $entry['quantity'],
                'stock'    => $availableStock,
                'image_url'=> $product->image_url,
                'platform' => $platform,
                'subtotal' => $subtotal,
];


            $total += $subtotal;
        }

        return response()->json([
            'items' => $items,
            'total' => $total,
            'message' => !empty($missingProductIds)
                ? 'Some unavailable items were removed from your cart.'
                : null,
        ]);
    }
}
