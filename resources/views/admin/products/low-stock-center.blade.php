<x-app-layout>
    <style>
        .admin-low-stock-page .low-card,
        .admin-low-stock-page .low-shell,
        .admin-low-stock-page .low-filter-shell {
            background: #ffffff;
            border-color: #e5e7eb;
        }

        .admin-low-stock-page .low-soft,
        .admin-low-stock-page .low-table-head {
            background: #f9fafb;
        }

        .admin-low-stock-page .low-text {
            color: #111827 !important;
        }

        .admin-low-stock-page .low-muted {
            color: #6b7280 !important;
        }

        .admin-low-stock-page .low-control {
            min-height: 56px;
            border-radius: 1rem;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            color: #111827;
            font-size: 1rem;
        }

        .admin-low-stock-page .low-control::placeholder {
            color: #6b7280;
        }

        .admin-low-stock-page .low-row:hover {
            background: rgba(15, 23, 42, 0.035);
        }

        html[data-theme="dark"] .admin-low-stock-page .low-card,
        html[data-theme="dark"] .admin-low-stock-page .low-shell,
        html[data-theme="dark"] .admin-low-stock-page .low-filter-shell {
            background: #1f2937;
            border-color: #374151;
        }

        html[data-theme="dark"] .admin-low-stock-page .low-soft,
        html[data-theme="dark"] .admin-low-stock-page .low-table-head {
            background: rgba(17, 24, 39, 0.78);
        }

        html[data-theme="dark"] .admin-low-stock-page .low-text {
            color: #f9fafb !important;
        }

        html[data-theme="dark"] .admin-low-stock-page .low-muted {
            color: #9ca3af !important;
        }

        html[data-theme="dark"] .admin-low-stock-page .low-control {
            border-color: #4b5563;
            background: rgba(17, 24, 39, 0.78);
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-low-stock-page .low-control::placeholder {
            color: #9ca3af;
        }

        html[data-theme="dark"] .admin-low-stock-page .low-row:hover {
            background: rgba(255, 255, 255, 0.03);
        }
    </style>

    <div class="admin-low-stock-page py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="low-shell rounded-3xl border p-5 shadow-sm">
                <div class="md:flex md:items-start md:justify-between">
                    <div>
                        <h1 class="low-text text-2xl font-semibold">Low-Stock Notification Centre</h1>
                        <p class="low-muted mt-1 text-base">Prioritise restocking based on urgency and current inventory risk.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="low-card rounded-3xl border p-6 shadow-sm">
                    <p class="text-sm font-medium text-rose-700">Critical</p>
                    <p class="low-text mt-2 text-3xl font-bold">{{ $criticalCount }}</p>
                    <p class="low-muted mt-2 text-sm">Products with 0-2 units left.</p>
                </div>
                <div class="low-card rounded-3xl border p-6 shadow-sm">
                    <p class="text-sm font-medium text-amber-700">Warning</p>
                    <p class="low-text mt-2 text-3xl font-bold">{{ $warningCount }}</p>
                    <p class="low-muted mt-2 text-sm">Products with 3-5 units left.</p>
                </div>
                <div class="low-card rounded-3xl border p-6 shadow-sm">
                    <p class="text-sm font-medium text-sky-700">Watch list</p>
                    <p class="low-text mt-2 text-3xl font-bold">{{ $watchCount }}</p>
                    <p class="low-muted mt-2 text-sm">Products with 6-10 units left.</p>
                </div>
            </div>

            <div class="low-shell rounded-3xl border p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.products.low-stock-center') }}" class="low-filter-shell rounded-2xl border p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-[1fr,240px,auto] md:items-end">
                        <div>
                            <label class="low-text mb-1 block text-sm font-semibold">Search products</label>
                            <input type="text" name="q" value="{{ $search }}" class="low-control w-full px-4 py-3" placeholder="Search low-stock products..." />
                        </div>

                        <div>
                            <label class="low-text mb-1 block text-sm font-semibold">Severity</label>
                            <select name="severity" class="low-control w-full px-4 py-3">
                                <option value="">All low-stock products</option>
                                <option value="critical" @selected($severity === 'critical')>Critical</option>
                                <option value="warning" @selected($severity === 'warning')>Warning</option>
                                <option value="watch" @selected($severity === 'watch')>Watch list</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                            <a href="{{ route('admin.products.low-stock-center') }}" class="low-soft low-text rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-gray-200">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="low-shell overflow-hidden rounded-3xl border shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                    <div>
                        <h3 class="low-text text-lg font-semibold">Restock queue</h3>
                        <p class="low-muted text-sm">The products most likely to need replenishment next.</p>
                    </div>
                    <span class="low-soft low-text rounded-full px-3 py-1 text-xs font-semibold">
                        Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}
                    </span>
                </div>

                @if ($products->isEmpty())
                    <div class="low-muted px-5 py-10 text-center text-sm">
                        No products matched the current low-stock filters.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="low-table-head text-left">
                                <tr class="low-muted text-xs uppercase tracking-[0.18em]">
                                    <th class="px-5 py-4 font-semibold">Product</th>
                                    <th class="px-5 py-4 font-semibold">Category</th>
                                    <th class="px-5 py-4 font-semibold">Stock</th>
                                    <th class="px-5 py-4 font-semibold">Priority</th>
                                    <th class="px-5 py-4 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($products as $product)
                                    @php
                                        $priority = $product->inventoryWorstStockValue() <= 2 ? 'Critical' : ($product->inventoryWorstStockValue() <= 5 ? 'Warning' : 'Watch');
                                        $priorityClasses = $priority === 'Critical'
                                            ? 'bg-rose-100 text-rose-800'
                                            : ($priority === 'Warning' ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-800');
                                    @endphp
                                    <tr class="low-row transition">
                                        <td class="px-5 py-4">
                                            <div class="low-text font-semibold">{{ $product->name }}</div>
                                            @if ($product->platformStocks->isNotEmpty())
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach ($product->platformStocks as $platformStock)
                                                        <span class="low-soft low-muted rounded-full border border-gray-200 px-2.5 py-1 text-[11px] font-medium">
                                                            {{ $platformStock->platform }}: {{ $platformStock->stock }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="low-muted px-5 py-4">{{ $product->category->name ?? '-' }}</td>
                                        <td class="low-text px-5 py-4 font-semibold">{{ $product->inventorySummaryText() }}</td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $priorityClasses }}">
                                                {{ $priority }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex justify-end">
                                                <a href="{{ route('admin.products.edit', $product) }}" class="rounded-lg border border-cyan-200 px-3 py-1.5 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-50">
                                                    Restock now
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($products->hasPages())
                    <div class="border-t border-gray-200 px-5 py-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
