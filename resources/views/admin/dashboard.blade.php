<x-admin-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-semibold mb-4">
                        Welcome, {{ auth()->user()->name }} (Admin)
                    </h3>

                    <p class="mb-6">
                        This is your admin dashboard. Use the tools below to manage the system.
                    </p>


                    {{-- QUICK LINKS --}}
                    <h3 class="text-md font-semibold mb-2">Admin Tools</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

                        <a href="{{ route('products.index') }}"
                            class="block p-4 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-500">
                            Manage Products
                        </a>

                        <a href="{{ route('categories.index') }}"
                            class="block p-4 bg-purple-600 text-white rounded-md shadow hover:bg-purple-500">
                            Manage Categories
                        </a>

                        <a href="{{ route('inventory.index') }}"
                            class="block p-4 bg-green-600 text-white rounded-md shadow hover:bg-green-500">
                            Inventory Management
                        </a>

                        <a href="{{ route('inventory.logs') }}"
                            class="block p-4 bg-yellow-600 text-white rounded-md shadow hover:bg-yellow-500">
                            Stock Movement Logs
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                            class="block p-4 bg-blue-600 text-white rounded-md shadow hover:bg-blue-500">
                            View All Users
                        </a>

                    </div>


                    {{-- LOW STOCK ALERTS --}}
                    <h3 class="text-md font-semibold mb-3">Low Stock Alerts</h3>

                    @if($lowStock->count() === 0)
                        <p class="text-green-500">All stock levels are healthy ✔️</p>
                    @else
                        <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded mb-4">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($lowStock as $item)
                                    <li>
                                        <strong>{{ $item->product->name }}</strong> —
                                        only <strong>{{ $item->quantity }}</strong> left!
                                        <a href="{{ route('inventory.edit', $item->id) }}"
                                            class="underline text-blue-600 ml-2">Adjust stock</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

</x-admin-layout>
