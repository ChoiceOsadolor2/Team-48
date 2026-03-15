<?php

namespace App\Http\Controllers;

use App\Models\ContactQuery;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Prevent users seeing other users' orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
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
        $orderItem->loadMissing(['order', 'product']);

        $this->authorizeReturnItem($orderItem);

        return view('orders.return', compact('orderItem'));
    }

    public function submitReturn(Request $request, OrderItem $orderItem): RedirectResponse
    {
        $orderItem->loadMissing(['order', 'product']);

        $this->authorizeReturnItem($orderItem);

        $validated = $request->validate([
            'request_type' => ['required', 'string', 'in:return,refund,exchange'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $user = $request->user();
        $order = $orderItem->order;
        $productName = $orderItem->product?->name ?? 'Unknown Product';
        $platform = $orderItem->platform ?: ($orderItem->product?->platform ?: 'Universal');

        ContactQuery::create([
            'name' => $user->name,
            'email' => $user->email,
            'subject' => sprintf(
                '%s request - Order VX-%d - %s',
                ucfirst($validated['request_type']),
                $order->id,
                $productName
            ),
            'message' => implode("\n", [
                'Return/refund request submitted from order history.',
                'Order: VX-' . $order->id,
                'Order item: #' . $orderItem->id,
                'Product: ' . $productName,
                'Platform: ' . $platform,
                'Quantity: ' . $orderItem->quantity,
                'Request type: ' . ucfirst($validated['request_type']),
                'Reason: ' . trim($validated['reason']),
            ]),
        ]);

        return redirect()
            ->route('orders.index')
            ->with('status', 'Your return request has been sent to support.');
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
