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
                    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Search</label>
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ $search ?? request('q') }}"
                                    class="w-full rounded border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-800"
                                    placeholder="Search name, email, or ID..."
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-1">Role</label>
                                <select name="role" class="w-full rounded border border-gray-300 px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                                    <option value="">All roles</option>
                                    <option value="admin" {{ ($role ?? request('role')) === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ ($role ?? request('role')) === 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Apply</button>
                                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 rounded dark:bg-gray-700">Clear</a>
                            </div>
                        </div>
                    </form>

                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-gray-700 text-white">
                            <tr>
                                <th class="px-3 py-2">ID</th>
                                <th class="px-3 py-2">Name</th>
                                <th class="px-3 py-2">Email</th>
                                <th class="px-3 py-2">Role</th>
                                <th class="px-3 py-2">Created</th>
                                <th class="px-3 py-2 text-right">Actions</th>
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
                                    <td class="px-3 py-2 text-right">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-500 hover:text-blue-400 mr-3">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (session('success'))
                        <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mt-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($users->isEmpty())
                        <p class="mt-4 text-sm text-gray-400">No users found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
