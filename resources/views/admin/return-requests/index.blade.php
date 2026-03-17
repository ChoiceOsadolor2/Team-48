<x-app-layout>
    <style>
        .admin-return-requests-page,
        .admin-return-requests-page * {
            font-family: 'MiniPixel', sans-serif !important;
            font-weight: 400 !important;
        }

        .admin-return-requests-page h2,
        .admin-return-requests-page h3 {
            font-size: 30px !important;
            line-height: 1.1 !important;
        }

        .admin-return-requests-page p,
        .admin-return-requests-page label,
        .admin-return-requests-page input,
        .admin-return-requests-page select,
        .admin-return-requests-page th,
        .admin-return-requests-page td,
        .admin-return-requests-page button,
        .admin-return-requests-page a {
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        .admin-return-requests-page .page-intro {
            margin-bottom: 8px;
        }

        .admin-return-requests-page .page-intro-title {
            font-size: 30px !important;
            line-height: 1.1 !important;
            color: #111827;
        }

        .admin-return-requests-page .page-intro-copy {
            margin-top: 8px;
            color: #6b7280 !important;
        }

        @media (min-width: 768px) {
            .admin-return-requests-page .page-intro {
                min-height: 58px;
                display: flex;
                align-items: center;
                margin-top: -90px;
                margin-left: 210px;
                margin-bottom: 24px;
            }
        }

        .admin-return-requests-page input,
        .admin-return-requests-page select {
            min-height: 56px;
            border-radius: 18px !important;
            padding: 0 16px !important;
        }

        .admin-return-requests-page .rounded-xl,
        .admin-return-requests-page .rounded-lg {
            border-radius: 18px !important;
        }

        .admin-return-requests-page button,
        .admin-return-requests-page a.rounded-xl,
        .admin-return-requests-page a.rounded-lg {
            min-height: 56px;
            padding: 0 22px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .admin-return-requests-page tbody td {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }

        html[data-theme="dark"] .admin-return-requests-page .page-intro-title {
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-return-requests-page .page-intro-copy {
            color: #9ca3af !important;
        }
    </style>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Returns & Refunds</h2>
                <p class="mt-1 text-sm text-gray-500">Review customer return, refund, and exchange requests.</p>
            </div>
        </div>
    </x-slot>

    <div class="admin-return-requests-page py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="page-intro">
                <h1 class="page-intro-title flex items-center gap-3">
                    <svg class="h-7 w-7 text-cyan-600 dark:text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 8v8a2 2 0 0 1-2 2H8"></path>
                        <path d="M3 16V8a2 2 0 0 1 2-2h11"></path>
                        <path d="m7 12-4 4 4 4"></path>
                        <path d="m17 4 4 4-4 4"></path>
                    </svg>
                    <span>Returns & Refunds</span>
                </h1>
                <p class="page-intro-copy">Track after-sales requests, review their status, and respond more efficiently.</p>
            </div>
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
