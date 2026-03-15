<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));
        $type = trim((string) $request->query('type', ''));

        $returnRequests = ReturnRequest::with(['user', 'order', 'orderItem.product', 'reviewedBy'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('name', 'like', "%{$search}%");
                    })->orWhere('reason', 'like', "%{$search}%");

                    if (ctype_digit($search)) {
                        $q->orWhere('order_id', (int) $search);
                    }
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($type !== '', fn ($query) => $query->where('request_type', $type))
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('admin.return-requests.index', compact('returnRequests', 'search', 'status', 'type'));
    }

    public function show(ReturnRequest $returnRequest)
    {
        $returnRequest->load(['user', 'order', 'orderItem.product', 'reviewedBy']);

        return view('admin.return-requests.show', compact('returnRequest'));
    }

    public function updateStatus(Request $request, ReturnRequest $returnRequest)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,declined'],
            'admin_notes' => ['nullable', 'string', 'max:1500'],
        ]);

        $returnRequest->update([
            'status' => $data['status'],
            'admin_notes' => trim((string) ($data['admin_notes'] ?? '')) ?: null,
            'reviewed_at' => $data['status'] === 'pending' ? null : now(),
            'reviewed_by' => $data['status'] === 'pending' ? null : $request->user()->id,
        ]);

        return redirect()->route('admin.return-requests.show', $returnRequest)
            ->with('status', 'Request updated to ' . $data['status'] . '.');
    }
}
