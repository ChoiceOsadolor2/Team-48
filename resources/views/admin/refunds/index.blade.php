<x-app-layout>
    <style>
        .admin-refunds-page,
        .admin-refunds-page * {
            font-family: 'MiniPixel', sans-serif !important;
            font-weight: 400 !important;
        }

        .admin-refunds-page h1,
        .admin-refunds-page h3 {
            font-size: 30px !important;
            line-height: 1.1 !important;
        }

        .admin-refunds-page p,
        .admin-refunds-page label,
        .admin-refunds-page input,
        .admin-refunds-page select,
        .admin-refunds-page th,
        .admin-refunds-page td,
        .admin-refunds-page button,
        .admin-refunds-page a {
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        .admin-refunds-page .page-intro {
            margin-bottom: 8px;
        }

        .admin-refunds-page .page-intro-title {
            font-size: 30px !important;
            line-height: 1.1 !important;
            color: #111827;
        }

        .admin-refunds-page .page-intro-copy {
            margin-top: 8px;
            color: #6b7280 !important;
        }

        @media (min-width: 768px) {
            .admin-refunds-page .page-intro {
                min-height: 58px;
                display: flex;
                align-items: center;
                margin-top: -90px;
                margin-left: 210px;
                margin-bottom: 24px;
            }
        }

        .admin-refunds-page input,
        .admin-refunds-page select {
            min-height: 56px;
            border-radius: 18px !important;
            padding: 0 16px !important;
        }

        .admin-refunds-page .rounded-xl,
        .admin-refunds-page .rounded-lg {
            border-radius: 18px !important;
        }

        .admin-refunds-page button,
        .admin-refunds-page a.rounded-xl,
        .admin-refunds-page a.rounded-lg {
            min-height: 56px;
            padding: 0 22px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .admin-refunds-page tbody td {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }

        html[data-theme="dark"] .admin-refunds-page .page-intro-title {
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-refunds-page .page-intro-copy {
            color: #9ca3af !important;
        }
    </style>
    <div class="admin-refunds-page py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 dark:border-emerald-800/70 dark:bg-emerald-900/20 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="page-intro">
            <div>
                <h1 class="page-intro-title flex items-center gap-3">
                    <svg class="h-7 w-7 text-cyan-600 dark:text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 7h18"></path>
                        <path d="M7 3v4"></path>
                        <path d="M17 3v4"></path>
                        <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                        <path d="M8 13h8"></path>
                        <path d="M8 17h5"></path>
                    </svg>
                    <span>Refund Requests</span>
                </h1>
                <p class="page-intro-copy">Handle refund decisions clearly and keep after-sales support moving.</p>
            </div>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <form method="GET" action="{{ route('admin.refunds.index') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/70">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Search</label>
                        <input
                            type="text"
                            name="q"
                            value="{{ $search ?? request('q') }}"
                            class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                            placeholder="Search customer, product, order, reason..."
                        />
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Status</label>
                        <select name="status" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">All requests</option>
                            <option value="pending" {{ ($status ?? request('status')) === 'pending' ? 'selected' : '' }}>Pending review</option>
                            <option value="approved" {{ ($status ?? request('status')) === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="denied" {{ ($status ?? request('status')) === 'denied' ? 'selected' : '' }}>Denied</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                        <a href="{{ route('admin.refunds.index') }}" class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Refund queue</h3>
                    </div>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                    Showing {{ $refundRequests->firstItem() ?? 0 }}-{{ $refundRequests->lastItem() ?? 0 }} of {{ $refundRequests->total() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left dark:bg-gray-900/70">
                        <tr class="text-xs uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                            <th class="px-5 py-4 font-semibold">Request</th>
                            <th class="px-5 py-4 font-semibold">Customer</th>
                            <th class="px-5 py-4 font-semibold">Order</th>
                            <th class="px-5 py-4 font-semibold">Product</th>
                            <th class="px-5 py-4 font-semibold">Reason</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold text-right">Decision</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($refundRequests as $refundRequest)
                            @php
                                $statusClasses = match($refundRequest->status) {
                                    'approved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
                                    'denied' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200',
                                    default => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                                };
                            @endphp
                            <tr class="transition hover:bg-gray-50/80 dark:hover:bg-gray-900/40">
                                <td class="px-5 py-4 align-top">
                                    <p class="font-semibold text-gray-900 dark:text-white">#{{ $refundRequest->id }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $refundRequest->created_at->format('d M Y H:i') }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $refundRequest->user?->name ?? 'Unknown customer' }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $refundRequest->user?->email ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <p class="font-semibold text-gray-900 dark:text-white">#{{ $refundRequest->order_id }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Order item #{{ $refundRequest->order_item_id }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $refundRequest->orderItem?->product?->name ?? 'Deleted product' }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $refundRequest->orderItem?->platform ?: 'Universal' }}</p>
                                </td>
                                <td class="px-5 py-4 align-top text-gray-600 dark:text-gray-300">
                                    <div class="max-w-md whitespace-pre-line break-words">{{ $refundRequest->reason }}</div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                        {{ ucfirst($refundRequest->status) }}
                                    </span>
                                    @if($refundRequest->reviewed_at)
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            Reviewed {{ $refundRequest->reviewed_at->diffForHumans() }}
                                            @if($refundRequest->reviewer)
                                                by {{ $refundRequest->reviewer->name }}
                                            @endif
                                        </p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="flex justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.refunds.update-status', $refundRequest) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="q" value="{{ request('q') }}">
                                            <input type="hidden" name="current_status_filter" value="{{ request('status') }}">
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="rounded-lg border border-emerald-200 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50 dark:border-emerald-800 dark:text-emerald-300 dark:hover:bg-emerald-900/20">
                                                Accept
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.refunds.update-status', $refundRequest) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="q" value="{{ request('q') }}">
                                            <input type="hidden" name="current_status_filter" value="{{ request('status') }}">
                                            <input type="hidden" name="status" value="denied">
                                            <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-800 dark:text-rose-300 dark:hover:bg-rose-900/20">
                                                Deny
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400" colspan="7">
                                    <div class="flex flex-col items-center gap-3">
                                        <p>No refund requests matched the current filters.</p>
                                        <a href="{{ route('admin.refunds.index') }}" class="admin-btn admin-btn--secondary">View all refunds</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">
                {{ $refundRequests->links() }}
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
