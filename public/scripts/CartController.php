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

    public function json(Request $request)
    {
        $cart = Session::get('cart', []);

        $items = [];
        $total = 0;

        if (!empty($cart)) {
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($cart as $productId => $qty) {
                if (!isset($products[$productId])) {
                    continue;
                }

                $product  = $products[$productId];
                $lineTotal = $product->price * $qty;
                $total += $lineTotal;

                $items[] = [
                    'id'        => $product->id,
                    'name'      => $product->name,
                    'price'     => $product->price,
                    'quantity'  => $qty,
                    'image_url' => $product->image_url,
                    'line_total'=> $lineTotal,
                ];
            }
        }

        return response()->json([
            'items' => $items,
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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart.',
            ]);
        }

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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated.',
            ]);
        }

        return back()->with('status', 'Cart updated.');
    }

    public function remove(Request $request, Product $product)
    {
        $cart = Session::get('cart', []);

        unset($cart[$product->id]);

        Session::put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed.',
            ]);
        }

        return back()->with('status', 'Item removed.');
    }

    public function clear(Request $request)
    {
        Session::forget('cart');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared.',
            ]);
        }

        return back()->with('status', 'Cart cleared.');
    }
}
