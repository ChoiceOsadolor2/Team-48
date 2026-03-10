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
    $currentQty = (int) ($cart[$product->id] ?? 0);
    $newQty = $currentQty + $qty;

    if ($product->stock <= 0) {
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

    if ($newQty > $product->stock) {
        $message = "You can only add up to {$product->stock} unit(s) of '{$product->name}'.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'cart' => $cart,
            ], 422);
        }

        return back()->with('stock_error', $message);
    }

    $cart[$product->id] = $newQty;

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

        return redirect()->route('cart.index')
            ->with('status', 'Item added to cart.');
    }

   public function update(Request $request, Product $product)
{
    $qty = (int) $request->input('quantity', 1);
    $cart = Session::get('cart', []);

    if ($qty <= 0) {
        unset($cart[$product->id]);
        Session::put('cart', $cart);

        if ($request->wantsJson()) {
            return $this->json($request);
        }

        return back()->with('status', 'Item removed.');
    }

    if ($qty > $product->stock) {
        $message = "Only {$product->stock} unit(s) of '{$product->name}' are available.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'cart' => $cart,
            ], 422);
        }

        return back()->with('stock_error', $message);
    }

    $cart[$product->id] = $qty;

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
        $qty = (int) $request->query('quantity', 1);

        $cart = Session::get('cart', []);

        if ($qty <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = $qty;
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
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => $qty,
                'stock'    => (int) $product->stock, 
                'image_url'=> $product->image_url,
                'subtotal' => $subtotal,
];


            $total += $subtotal;
        }

        return response()->json([
            'items' => $items,
            'total' => $total,
        ]);
    }
}
