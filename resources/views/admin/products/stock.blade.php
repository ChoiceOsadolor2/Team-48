<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Stock Status') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Product availability follows the lowest platform stock when a product uses platform-specific inventory.
                </p>
            </div>

            <a href="{{ route('admin.products.index') }}"
               class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Manage Products
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-3xl border border-emerald-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-emerald-700">Available</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $inStockCount }}</p>
                    <p class="mt-2 text-sm text-gray-500">Products with stock greater than 0.</p>
                </div>

                <div class="rounded-3xl border border-rose-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-rose-700">Out of stock</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $outOfStockCount }}</p>
                    <p class="mt-2 text-sm text-gray-500">Products with stock equal to 0.</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-700">Total products</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $products->count() }}</p>
                    <p class="mt-2 text-sm text-gray-500">Current inventory tracked in admin.</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#3a3a3d] text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Product</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Category</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Stock</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Availability</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3a3a3d] bg-white">
                            @forelse ($products as $product)
                                @php
                                    $statusKey = $product->inventoryStatusKey();
                                    $isAvailable = $statusKey === 'in_stock';
                                    $isLowStock = $statusKey === 'low_stock';
                                @endphp
                                <tr class="{{ $statusKey === 'out_of_stock' ? 'bg-rose-50/60' : ($isLowStock ? 'bg-amber-50/60' : '') }}">
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $product->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-900">
                                        <div class="font-semibold">{{ $product->inventorySummaryText() }}</div>
                                        @if ($product->platformStocks->isNotEmpty())
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @foreach ($product->platformStocks as $platformStock)
                                                    <span class="rounded-full border border-gray-200 px-2 py-1 text-[11px] text-gray-600">
                                                        {{ $platformStock->platform }}: {{ $platformStock->stock }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusKey === 'out_of_stock' ? 'bg-rose-100 text-rose-800' : ($isLowStock ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                            {{ $product->inventoryStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="font-semibold text-blue-600 hover:text-blue-500">
                                            Edit stock
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        No products found in inventory.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
