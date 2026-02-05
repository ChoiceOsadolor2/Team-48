<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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

                    <p class="mb-4">
                        This is the admin area. Only admins can see this page.
                    </p>

                    <ul class="list-disc list-inside space-y-1 mb-6">
                        <li>Manage users</li>
                        <li>View inventory (stock)</li>
                        <li>View all orders (with user names)</li>
                    </ul>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.users.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            View All Users
                        </a>

                        <a href="{{ route('admin.products.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                            View Inventory
                        </a>

                        <a href="{{ route('admin.orders.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                            View Orders
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

