<x-app-layout>
    <style>
        .admin-orders-page,
        .admin-orders-page * {
            font-family: 'MiniPixel', sans-serif !important;
            font-weight: 400 !important;
        }

        .admin-orders-page h1,
        .admin-orders-page h3 {
            font-size: 30px !important;
            line-height: 1.1 !important;
        }

        .admin-orders-page p,
        .admin-orders-page label,
        .admin-orders-page input,
        .admin-orders-page select,
        .admin-orders-page th,
        .admin-orders-page td,
        .admin-orders-page button,
        .admin-orders-page a {
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        .admin-orders-page input,
        .admin-orders-page select {
            min-height: 56px;
            border-radius: 18px !important;
            padding: 0 16px !important;
        }

        .admin-orders-page .rounded-xl,
        .admin-orders-page .rounded-lg {
            border-radius: 18px !important;
        }

        .admin-orders-page button,
        .admin-orders-page a.rounded-xl,
        .admin-orders-page a.rounded-lg {
            min-height: 56px;
            padding: 0 22px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .admin-orders-page .orders-filter-grid {
            align-items: end;
        }

        .admin-orders-page .orders-filter-actions {
            justify-content: flex-start;
        }

        .admin-orders-page .orders-table-head {
            background: #f8fafc;
        }

        .admin-orders-page .orders-row {
            transition: background 0.2s ease;
        }

        .admin-orders-page .orders-row:hover {
            background: rgba(15, 23, 42, 0.035);
        }

        .admin-orders-page .orders-row td {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }

        .admin-orders-page .orders-page-intro {
            margin-bottom: 8px;
        }

        html[data-theme="dark"] .admin-orders-page .orders-table-head {
            background: rgba(17, 24, 39, 0.78);
        }

        html[data-theme="dark"] .admin-orders-page .orders-row:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        @media (min-width: 768px) {
            .admin-orders-page .orders-page-intro {
                min-height: 58px;
                display: flex;
                align-items: center;
                margin-top: -90px;
                margin-left: 210px;
                margin-bottom: 24px;
            }
        }
    </style>
    <div class="admin-orders-page py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="orders-page-intro">
            <div>
                <h1 class="flex items-center gap-3 text-[1.7rem] font-bold text-gray-900 dark:text-white">
                    <svg class="h-7 w-7 text-cyan-600 dark:text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 6h13"></path>
                        <path d="M8 12h13"></path>
                        <path d="M8 18h13"></path>
                        <path d="M3 6h.01"></path>
                        <path d="M3 12h.01"></path>
                        <path d="M3 18h.01"></path>
                    </svg>
                    <span>All Orders</span>
                </h1>
                <p class="mt-1.5 text-[0.98rem] text-gray-500 dark:text-gray-400">Track fulfilment, review customers, and filter recent orders faster.</p>
            </div>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900/70">
                <div class="orders-filter-grid grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label class="mb-1.5 block text-[0.95rem] font-semibold text-gray-700 dark:text-gray-200">Search</label>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-[0.95rem] dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                            placeholder="Search name, email, order id, product..."
                        />
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[0.95rem] font-semibold text-gray-700 dark:text-gray-200">Status</label>
                        <select name="status" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-[0.95rem] dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="" {{ request('status') === '' ? 'selected' : '' }}>All</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>In fulfilment</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[0.95rem] font-semibold text-gray-700 dark:text-gray-200">From</label>
                        <input
                            type="date"
                            name="from"
                            value="{{ request('from') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-[0.95rem] dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                        />
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[0.95rem] font-semibold text-gray-700 dark:text-gray-200">To</label>
                        <input
                            type="date"
                            name="to"
                            value="{{ request('to') }}"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-[0.95rem] dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                        />
                    </div>
                </div>

                <div class="orders-filter-actions mt-4 flex gap-2">
                    <button type="submit" class="admin-btn admin-btn--primary">Apply</button>
                    <a href="{{ route('admin.orders.index') }}" class="admin-btn admin-btn--secondary">Clear</a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                <div>
                    <h3 class="text-[1.2rem] font-semibold text-gray-900 dark:text-white">Order queue</h3>
                    <p class="text-[0.95rem] text-gray-500 dark:text-gray-400">A cleaner table for fulfilment and customer review.</p>
                </div>
                <span class="rounded-full bg-gray-100 px-3.5 py-1.5 text-[0.82rem] font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                    @if (($orders->firstItem() ?? 0) <= 1 && ($orders->lastItem() ?? 0) === $orders->total())
                        Showing {{ $orders->count() }} of {{ $orders->total() }} orders
                    @else
                        Showing {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
                    @endif
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-[0.95rem]">
                    <thead class="orders-table-head text-left dark:bg-gray-900/70">
                        <tr class="text-[0.82rem] uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                            <th class="px-5 py-4 font-semibold">Order</th>
                            <th class="px-5 py-4 font-semibold">Customer</th>
                            <th class="px-5 py-4 font-semibold">Items</th>
                            <th class="px-5 py-4 font-semibold">Total</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold">Placed</th>
                            <th class="px-5 py-4 font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/80 dark:divide-gray-700/80">
                        @forelse ($orders as $order)
                            <tr class="orders-row">
                                <td class="px-5 py-5">
                                    <div class="font-semibold text-gray-900 dark:text-white">#{{ $order->id }}</div>
                                </td>
                                <td class="px-5 py-5">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $order->user?->name ?? 'Unknown' }}</div>
                                    <div class="mt-1 text-[0.82rem] text-gray-500 dark:text-gray-400">{{ $order->user?->email ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-5">
                                    <ul class="space-y-1 text-gray-600 dark:text-gray-300">
                                        @foreach ($order->items as $it)
                                            <li>{{ $it->product?->name ?? 'Deleted product' }} <span class="text-[0.82rem] text-gray-500">(x{{ $it->quantity }})</span></li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">£{{ number_format($order->total, 2) }}</td>
                                <td class="px-5 py-4">
                                    @php
                                        $statusClasses = match($order->status) {
                                            'cancelled' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200',
                                            'completed', 'delivered' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
                                            default => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                                        };
                                    @endphp
                                    <div class="mb-3">
                                        <span class="rounded-full px-3 py-1.5 text-[0.82rem] font-semibold {{ $statusClasses }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="q" value="{{ request('q') }}">
                                        <input type="hidden" name="current_status_filter" value="{{ request('status') }}">
                                        <input type="hidden" name="from" value="{{ request('from') }}">
                                        <input type="hidden" name="to" value="{{ request('to') }}">
                                        <select name="status" class="rounded-lg border border-gray-300 px-3.5 py-2 text-[0.9rem] dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>In fulfilment</option>
                                            <option value="completed" {{ $order->status === 'completed' || $order->status === 'delivered' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="admin-btn admin-btn--primary">
                                            Save
                                        </button>
                                    </form>
                                </td>
                                <td class="px-5 py-5 text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td class="px-5 py-5">
                                    <div class="flex justify-end">
                                        <a class="admin-btn admin-btn--quiet"
                                           href="{{ route('admin.orders.show', $order) }}">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-5 py-10 text-center text-[0.98rem] text-gray-500 dark:text-gray-400" colspan="7">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-6 py-5 dark:border-gray-700">
                {{ $orders->links() }}
            </div>
        </div>
        </div>
    </div>
</x-app-layout>
