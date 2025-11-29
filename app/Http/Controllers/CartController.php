<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;

class CartController extends Controller
{
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
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $qty) {
            if (!isset($products[$productId])) {
                continue;
            }

            $product  = $products[$productId];
            $subtotal = $product->price * $qty;

            $items[] = [
                'product'  => $product,
                'quantity' => $qty,
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
        $qty = (int) $request->input('quantity', 1);
        if ($qty < 1) {
            $qty = 1;
        }

        $cart = Session::get('cart', []);

        $cart[$product->id] = ($cart[$product->id] ?? 0) + $qty;

        Session::put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('status', 'Item added to cart.');
    }

    public function update(Request $request, Product $product)
    {
        $qty = (int) $request->input('quantity', 1);

        $cart = Session::get('cart', []);

        if ($qty <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = $qty;
        }

        Session::put('cart', $cart);

        return back()->with('status', 'Cart updated.');
    }

    public function remove(Product $product)
    {
        $cart = Session::get('cart', []);

        unset($cart[$product->id]);

        Session::put('cart', $cart);

        return back()->with('status', 'Item removed.');
    }

    public function clear()
    {
        Session::forget('cart');

        return back()->with('status', 'Cart cleared.');
    }
}
