<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string) $request->get('q', ''));
        $status = (string) $request->get('status', '');
        $from   = $request->get('from');
        $to     = $request->get('to');

        $ordersQ = Order::with(['user', 'items.product'])->latest();

        if ($status !== '') {
            $ordersQ->where('status', $status);
        }

        if ($from) {
            $ordersQ->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $ordersQ->whereDate('created_at', '<=', $to);
        }

        if ($q !== '') {
            $ordersQ->where(function ($qq) use ($q) {
                if (ctype_digit($q)) {
                    $qq->orWhere('id', (int) $q);
                }

                $qq->orWhereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                });

                $qq->orWhereHas('items.product', function ($p) use ($q) {
                    $p->where('name', 'like', "%{$q}%");
                });
            });
        }

        $orders = $ordersQ->paginate(20)->appends($request->query());

        return view('admin.orders.index', compact('orders'));
    }

    // ✅ View single order details page
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    // ✅ Cancel order + restock
    public function cancel(Order $order)
    {
        // prevent cancelling twice
        if ($order->status === 'cancelled') {
            return back()->with('status', 'Order already cancelled.');
        }

        DB::transaction(function () use ($order) {
            $order->load('items.product');

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);
        });

        return back()->with('status', 'Order cancelled and stock restored.');
    }
}
