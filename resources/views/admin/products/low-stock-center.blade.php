<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Low-Stock Notification Centre</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Prioritise restocking based on urgency and current inventory risk.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-3xl border border-rose-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-rose-700">Critical</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $criticalCount }}</p>
                    <p class="mt-2 text-sm text-gray-500">Products with 0-2 units left.</p>
                </div>
                <div class="rounded-3xl border border-amber-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-amber-700">Warning</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $warningCount }}</p>
                    <p class="mt-2 text-sm text-gray-500">Products with 3-5 units left.</p>
                </div>
                <div class="rounded-3xl border border-sky-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-sky-700">Watch list</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $watchCount }}</p>
                    <p class="mt-2 text-sm text-gray-500">Products with 6-10 units left.</p>
                </div>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.products.low-stock-center') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-[1fr,240px,auto] md:items-end">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Search products</label>
                            <input type="text" name="q" value="{{ $search }}" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm" placeholder="Search low-stock products..." />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Severity</label>
                            <select name="severity" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm">
                                <option value="">All low-stock products</option>
                                <option value="critical" @selected($severity === 'critical')>Critical</option>
                                <option value="warning" @selected($severity === 'warning')>Warning</option>
                                <option value="watch" @selected($severity === 'watch')>Watch list</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                            <a href="{{ route('admin.products.low-stock-center') }}" class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-300">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Restock queue</h3>
                        <p class="text-sm text-gray-500">The products most likely to need replenishment next.</p>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                        Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}
                    </span>
                </div>

                @if ($products->isEmpty())
                    <div class="px-5 py-10 text-center text-sm text-gray-500">
                        No products matched the current low-stock filters.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-left">
                                <tr class="text-xs uppercase tracking-[0.18em] text-gray-500">
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
                                        $priority = $product->stock <= 2 ? 'Critical' : ($product->stock <= 5 ? 'Warning' : 'Watch');
                                        $priorityClasses = $priority === 'Critical'
                                            ? 'bg-rose-100 text-rose-800'
                                            : ($priority === 'Warning' ? 'bg-amber-100 text-amber-800' : 'bg-sky-100 text-sky-800');
                                    @endphp
                                    <tr class="transition hover:bg-gray-50/80">
                                        <td class="px-5 py-4">
                                            <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                                            @if ($product->platformStocks->isNotEmpty())
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach ($product->platformStocks as $platformStock)
                                                        <span class="rounded-full border border-gray-200 px-2.5 py-1 text-[11px] font-medium text-gray-600">
                                                            {{ $platformStock->platform }}: {{ $platformStock->stock }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                                        <td class="px-5 py-4 font-semibold text-gray-900">{{ $product->stock }}</td>
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
