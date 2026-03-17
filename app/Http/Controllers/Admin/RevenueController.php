<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RefundRequest;
use Carbon\Carbon;

class RevenueController extends Controller
{
    public function index()
    {
        $now = now()->startOfMonth();
        $windowStart = $now->copy()->subMonths(5);

        $orders = Order::query()
            ->whereNotIn('status', ['cancelled'])
            ->where('created_at', '>=', $windowStart)
            ->get(['id', 'total', 'status', 'created_at']);

        $allActiveOrders = Order::query()
            ->whereNotIn('status', ['cancelled'])
            ->get(['id', 'total', 'status', 'created_at']);

        $approvedRefunds = RefundRequest::query()
            ->where('status', 'approved')
            ->with('orderItem:id,price,quantity')
            ->get(['id', 'order_item_id', 'reviewed_at', 'created_at']);

        $grossRevenue = (float) $allActiveOrders->sum('total');
        $approvedRefundValue = (float) $approvedRefunds->sum(function (RefundRequest $refundRequest) {
            $orderItem = $refundRequest->orderItem;

            return $orderItem ? ((float) $orderItem->price * (int) $orderItem->quantity) : 0;
        });

        $netRevenue = $grossRevenue - $approvedRefundValue;
        $averageOrderValue = $allActiveOrders->isNotEmpty()
            ? (float) $allActiveOrders->avg('total')
            : 0.0;

        $completedOrderCount = $allActiveOrders
            ->whereIn('status', ['completed', 'delivered'])
            ->count();
        $processingOrderCount = $allActiveOrders
            ->where('status', 'processing')
            ->count();

        $months = collect(range(0, 5))
            ->map(fn (int $offset) => $windowStart->copy()->addMonths($offset));

        $monthlyRevenue = $months->map(function (Carbon $month) use ($orders, $approvedRefunds) {
            $gross = (float) $orders
                ->filter(fn (Order $order) => $order->created_at->format('Y-m') === $month->format('Y-m'))
                ->sum('total');

            $refunds = (float) $approvedRefunds
                ->filter(function (RefundRequest $refundRequest) use ($month) {
                    $refundDate = $refundRequest->reviewed_at ?? $refundRequest->created_at;

                    return $refundDate && $refundDate->format('Y-m') === $month->format('Y-m');
                })
                ->sum(function (RefundRequest $refundRequest) {
                    $orderItem = $refundRequest->orderItem;

                    return $orderItem ? ((float) $orderItem->price * (int) $orderItem->quantity) : 0;
                });

            $orderCount = $orders
                ->filter(fn (Order $order) => $order->created_at->format('Y-m') === $month->format('Y-m'))
                ->count();

            return [
                'label' => $month->format('M Y'),
                'short_label' => $month->format('M'),
                'gross' => $gross,
                'refunds' => $refunds,
                'net' => $gross - $refunds,
                'orders' => $orderCount,
            ];
        });

        $graphMax = max(
            1,
            (float) $monthlyRevenue->max('gross'),
            (float) $monthlyRevenue->max('net'),
            (float) $monthlyRevenue->max('refunds')
        );

        $activeOrderCount = max(1, $allActiveOrders->count());
        $topSalesMonths = $monthlyRevenue
            ->sortByDesc('net')
            ->take(3)
            ->values();

        $topSalesMonthMax = max(1, (float) $topSalesMonths->max('net'));

        return view('admin.revenue.index', [
            'grossRevenue' => $grossRevenue,
            'approvedRefundValue' => $approvedRefundValue,
            'netRevenue' => $netRevenue,
            'averageOrderValue' => $averageOrderValue,
            'completedOrderCount' => $completedOrderCount,
            'processingOrderCount' => $processingOrderCount,
            'activeOrderCount' => $activeOrderCount,
            'approvedRefundCount' => $approvedRefunds->count(),
            'monthlyRevenue' => $monthlyRevenue,
            'graphMax' => $graphMax,
            'topSalesMonths' => $topSalesMonths,
            'topSalesMonthMax' => $topSalesMonthMax,
        ]);
    }
}
