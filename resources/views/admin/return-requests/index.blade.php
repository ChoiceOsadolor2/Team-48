<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Returns & Refunds</h2>
                <p class="mt-1 text-sm text-gray-500">Review customer return, refund, and exchange requests.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.return-requests.index') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Search</label>
                            <input type="text" name="q" value="{{ $search }}" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm" placeholder="Customer, order, product..." />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Status</label>
                            <select name="status" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm">
                                <option value="">All statuses</option>
                                <option value="pending" @selected($status === 'pending')>Pending</option>
                                <option value="approved" @selected($status === 'approved')>Approved</option>
                                <option value="declined" @selected($status === 'declined')>Declined</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Type</label>
                            <select name="type" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm">
                                <option value="">All types</option>
                                <option value="return" @selected($type === 'return')>Return</option>
                                <option value="refund" @selected($type === 'refund')>Refund</option>
                                <option value="exchange" @selected($type === 'exchange')>Exchange</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                            <a href="{{ route('admin.return-requests.index') }}" class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-300">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Request queue</h3>
                        <p class="text-sm text-gray-500">Approve or decline customer after-sales requests from one place.</p>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                        Showing {{ $returnRequests->firstItem() ?? 0 }}-{{ $returnRequests->lastItem() ?? 0 }} of {{ $returnRequests->total() }}
                    </span>
                </div>

                @if ($returnRequests->isEmpty())
                    <div class="px-5 py-10 text-center text-sm text-gray-500">
                        No return or refund requests matched the current filters.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-left">
                                <tr class="text-xs uppercase tracking-[0.18em] text-gray-500">
                                    <th class="px-5 py-4 font-semibold">Customer</th>
                                    <th class="px-5 py-4 font-semibold">Order</th>
                                    <th class="px-5 py-4 font-semibold">Product</th>
                                    <th class="px-5 py-4 font-semibold">Type</th>
                                    <th class="px-5 py-4 font-semibold">Status</th>
                                    <th class="px-5 py-4 font-semibold">Submitted</th>
                                    <th class="px-5 py-4 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($returnRequests as $returnRequest)
                                    @php
                                        $statusClasses = match ($returnRequest->status) {
                                            'approved' => 'bg-emerald-100 text-emerald-800',
                                            'declined' => 'bg-rose-100 text-rose-800',
                                            default => 'bg-amber-100 text-amber-800',
                                        };
                                    @endphp
                                    <tr class="transition hover:bg-gray-50/80">
                                        <td class="px-5 py-4 align-top">
                                            <p class="font-semibold text-gray-900">{{ $returnRequest->user?->name ?? 'Unknown customer' }}</p>
                                            <p class="mt-1 text-xs text-gray-500">{{ $returnRequest->user?->email }}</p>
                                        </td>
                                        <td class="px-5 py-4 align-top text-gray-700">VX-{{ $returnRequest->order_id }}</td>
                                        <td class="px-5 py-4 align-top">
                                            <p class="font-semibold text-gray-900">{{ $returnRequest->product?->name ?? 'Unknown product' }}</p>
                                            <p class="mt-1 text-xs text-gray-500">Item #{{ $returnRequest->order_item_id }}</p>
                                        </td>
                                        <td class="px-5 py-4 align-top text-gray-700">{{ ucfirst($returnRequest->request_type) }}</td>
                                        <td class="px-5 py-4 align-top">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                                {{ ucfirst($returnRequest->status) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 align-top text-gray-500">{{ $returnRequest->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.return-requests.show', $returnRequest) }}" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-500">Review</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($returnRequests->hasPages())
                    <div class="border-t border-gray-200 px-5 py-4">
                        {{ $returnRequests->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
