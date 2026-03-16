<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderItem;
use App\Support\InputSanitizer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    private const DISCOUNT_SESSION_KEY = 'checkout.discount_code';

    private function shippingOptions(): array
    {
        return [
            'standard' => [
                'label' => 'Standard Delivery',
                'price' => 4.99,
            ],
            'express' => [
                'label' => 'Express Delivery',
                'price' => 9.99,
            ],
            'next_day' => [
                'label' => 'Next Day Delivery',
                'price' => 14.99,
            ],
        ];
    }

    private function resolveShippingOption(?string $requestedOption = null): array
    {
        $options = $this->shippingOptions();
        $key = trim((string) $requestedOption);

        if ($key !== '' && isset($options[$key])) {
            return ['key' => $key] + $options[$key];
        }

        return ['key' => 'standard'] + $options['standard'];
    }

    private function emptyShippingSelection(): array
    {
        return [
            'key' => '',
            'label' => 'Select shipping',
            'price' => 0,
        ];
    }

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

        return ! empty($platforms) ? $platforms : ['Universal'];
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

    private function availableStockFor(Product $product, ?string $platform = null): int
    {
        return $product->stockForPlatform($platform);
    }

    private function calculateDiscountAmount(DiscountCode $discountCode, float $subtotal): float
    {
        $amount = $discountCode->type === 'percentage'
            ? ($subtotal * ((float) $discountCode->value / 100))
            : (float) $discountCode->value;

        return min($subtotal, max(0, round($amount, 2)));
    }

    private function resolveValidDiscountCode(?string $code): array
    {
        $normalizedCode = strtoupper(trim((string) $code));

        if ($normalizedCode === '') {
            return ['discountCode' => null, 'message' => 'Please enter a discount code.'];
        }

        $discountCode = DiscountCode::query()
            ->whereRaw('UPPER(code) = ?', [$normalizedCode])
            ->first();

        if (! $discountCode) {
            return ['discountCode' => null, 'message' => 'That discount code could not be found.'];
        }

        if (! $discountCode->is_active) {
            return ['discountCode' => null, 'message' => 'That discount code is not active right now.'];
        }

        if (! $discountCode->hasStarted()) {
            return ['discountCode' => null, 'message' => 'That discount code is not available yet.'];
        }

        if ($discountCode->isExpired()) {
            return ['discountCode' => null, 'message' => 'That discount code has expired.'];
        }

        if (! $discountCode->hasUsageRemaining()) {
            return ['discountCode' => null, 'message' => 'That discount code has reached its usage limit.'];
        }

        return ['discountCode' => $discountCode, 'message' => null];
    }

    private function appliedDiscountSummary(float $subtotal): ?array
    {
        $storedCode = Session::get(self::DISCOUNT_SESSION_KEY);

        if (! $storedCode) {
            return null;
        }

        $resolved = $this->resolveValidDiscountCode($storedCode);
        $discountCode = $resolved['discountCode'];

        if (! $discountCode) {
            Session::forget(self::DISCOUNT_SESSION_KEY);
            session()->flash('discount_error', $resolved['message'] ?? 'That discount code is no longer available.');

            return null;
        }

        return [
            'code' => $discountCode->code,
            'amount' => $this->calculateDiscountAmount($discountCode, $subtotal),
            'label' => $discountCode->type === 'percentage'
                ? rtrim(rtrim(number_format((float) $discountCode->value, 2), '0'), '.') . '% off'
                : '£' . number_format((float) $discountCode->value, 2) . ' off',
        ];
    }

    public function index()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('status', 'Your cart is empty.');
        }

        $productIds = array_keys($cart);
        $products = Product::with('platformStocks')->whereIn('id', $productIds)->get()->keyBy('id');
        $missingProductIds = array_diff($productIds, $products->keys()->all());

        if (! empty($missingProductIds)) {
            foreach ($missingProductIds as $missingProductId) {
                unset($cart[$missingProductId]);
            }

            Session::put('cart', $cart);

            return redirect()->route('cart.index')
                ->with('stock_error', 'Some items in your cart are no longer available and were removed.');
        }

        $items = [];
        $total = 0;
        $selectedShipping = $this->emptyShippingSelection();

        foreach ($cart as $productId => $entryData) {
            if (! isset($products[$productId])) {
                continue;
            }

            $entry = $this->normalizeCartEntry($entryData);
            $product = $products[$productId];
            $subtotal = $product->price * $entry['quantity'];

            $items[] = [
                'product' => $product,
                'quantity' => $entry['quantity'],
                'platform' => $this->resolvePlatformSelection($product, $entry['platform']),
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        $shippingOptions = $this->shippingOptions();
        $shippingCost = $selectedShipping['price'];
        $appliedDiscount = $this->appliedDiscountSummary($total);

        return view('checkout.index', compact('items', 'total', 'shippingOptions', 'selectedShipping', 'shippingCost', 'appliedDiscount'));
    }

    public function applyDiscount(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            Session::forget(self::DISCOUNT_SESSION_KEY);

            return redirect()->route('cart.index')
                ->with('status', 'Your cart is empty.');
        }

        $resolved = $this->resolveValidDiscountCode($request->input('discount_code'));
        $discountCode = $resolved['discountCode'];

        if (! $discountCode) {
            return redirect()->route('checkout.index')
                ->withInput()
                ->with('discount_error', $resolved['message']);
        }

        Session::put(self::DISCOUNT_SESSION_KEY, $discountCode->code);

        return redirect()->route('checkout.index')
            ->with('discount_success', 'Discount code applied successfully.');
    }

    public function removeDiscount()
    {
        Session::forget(self::DISCOUNT_SESSION_KEY);

        return redirect()->route('checkout.index')
            ->with('discount_success', 'Discount code removed.');
    }

    public function place(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('status', 'Your cart is empty.');
        }

        $request->merge([
            'email' => InputSanitizer::email($request->input('email')),
            'first-name' => InputSanitizer::singleLine($request->input('first-name')),
            'last-name' => InputSanitizer::singleLine($request->input('last-name')),
            'address' => InputSanitizer::singleLine($request->input('address')),
            'city' => InputSanitizer::singleLine($request->input('city')),
            'country' => InputSanitizer::singleLine($request->input('country')),
            'postal-code' => InputSanitizer::singleLine($request->input('postal-code')),
            'card-number' => InputSanitizer::singleLine($request->input('card-number')),
            'expiry' => InputSanitizer::singleLine($request->input('expiry')),
            'cvv' => InputSanitizer::singleLine($request->input('cvv')),
        ]);

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'first-name' => ['required', 'string', 'max:60', 'regex:/^[\pL\s\'-]+$/u'],
            'last-name' => ['required', 'string', 'max:60', 'regex:/^[\pL\s\'-]+$/u'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
            'country' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
            'postal-code' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9\s-]+$/'],
            'shipping_option' => ['required', 'in:standard,express,next_day'],
            'payment-type' => ['required', 'in:card'],
            'card-number' => ['required', 'string', 'regex:/^[0-9 ]{13,23}$/'],
            'expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv' => ['required', 'string', 'regex:/^\d{3,4}$/'],
        ]);

        $shippingKey = trim((string) $request->input('shipping_option'));
        $shippingOptions = $this->shippingOptions();

        if ($shippingKey === '' || ! isset($shippingOptions[$shippingKey])) {
            return redirect()->route('checkout.index')
                ->with('status', 'Please select a shipping option.');
        }

        $selectedShipping = $this->resolveShippingOption($shippingKey);

        DB::beginTransaction();

        try {
            $productIds = array_keys($cart);
            $products = Product::with('platformStocks')->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $missingProductIds = array_diff($productIds, $products->keys()->all());

            if (! empty($missingProductIds)) {
                foreach ($missingProductIds as $missingProductId) {
                    unset($cart[$missingProductId]);
                }

                Session::put('cart', $cart);
                DB::rollBack();

                return redirect()->route('cart.index')
                    ->with('stock_error', 'Some items in your cart are no longer available and were removed.');
            }

            foreach ($cart as $productId => $entryData) {
                if (! isset($products[$productId])) {
                    continue;
                }

                $entry = $this->normalizeCartEntry($entryData);
                $product = $products[$productId];
                $platform = $this->resolvePlatformSelection($product, $entry['platform']);
                $availableStock = $this->availableStockFor($product, $platform);

                if ($availableStock < $entry['quantity']) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('stock_error', "Only {$availableStock} units of '{$product->name}' are available for {$platform}.");
                }
            }

            $discountCode = null;
            $discountAmount = 0.0;
            $storedDiscountCode = Session::get(self::DISCOUNT_SESSION_KEY);

            if ($storedDiscountCode) {
                $discountCode = DiscountCode::query()
                    ->whereRaw('UPPER(code) = ?', [strtoupper((string) $storedDiscountCode)])
                    ->lockForUpdate()
                    ->first();

                if (! $discountCode || ! $discountCode->is_active || ! $discountCode->hasStarted() || $discountCode->isExpired() || ! $discountCode->hasUsageRemaining()) {
                    Session::forget(self::DISCOUNT_SESSION_KEY);
                    DB::rollBack();

                    return redirect()->route('checkout.index')
                        ->with('discount_error', 'That discount code is no longer available. Please review your checkout total and try again.');
                }
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0,
                'status' => 'processing',
                'shipping_method' => $selectedShipping['label'],
                'shipping_cost' => $selectedShipping['price'],
                'discount_code' => $discountCode?->code,
                'discount_amount' => 0,
            ]);

            $total = 0;

            foreach ($cart as $productId => $entryData) {
                if (! isset($products[$productId])) {
                    continue;
                }

                $entry = $this->normalizeCartEntry($entryData);
                $product = $products[$productId];
                $subtotal = $product->price * $entry['quantity'];
                $platform = $this->resolvePlatformSelection($product, $entry['platform']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $entry['quantity'],
                    'price' => $product->price,
                    'platform' => $platform,
                ]);

                $product->decrement('stock', $entry['quantity']);

                if ($product->hasPlatformSpecificStock()) {
                    $product->platformStocks()
                        ->where('platform', $platform)
                        ->decrement('stock', $entry['quantity']);
                }

                $total += $subtotal;
            }

            if ($discountCode) {
                $discountAmount = $this->calculateDiscountAmount($discountCode, $total);
                $discountCode->increment('used_count');
            }

            $order->update([
                'total' => max(0, ($total + $selectedShipping['price']) - $discountAmount),
                'discount_code' => $discountCode?->code,
                'discount_amount' => $discountAmount,
            ]);

            DB::commit();

            Session::forget('cart');
            Session::forget(self::DISCOUNT_SESSION_KEY);

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
