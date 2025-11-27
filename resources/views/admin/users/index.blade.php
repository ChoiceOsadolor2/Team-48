<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-gray-700 text-white">
                            <tr>
                                <th class="px-3 py-2">ID</th>
                                <th class="px-3 py-2">Name</th>
                                <th class="px-3 py-2">Email</th>
                                <th class="px-3 py-2">Role</th>
                                <th class="px-3 py-2">Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="border-b border-gray-700 text-white">
                                    <td class="px-3 py-2">{{ $user->id }}</td>
                                    <td class="px-3 py-2">{{ $user->name }}</td>
                                    <td class="px-3 py-2">{{ $user->email }}</td>
                                    <td class="px-3 py-2">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                            {{ $user->role === 'admin'
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100'
                                                : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">{{ $user->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($users->isEmpty())
                        <p class="mt-4 text-sm text-gray-400">No users found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
