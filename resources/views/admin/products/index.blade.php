<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4">
                        <a href="{{ route('admin.products.create') }}"
                           class="inline-block px-4 py-2 bg-blue-600 text-white rounded">
                            + Add Product
                        </a>
                    </div>

                    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-6 rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Search</label>
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ $search ?? request('q') }}"
                                    class="w-full rounded border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-800"
                                    placeholder="Search name, description, platform..."
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-1">Stock</label>
                                <select name="stock" class="w-full rounded border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                                    <option value="">All stock levels</option>
                                    <option value="in_stock" {{ ($stockFilter ?? request('stock')) === 'in_stock' ? 'selected' : '' }}>In stock</option>
                                    <option value="low_stock" {{ ($stockFilter ?? request('stock')) === 'low_stock' ? 'selected' : '' }}>Low stock</option>
                                    <option value="out_of_stock" {{ ($stockFilter ?? request('stock')) === 'out_of_stock' ? 'selected' : '' }}>Out of stock</option>
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                @if($currentCategory)
                                    <input type="hidden" name="category" value="{{ $currentCategory }}">
                                @endif
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Apply</button>
                                <a href="{{ $currentCategory ? route('admin.products.index', ['category' => $currentCategory]) : route('admin.products.index') }}" class="px-4 py-2 bg-gray-200 rounded dark:bg-gray-700">Clear</a>
                            </div>
                        </div>
                    </form>

                    @php
                        // Comes from controller: 'Games', 'Consoles and PCs', etc.
                        $currentCategory = $categoryKey ?? request('category');
                    @endphp

                    {{-- Category tabs --}}
                    <div class="mb-6 flex flex-wrap gap-1 text-xs">
                        {{-- All --}}
                        <a href="{{ route('admin.products.index') }}"
                           class="px-3 py-1 border rounded-t
                                  {{ !$currentCategory ? 'font-semibold bg-gray-200 dark:bg-gray-700' : 'bg-gray-100 dark:bg-gray-900' }}">
                            All
                        </a>

                        {{-- Games -> key "Games" --}}
                        <a href="{{ route('admin.products.index', ['category' => 'Games']) }}"
                           class="px-3 py-1 border rounded-t
                                  {{ $currentCategory === 'Games' ? 'font-semibold bg-gray-200 dark:bg-gray-700' : 'bg-gray-100 dark:bg-gray-900' }}">
                            Games
                        </a>

                        {{-- Consoles and PCs -> key "Consoles and PCs" --}}
                        <a href="{{ route('admin.products.index', ['category' => 'Consoles and PCs']) }}"
                           class="px-3 py-1 border rounded-t
                                  {{ $currentCategory === 'Consoles and PCs' ? 'font-semibold bg-gray-200 dark:bg-gray-700' : 'bg-gray-100 dark:bg-gray-900' }}">
                            Consoles and PCs
                        </a>

                        {{-- Accessories -> key "Accessories" --}}
                        <a href="{{ route('admin.products.index', ['category' => 'Accessories']) }}"
                           class="px-3 py-1 border rounded-t
                                  {{ $currentCategory === 'Accessories' ? 'font-semibold bg-gray-200 dark:bg-gray-700' : 'bg-gray-100 dark:bg-gray-900' }}">
                            Accessories
                        </a>

                        {{-- Hardware -> key "Hardware" (chairs + monitors) --}}
                        <a href="{{ route('admin.products.index', ['category' => 'Hardware']) }}"
                           class="px-3 py-1 border rounded-t
                                  {{ $currentCategory === 'Hardware' ? 'font-semibold bg-gray-200 dark:bg-gray-700' : 'bg-gray-100 dark:bg-gray-900' }}">
                            Hardware
                        </a>
                    </div>

                    @if ($products->isEmpty())
                        <p>No products yet.</p>
                    @else
                        <table class="w-full border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-gray-700">
                                    <th class="text-left p-2">Name</th>
                                    <th class="text-left p-2">Category</th>
                                    <th class="text-left p-2">Price</th>
                                    <th class="text-left p-2">Stock</th>
                                    <th class="text-left p-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr class="border-b border-gray-700">
                                        <td class="p-2">{{ $product->name }}</td>
                                        <td class="p-2">{{ $product->category->name ?? '-' }}</td>
                                        <td class="p-2">£{{ number_format($product->price, 2) }}</td>
                                        <td class="p-2">{{ $product->stock }}</td>
                                        <td class="p-2 flex gap-3">
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="text-blue-400 hover:underline">Edit</a>

                                            <form action="{{ route('admin.products.destroy', $product) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:underline">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
