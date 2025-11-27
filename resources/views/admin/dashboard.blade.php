<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-white">
                    <h3 class="text-lg font-semibold mb-4">
                        Welcome, {{ auth()->user()->name }} (Admin)
                    </h3>

                    <p class="mb-4">
                        This is the admin area. Only admins can see this page .
                    </p>

                    <ul class="list-disc list-inside space-y-1">
                        <li>Manage users (NOT CLICKABLE YET)</li>
                        <li>View system stats (NOT CLICKABLE YET)</li>
                        <li>Configure settings (NOT CLICKABLE YET)</li>
                    </ul>

                    <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center px-4 py-2 mt-4 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                        View All Users
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
