<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage catalogue items, stock, and pricing from one place.</p>
        </div>
    </x-slot>

    <style>
        .admin-products-page,
        .admin-products-page * {
            font-family: 'MiniPixel', sans-serif !important;
            font-weight: 400 !important;
        }

        .admin-products-page {
            color: #fff;
        }

        .admin-products-page .products-shell,
        .admin-products-page .products-filter-box,
        .admin-products-page .products-table-shell,
        .admin-products-page .products-table-head,
        .admin-products-page .products-table-row,
        .admin-products-page .products-pill,
        .admin-products-page .products-role-chip,
        .admin-products-page .products-note,
        .admin-products-page .products-status-chip,
        .admin-products-page .products-tab {
            background: #1d1d1f !important;
            border-color: #444 !important;
        }

        .admin-products-page .products-title {
            font-size: 30px !important;
            line-height: 1.1 !important;
            color: #fff !important;
        }

        .admin-products-page .products-copy,
        .admin-products-page .products-copy-sm,
        .admin-products-page .products-copy-xs,
        .admin-products-page label,
        .admin-products-page input,
        .admin-products-page select,
        .admin-products-page th,
        .admin-products-page td,
        .admin-products-page button,
        .admin-products-page a {
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        .admin-products-page .products-copy,
        .admin-products-page .products-copy-sm,
        .admin-products-page .products-copy-xs {
            color: #888 !important;
        }

        .admin-products-page .products-pill {
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        .admin-products-page label,
        .admin-products-page th,
        .admin-products-page td,
        .admin-products-page input,
        .admin-products-page select {
            color: #fff !important;
        }

        .admin-products-page .products-input,
        .admin-products-page .products-select {
            min-height: 56px;
            border: 1px solid #444 !important;
            border-radius: 18px !important;
            background: #000 !important;
            color: #fff !important;
            box-shadow: none !important;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .admin-products-page .products-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none !important;
            padding-right: 52px !important;
        }

        .admin-products-page .products-input::placeholder {
            color: #888 !important;
        }

        .admin-products-page .products-field-shell {
            position: relative;
            border-radius: 18px;
            overflow: visible;
        }

        .admin-products-page .products-field-shell::after {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            border: 1px solid transparent;
            opacity: 0;
            animation: veltrixGlow 2s infinite alternate;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .admin-products-page .products-field-shell:hover::after,
        .admin-products-page .products-field-shell:focus-within::after {
            opacity: 1;
        }

        .admin-products-page .products-field-shell:hover .products-input,
        .admin-products-page .products-field-shell:hover .products-select,
        .admin-products-page .products-field-shell:focus-within .products-input,
        .admin-products-page .products-field-shell:focus-within .products-select {
            background: #1d1d1d !important;
            border-color: transparent !important;
            outline: none !important;
        }

        .admin-products-page .products-select-wrap {
            position: relative;
        }

        .admin-products-page .products-select-wrap::after {
            content: '';
            position: absolute;
            right: 20px;
            top: 50%;
            width: 10px;
            height: 10px;
            border-right: 2px solid rgba(255, 255, 255, 0.7);
            border-bottom: 2px solid rgba(255, 255, 255, 0.7);
            transform: translateY(-65%) rotate(45deg);
            pointer-events: none;
            z-index: 2;
        }

        .admin-products-page .products-action-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 56px;
            min-width: 90px;
            padding: 0 22px;
            border: 1px solid #444 !important;
            border-radius: 18px !important;
            background: #000 !important;
            color: #fff !important;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
            text-decoration: none !important;
            overflow: visible;
            cursor: pointer;
        }

        .admin-products-page .products-action-btn::after {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            border: 1px solid transparent;
            opacity: 0;
            animation: veltrixGlow 2s infinite alternate;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .admin-products-page .products-action-btn:hover,
        .admin-products-page .products-action-btn:focus-visible {
            background: #1d1d1d !important;
            border-color: transparent !important;
            transform: translateY(-1px);
            outline: none;
        }

        .admin-products-page .products-action-btn:hover::after,
        .admin-products-page .products-action-btn:focus-visible::after {
            opacity: 1;
        }

        .admin-products-page .products-tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 50px;
            padding: 0 18px;
            border-radius: 999px;
            border: 1px solid #444;
            background: #000 !important;
            color: #fff !important;
            text-decoration: none !important;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
            position: relative;
            overflow: visible;
        }

        .admin-products-page .products-tab::after {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            border: 1px solid transparent;
            opacity: 0;
            animation: veltrixGlow 2s infinite alternate;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .admin-products-page .products-tab:hover,
        .admin-products-page .products-tab.is-active {
            background: #1d1d1d !important;
            border-color: transparent !important;
        }

        .admin-products-page .products-tab:hover::after,
        .admin-products-page .products-tab.is-active::after {
            opacity: 1;
        }

        .admin-products-page .products-table-shell {
            overflow: hidden;
        }

        .admin-products-page .products-table-row:hover {
            background: transparent !important;
        }

        .admin-products-page .products-table-row input[type="checkbox"] {
            accent-color: #22d3ee;
        }

        .admin-products-page .products-note {
            color: #888 !important;
        }

        .admin-products-page .products-platform-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 14px;
            margin-left: -20px;
        }

        .admin-products-page .products-platform-line {
            color: #fff !important;
        }

        .admin-products-page .products-stock-summary {
            display: grid;
            grid-template-columns: 140px auto;
            align-items: center;
            column-gap: 16px;
            margin-bottom: 12px;
            margin-left: -20px;
        }

        .admin-products-page .products-stock-summary .products-status-chip {
            justify-self: start;
        }

        .admin-products-page .products-stock-summary-secondary {
            margin-top: -4px;
            margin-bottom: 10px;
        }

        .admin-products-page .products-stock-cell {
            padding-left: 32px !important;
        }

        .admin-products-page .products-stock-header {
            padding-left: 0 !important;
        }

        .admin-products-page .products-status-chip {
            background: transparent !important;
            border: none !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>

    <div class="admin-products-page py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="products-shell rounded-3xl border p-5 shadow-sm">
                @php
                    $currentCategory = $categoryKey ?? request('category');
                @endphp

                <form method="GET" action="{{ route('admin.products.index') }}" class="products-filter-box rounded-2xl border p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-2 block">Search</label>
                            <div class="products-field-shell">
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ $search ?? request('q') }}"
                                    class="products-input w-full px-4 py-3"
                                    placeholder="Search name, description, platform..."
                                    autocomplete="off"
                                    autocorrect="off"
                                    autocapitalize="off"
                                    spellcheck="false"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block">Stock</label>
                            <div class="products-field-shell">
                                <div class="products-select-wrap">
                                    <select name="stock" class="products-select w-full px-4 py-3">
                                        <option value="">All stock levels</option>
                                        <option value="in_stock" {{ ($stockFilter ?? request('stock')) === 'in_stock' ? 'selected' : '' }}>In stock</option>
                                        <option value="low_stock" {{ ($stockFilter ?? request('stock')) === 'low_stock' ? 'selected' : '' }}>Low stock</option>
                                        <option value="out_of_stock" {{ ($stockFilter ?? request('stock')) === 'out_of_stock' ? 'selected' : '' }}>Out of stock</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-end gap-3">
                            @if ($currentCategory)
                                <input type="hidden" name="category" value="{{ $currentCategory }}">
                            @endif
                            <button type="submit" class="products-action-btn">Apply</button>
                            <a href="{{ $currentCategory ? route('admin.products.index', ['category' => $currentCategory]) : route('admin.products.index') }}" class="products-action-btn">Clear</a>
                        </div>
                    </div>
                </form>

                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('admin.products.index') }}" class="products-tab {{ !$currentCategory ? 'is-active' : '' }}">
                        All
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Games']) }}" class="products-tab {{ $currentCategory === 'Games' ? 'is-active' : '' }}">
                        Video Games
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Consoles and PCs']) }}" class="products-tab {{ $currentCategory === 'Consoles and PCs' ? 'is-active' : '' }}">
                        Consoles and PCs
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Accessories']) }}" class="products-tab {{ $currentCategory === 'Accessories' ? 'is-active' : '' }}">
                        Accessories
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Hardware']) }}" class="products-tab {{ $currentCategory === 'Hardware' ? 'is-active' : '' }}">
                        Hardware
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Furniture']) }}" class="products-tab {{ $currentCategory === 'Furniture' ? 'is-active' : '' }}">
                        Furniture
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Merchandise']) }}" class="products-tab {{ $currentCategory === 'Merchandise' ? 'is-active' : '' }}">
                        Merchandise
                    </a>
                    <a href="{{ route('admin.products.index', ['category' => 'Trading Cards']) }}" class="products-tab {{ $currentCategory === 'Trading Cards' ? 'is-active' : '' }}">
                        Trading Cards
                    </a>
                </div>
            </div>

            <div class="products-table-shell rounded-3xl border shadow-sm">
                <div class="flex items-center justify-between border-b border-[#444] px-5 py-4">
                    <div>
                        <h3 class="products-title">Product Inventory</h3>
                        <p class="products-copy">A cleaner view of products, stock status, and actions.</p>
                    </div>
                    <span class="products-pill rounded-full border px-3 py-2 text-white">
                        Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}
                    </span>
                </div>

                @if ($products->isEmpty())
                    <div class="products-copy px-5 py-10 text-center">
                        No products matched these filters.
                    </div>
                @else
                    <form id="bulk-products-form" method="POST" action="{{ route('admin.products.bulk') }}">
                        @csrf
                        <input type="hidden" name="action" value="delete">
                    </form>

                    <div class="border-b border-[#444] bg-[#1d1d1f] px-5 py-4">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div class="flex items-center gap-3">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" data-check-all="products" class="h-4 w-4 rounded border-[#444] bg-black">
                                    Select all
                                </label>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" form="bulk-products-form" class="products-action-btn min-w-[220px]" onclick="return confirm('Delete all selected products?');">
                                    Delete Selected
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="products-table-head text-left">
                                <tr class="uppercase tracking-[0.18em] text-[#888]">
                                    <th class="px-5 py-4">
                                        <span class="sr-only">Select</span>
                                    </th>
                                    <th class="px-5 py-4">Product</th>
                                    <th class="px-5 py-4">Category</th>
                                    <th class="px-5 py-4">Price</th>
                                    <th class="products-stock-header px-5 py-4">Stock</th>
                                    <th class="px-5 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#444]">
                                @foreach ($products as $product)
                                    <tr class="products-table-row transition">
                                        <td class="px-5 py-4">
                                            <input type="checkbox" name="selected[]" value="{{ $product->id }}" form="bulk-products-form" data-check-item="products" class="h-4 w-4 rounded border-[#444] bg-black">
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="text-white">{{ $product->name }}</div>
                                        </td>
                                        <td class="px-5 py-4 text-white">{{ $product->category->name ?? '-' }}</td>
                                        <td class="px-5 py-4 text-white">{{ number_format($product->price, 2) }} GBP</td>
                                        <td class="products-stock-cell px-5 py-4">
                                            @php
                                                $stockTextClasses = $product->inventoryStatusKey() === 'out_of_stock'
                                                    ? 'text-rose-300'
                                                    : ($product->inventoryStatusKey() === 'low_stock' ? 'text-amber-300' : 'text-emerald-300');
                                                $stockLabel = $product->inventoryStatusLabel();
                                                $outCount = $product->outOfStockPlatformCount();
                                                $lowCount = $product->lowStockPlatformCount();
                                                $inCount = $product->hasPlatformSpecificStock()
                                                    ? $product->platformStocks->filter(fn ($platformStock) => (int) $platformStock->stock > 0)->count()
                                                    : max((int) $product->stock, 0);
                                            @endphp
                                            <div class="products-stock-summary">
                                                <span class="products-status-chip rounded-full px-3 py-2 {{ $stockTextClasses }}">{{ $stockLabel }}</span>
                                                <span class="products-copy-sm">
                                                    @if ($product->hasPlatformSpecificStock() && $product->inventoryStatusKey() === 'out_of_stock')
                                                        {{ $outCount }} platform{{ $outCount === 1 ? '' : 's' }} out
                                                    @else
                                                        {{ $product->inventorySummaryText() }}
                                                    @endif
                                                </span>
                                            </div>
                                            @if ($product->hasPlatformSpecificStock() && $product->inventoryStatusKey() === 'out_of_stock' && $lowCount > 0)
                                                <div class="products-stock-summary products-stock-summary-secondary">
                                                    <span class="products-status-chip rounded-full px-3 py-2 text-amber-300">Low stock</span>
                                                    <span class="products-copy-sm">{{ $lowCount }} platform{{ $lowCount === 1 ? '' : 's' }} low</span>
                                                </div>
                                            @endif
                                            @if ($product->hasPlatformSpecificStock() && $inCount > 0)
                                                <div class="products-stock-summary products-stock-summary-secondary">
                                                    <span class="products-status-chip rounded-full px-3 py-2 text-emerald-300">In stock</span>
                                                    <span class="products-copy-sm">{{ $inCount }} platform{{ $inCount === 1 ? '' : 's' }} in stock</span>
                                                </div>
                                            @endif
                                            @if ($product->platformStocks->isNotEmpty())
                                                <div class="products-platform-list">
                                                    @foreach ($product->platformStocks as $platformStock)
                                                        <div class="products-platform-line">
                                                            {{ $platformStock->platform }}: {{ $platformStock->stock }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.products.edit', $product) }}" class="products-action-btn">
                                                    Edit
                                                </a>
                                                <button type="submit" form="delete-product-{{ $product->id }}" class="products-action-btn" onclick="return confirm('Delete this product?');">
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
                    <div class="border-t border-[#444] px-5 py-4">
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
