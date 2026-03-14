<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
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

    private function resolvePlatformSelection(Product $product, ?string $requestedPlatform = null): string
    {
        $options = $this->platformOptionsForProduct($product);
        $requestedPlatform = trim((string) $requestedPlatform);

        if ($requestedPlatform !== '' && in_array($requestedPlatform, $options, true)) {
            return $requestedPlatform;
        }

        return $options[0] ?? 'Universal';
    }
    // ✅ SHOW checkout page only
    public function index()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('status', 'Your cart is empty.');
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $missingProductIds = array_diff($productIds, $products->keys()->all());

        if (!empty($missingProductIds)) {
            foreach ($missingProductIds as $missingProductId) {
                unset($cart[$missingProductId]);
            }

            Session::put('cart', $cart);

            return redirect()->route('cart.index')
                ->with('stock_error', 'Some items in your cart are no longer available and were removed.');
        }

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $entryData) {
            if (!isset($products[$productId])) continue;

            $entry = $this->normalizeCartEntry($entryData);
            $product = $products[$productId];
            $subtotal = $product->price * $entry['quantity'];

            $items[] = [
                'product'  => $product,
                'quantity' => $entry['quantity'],
                'platform' => $this->resolvePlatformSelection($product, $entry['platform']),
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        return view('checkout.index', compact('items', 'total'));
    }

    // ✅ PLACE order only (create order, create items, decrement stock)
    public function place(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('status', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {
            // Lock products so stock can't be oversold in race conditions
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $missingProductIds = array_diff($productIds, $products->keys()->all());

            if (!empty($missingProductIds)) {
                foreach ($missingProductIds as $missingProductId) {
                    unset($cart[$missingProductId]);
                }

                Session::put('cart', $cart);
                DB::rollBack();

                return redirect()->route('cart.index')
                    ->with('stock_error', 'Some items in your cart are no longer available and were removed.');
            }

            // ✅ Validate stock before creating order
            foreach ($cart as $productId => $entryData) {
                if (!isset($products[$productId])) continue;

                $entry = $this->normalizeCartEntry($entryData);
                $product = $products[$productId];
                if ($product->stock < $entry['quantity']) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('stock_error', "Only {$product->stock} units of '{$product->name}' are available.");
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total'   => 0,
                'status'  => 'processing',
            ]);

            $total = 0;

            foreach ($cart as $productId => $entryData) {
                if (!isset($products[$productId])) continue;

                $entry = $this->normalizeCartEntry($entryData);
                $product = $products[$productId];
                $subtotal = $product->price * $entry['quantity'];
                $platform = $this->resolvePlatformSelection($product, $entry['platform']);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $entry['quantity'],
                    'price'      => $product->price,
                    'platform'   => $platform,
                ]);

                // ✅ decrement stock
                $product->decrement('stock', $entry['quantity']);

                $total += $subtotal;
            }

            $order->update(['total' => $total]);

            DB::commit();

            Session::forget('cart');

            return redirect()->route('orders.show', $order->id)
                ->with('status', 'Order placed successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('ORDER ERROR: ' . $e->getMessage());

            return redirect()->route('cart.index')
                ->with('status', 'Order failed. Please try again.');
        }
    }
}
