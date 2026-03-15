<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Products') }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage catalogue items, stock, and pricing from one place.</p>
            </div>
            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-500">
                + Add Product
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                @php
                    $currentCategory = $categoryKey ?? request('category');
                @endphp

                <form method="GET" action="{{ route('admin.products.index') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/70">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Search</label>
                            <input
                                type="text"
                                name="q"
                                value="{{ $search ?? request('q') }}"
                                class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                placeholder="Search name, description, platform..."
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Stock</label>
                            <select name="stock" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                                <option value="">All stock levels</option>
                                <option value="in_stock" {{ ($stockFilter ?? request('stock')) === 'in_stock' ? 'selected' : '' }}>In stock</option>
                                <option value="low_stock" {{ ($stockFilter ?? request('stock')) === 'low_stock' ? 'selected' : '' }}>Low stock</option>
                                <option value="out_of_stock" {{ ($stockFilter ?? request('stock')) === 'out_of_stock' ? 'selected' : '' }}>Out of stock</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            @if ($currentCategory)
                                <input type="hidden" name="category" value="{{ $currentCategory }}">
                            @endif
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                            <a href="{{ $currentCategory ? route('admin.products.index', ['category' => $currentCategory]) : route('admin.products.index') }}" class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">Clear</a>
                        </div>
                    </div>
                </form>

                <div class="mt-4 flex flex-wrap gap-2 text-sm">
                    <a href="{{ route('admin.products.index') }}"
                       class="rounded-full border px-3 py-1.5 transition {{ !$currentCategory ? 'border-cyan-500 bg-cyan-50 font-semibold text-cyan-700 dark:border-cyan-400 dark:bg-cyan-900/30 dark:text-cyan-200' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                        All
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Games']) }}"
                       class="rounded-full border px-3 py-1.5 transition {{ $currentCategory === 'Games' ? 'border-cyan-500 bg-cyan-50 font-semibold text-cyan-700 dark:border-cyan-400 dark:bg-cyan-900/30 dark:text-cyan-200' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                        Games
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Consoles and PCs']) }}"
                       class="rounded-full border px-3 py-1.5 transition {{ $currentCategory === 'Consoles and PCs' ? 'border-cyan-500 bg-cyan-50 font-semibold text-cyan-700 dark:border-cyan-400 dark:bg-cyan-900/30 dark:text-cyan-200' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                        Consoles and PCs
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Accessories']) }}"
                       class="rounded-full border px-3 py-1.5 transition {{ $currentCategory === 'Accessories' ? 'border-cyan-500 bg-cyan-50 font-semibold text-cyan-700 dark:border-cyan-400 dark:bg-cyan-900/30 dark:text-cyan-200' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                        Accessories
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Hardware']) }}"
                       class="rounded-full border px-3 py-1.5 transition {{ $currentCategory === 'Hardware' ? 'border-cyan-500 bg-cyan-50 font-semibold text-cyan-700 dark:border-cyan-400 dark:bg-cyan-900/30 dark:text-cyan-200' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                        Hardware
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Furniture']) }}"
                       class="rounded-full border px-3 py-1.5 transition {{ $currentCategory === 'Furniture' ? 'border-cyan-500 bg-cyan-50 font-semibold text-cyan-700 dark:border-cyan-400 dark:bg-cyan-900/30 dark:text-cyan-200' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                        Furniture
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Merchandise']) }}"
                       class="rounded-full border px-3 py-1.5 transition {{ $currentCategory === 'Merchandise' ? 'border-cyan-500 bg-cyan-50 font-semibold text-cyan-700 dark:border-cyan-400 dark:bg-cyan-900/30 dark:text-cyan-200' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                        Merchandise
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Trading Cards']) }}"
                       class="rounded-full border px-3 py-1.5 transition {{ $currentCategory === 'Trading Cards' ? 'border-cyan-500 bg-cyan-50 font-semibold text-cyan-700 dark:border-cyan-400 dark:bg-cyan-900/30 dark:text-cyan-200' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                        Trading Cards
                    </a>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Product inventory</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">A cleaner view of products, stock status, and actions.</p>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                        Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}
                    </span>
                </div>

                @if ($products->isEmpty())
                    <div class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                        No products matched these filters.
                    </div>
                @else
                    <form id="bulk-products-form" method="POST" action="{{ route('admin.products.bulk') }}">
                        @csrf
                    </form>
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4 dark:border-gray-700 dark:bg-gray-900/70">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div class="flex items-center gap-3">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        <input type="checkbox" data-check-all="products" class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                        Select all
                                    </label>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Choose products, then run a bulk action.</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <select name="action" form="bulk-products-form" class="rounded-xl border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                                        <option value="">Bulk action</option>
                                        <option value="delete">Delete selected</option>
                                    </select>
                                    <button type="submit" form="bulk-products-form" class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-800 dark:bg-cyan-600 dark:hover:bg-cyan-500" onclick="return confirm('Apply this bulk action to the selected products?');">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-left dark:bg-gray-900/70">
                                <tr class="text-xs uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                                    <th class="px-5 py-4 font-semibold">
                                        <span class="sr-only">Select</span>
                                    </th>
                                    <th class="px-5 py-4 font-semibold">Product</th>
                                    <th class="px-5 py-4 font-semibold">Category</th>
                                    <th class="px-5 py-4 font-semibold">Price</th>
                                    <th class="px-5 py-4 font-semibold">Stock</th>
                                    <th class="px-5 py-4 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($products as $product)
                                    <tr class="transition hover:bg-gray-50/80 dark:hover:bg-gray-900/40">
                                        <td class="px-5 py-4">
                                            <input type="checkbox" name="selected[]" value="{{ $product->id }}" form="bulk-products-form" data-check-item="products" class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}</div>
                                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $product->platform ?: 'No platform set' }}</div>
                                            @if ($product->platformStocks->isNotEmpty())
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach ($product->platformStocks as $platformStock)
                                                        <span class="rounded-full border border-gray-200 px-2.5 py-1 text-[11px] font-medium text-gray-600 dark:border-gray-700 dark:text-gray-300">
                                                            {{ $platformStock->platform }}: {{ $platformStock->stock }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $product->category->name ?? '-' }}</td>
                                        <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">£{{ number_format($product->price, 2) }}</td>
                                        <td class="px-5 py-4">
                                            @php
                                                $stockClasses = $product->stock <= 0
                                                    ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200'
                                                    : ($product->stock <= 5
                                                        ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200'
                                                        : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200');
                                                $stockLabel = $product->stock <= 0
                                                    ? 'Out of stock'
                                                    : ($product->stock <= 5 ? 'Low stock' : 'In stock');
                                            @endphp
                                            <div class="mb-3 flex items-center gap-3">
                                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $stockClasses }}">{{ $stockLabel }}</span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $product->stock }} units</span>
                                            </div>
                                            @if ($product->platformStocks->isNotEmpty())
                                                <div class="rounded-lg border border-dashed border-cyan-300 bg-cyan-50/60 px-3 py-2 text-xs text-cyan-700 dark:border-cyan-800 dark:bg-cyan-900/20 dark:text-cyan-200">
                                                    Platform quantities are managed from the edit page.
                                                </div>
                                            @else
                                                <form method="POST" action="{{ route('admin.products.update-stock', $product) }}" class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="q" value="{{ $search ?? request('q') }}">
                                                    <input type="hidden" name="filter_stock" value="{{ $stockFilter ?? request('stock') }}">
                                                    <input type="hidden" name="category_filter" value="{{ $currentCategory }}">
                                                    <input
                                                        type="number"
                                                        name="stock"
                                                        min="0"
                                                        value="{{ $product->stock }}"
                                                        class="w-24 rounded-lg border border-gray-300 px-3 py-1.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                                    >
                                                    <button type="submit" class="rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-gray-800 dark:bg-cyan-600 dark:hover:bg-cyan-500">
                                                        Save
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                   class="rounded-lg border border-cyan-200 px-3 py-1.5 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-50 dark:border-cyan-800 dark:text-cyan-300 dark:hover:bg-cyan-900/20">
                                                    Edit
                                                </a>
                                                <button type="submit" form="delete-product-{{ $product->id }}" class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-800 dark:text-rose-300 dark:hover:bg-rose-900/20" onclick="return confirm('Delete this product?');">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @foreach ($products as $product)
                        <form id="delete-product-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                @endif

                @if ($products instanceof \Illuminate\Contracts\Pagination\Paginator && $products->hasPages())
                    <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const master = document.querySelector('[data-check-all="products"]');
            const items = document.querySelectorAll('[data-check-item="products"]');
            if (!master || !items.length) return;

            master.addEventListener('change', function () {
                items.forEach((item) => item.checked = master.checked);
            });
        });
    </script>
</x-app-layout>
