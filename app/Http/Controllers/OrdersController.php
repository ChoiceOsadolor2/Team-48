<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RefundRequest;
use App\Models\ReturnRequest;
use App\Support\InputSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'items.review', 'items.refundRequest', 'items.latestReturnRequest', 'serviceReviews'])
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
        $orderItem->loadMissing(['order', 'product', 'refundRequest', 'latestReturnRequest']);

        $this->authorizeReturnItem($orderItem);

        if ($orderItem->refundRequest()->exists()) {
            return redirect()
                ->route('orders.index')
                ->with('status', 'Refund request already sent for this item.');
        }

        return view('orders.return', compact('orderItem'));
    }

    public function submitReturn(Request $request, OrderItem $orderItem): RedirectResponse
    {
        $orderItem->loadMissing(['order', 'product', 'refundRequest', 'latestReturnRequest']);

        $this->authorizeReturnItem($orderItem);

        if ($orderItem->refundRequest()->exists()) {
            return redirect()
                ->route('orders.index')
                ->with('status', 'Refund request already sent for this item.');
        }

        $request->merge([
            'reason' => InputSanitizer::multiLine($request->input('reason')),
        ]);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $order = $orderItem->order;
        $productName = $orderItem->product?->name ?? 'Unknown Product';
        RefundRequest::create([
            'user_id' => $request->user()->id,
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'status' => 'pending',
            'reason' => $validated['reason'],
        ]);

        ReturnRequest::updateOrCreate([
            'order_item_id' => $orderItem->id,
        ], [
            'user_id' => $request->user()->id,
            'order_id' => $order->id,
            'product_id' => $orderItem->product_id,
            'request_type' => 'refund',
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('orders.index')
            ->with('status', 'Refund request sent for ' . $productName . '.');
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
