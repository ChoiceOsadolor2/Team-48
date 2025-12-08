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
    public function index()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('status', 'Your cart is empty.');
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $qty) {
            if (!isset($products[$productId])) {
                continue;
            }

            $product = $products[$productId];
            $subtotal = $product->price * $qty;

            $items[] = [
                'product'  => $product,
                'quantity' => $qty,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        return view('checkout.index', compact('items', 'total'));
    }

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
            $order = Order::create([
                'user_id' => $user->id,
                'total'   => 0,
                'status'  => 'processing',
            ]);

            $total = 0;

            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($cart as $productId => $qty) {
                if (!isset($products[$productId])) {
                    continue;
                }

                $product = $products[$productId];
                $subtotal = $product->price * $qty;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $qty,
                    'price'      => $product->price,
                ]);

                $total += $subtotal;
            }

            $order->update(['total' => $total]);

            DB::commit();

            Session::forget('cart');

            return redirect()->route('orders.show', $order->id)
                ->with('status', 'Order placed successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('ORDER ERROR: '.$e->getMessage());

            return redirect()->route('cart.index')
                ->with('status', 'Order failed. Please try again.');
        }
    }
}
