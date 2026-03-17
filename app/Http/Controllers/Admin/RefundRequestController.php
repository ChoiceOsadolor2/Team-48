<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;

class RefundRequestController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));

        $refundRequests = RefundRequest::with(['user', 'order', 'orderItem.product', 'reviewer'])
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    if (ctype_digit($search)) {
                        $inner->orWhere('id', (int) $search)
                            ->orWhere('order_id', (int) $search)
                            ->orWhere('order_item_id', (int) $search);
                    }

                    $inner->orWhere('reason', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('orderItem.product', function ($productQuery) use ($search) {
                            $productQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('admin.refunds.index', compact('refundRequests', 'search', 'status'));
    }

    public function updateStatus(Request $request, RefundRequest $refundRequest)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,denied'],
        ]);

        $currentStatusFilter = trim((string) $request->input('current_status_filter', ''));
        $search = trim((string) $request->input('q', ''));

        $refundRequest->update([
            'status' => $data['status'],
            'reviewed_at' => $data['status'] === 'pending' ? null : now(),
            'reviewed_by' => $data['status'] === 'pending' ? null : $request->user()->id,
        ]);

        return redirect()
            ->route('admin.refunds.index', array_filter([
                'q' => $search !== '' ? $search : null,
                'status' => $currentStatusFilter !== '' ? $currentStatusFilter : null,
            ]))
            ->with('status', 'Refund request #' . $refundRequest->id . ' updated to ' . $data['status'] . '.');
    }
}
