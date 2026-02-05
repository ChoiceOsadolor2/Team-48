<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index(Request $request)
{
    $query = \App\Models\Order::with(['user', 'items.product']);

    // Search: order id OR user name/email
    if ($request->filled('q')) {
        $q = trim($request->q);

        $query->where(function ($sub) use ($q) {
            $sub->where('id', $q)
                ->orWhereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                });
        });
    }

    // Status filter
    if ($request->filled('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }

    // Date range filter
    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }
    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    $orders = $query->latest()->paginate(20)->withQueryString();

    return view('admin.orders.index', compact('orders'));
}


    // ✅ 2) Click into an order to see items, totals, delivery info (if exists)
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    // ✅ 3) Cancel order → restock items automatically
    public function cancel(Order $order)
    {
        // Prevent double-cancel
        if ($order->status === 'cancelled') {
            return back()->with('status', 'Order is already cancelled.');
        }

        DB::transaction(function () use ($order) {
            $order->load(['items.product']);

            // Restock each product
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', (int) $item->quantity);
                }
            }

            // Update order status
            $order->update([
                'status' => 'cancelled',
            ]);
        });

        return back()->with('status', 'Order cancelled and stock restocked.');
    }
}
