<x-app-layout>
    <div class="py-8 max-w-6xl mx-auto px-4 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">All Orders</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track fulfilment, review customers, and filter recent orders faster.</p>
            </div>
        </div>

        <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/70">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Search</label>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                            placeholder="Search name, email, order id, product..."
                        />
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Status</label>
                        <select name="status" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="" {{ request('status') === '' ? 'selected' : '' }}>All</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">From</label>
                        <input
                            type="date"
                            name="from"
                            value="{{ request('from') }}"
                            class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                        />
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">To</label>
                        <input
                            type="date"
                            name="to"
                            value="{{ request('to') }}"
                            class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                        />
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                    <a href="{{ route('admin.orders.index') }}" class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">Clear</a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order queue</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">A cleaner table for fulfilment and customer review.</p>
                </div>
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                    {{ $orders->count() }} shown
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left dark:bg-gray-900/70">
                        <tr class="text-xs uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                            <th class="px-5 py-4 font-semibold">Order</th>
                            <th class="px-5 py-4 font-semibold">Customer</th>
                            <th class="px-5 py-4 font-semibold">Items</th>
                            <th class="px-5 py-4 font-semibold">Total</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold">Placed</th>
                            <th class="px-5 py-4 font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($orders as $order)
                            <tr class="transition hover:bg-gray-50/80 dark:hover:bg-gray-900/40">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-white">#{{ $order->id }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $order->user?->name ?? 'Unknown' }}</div>
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $order->user?->email ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4">
                                    <ul class="space-y-1 text-gray-600 dark:text-gray-300">
                                        @foreach ($order->items as $it)
                                            <li>{{ $it->product?->name ?? 'Deleted product' }} <span class="text-xs text-gray-500">(x{{ $it->quantity }})</span></li>
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
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end">
                                        <a class="rounded-lg border border-cyan-200 px-3 py-1.5 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-50 dark:border-cyan-800 dark:text-cyan-300 dark:hover:bg-cyan-900/20"
                                           href="{{ route('admin.orders.show', $order) }}">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400" colspan="7">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
