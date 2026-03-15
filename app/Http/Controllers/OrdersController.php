<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'items.latestReturnRequest'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function returnsIndex(Order $order): View
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! in_array(strtolower((string) $order->status), ['completed', 'delivered'], true)) {
            abort(404);
        }

        $order->load(['items.product', 'items.latestReturnRequest']);

        return view('orders.returns', compact('order'));
    }

    public function cancel(Order $order): RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (strtolower($order->status) !== 'processing') {
            return back()->with('status', 'Only processing orders can be cancelled.');
        }

        DB::transaction(function () use ($order) {
            $order->load(['items.product']);

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', (int) $item->quantity);
                }
            }

            $order->update([
                'status' => 'cancelled',
            ]);
        });

        return back()->with('status', 'Order cancelled successfully.');
    }

    public function returnForm(OrderItem $orderItem): View
    {
        $orderItem->loadMissing(['order', 'product', 'latestReturnRequest']);

        $this->authorizeReturnItem($orderItem);

        return view('orders.return', compact('orderItem'));
    }

    public function submitReturn(Request $request, OrderItem $orderItem): RedirectResponse
    {
        $orderItem->loadMissing(['order', 'product', 'latestReturnRequest']);

        $this->authorizeReturnItem($orderItem);

        if ($orderItem->latestReturnRequest && $orderItem->latestReturnRequest->status === 'pending') {
            return redirect()
                ->route('orders.index')
                ->with('status', 'A return or refund request for this item is already pending review.');
        }

        $validated = $request->validate([
            'request_type' => ['required', 'string', 'in:return,refund,exchange'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user = $request->user();
        $order = $orderItem->order;

        ReturnRequest::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'product_id' => $orderItem->product_id,
            'request_type' => $validated['request_type'],
            'reason' => trim($validated['reason']),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('orders.index')
            ->with('status', 'Your return request has been sent for review.');
    }

    protected function authorizeReturnItem(OrderItem $orderItem): void
    {
        $order = $orderItem->order;

        if (! $order || $order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! in_array(strtolower((string) $order->status), ['completed', 'delivered'], true)) {
            abort(404);
        }
    }
}
