<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Users') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Search users, check roles, and manage accounts more easily.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <form method="GET" action="{{ route('admin.users.index') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/70">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Search</label>
                            <input
                                type="text"
                                name="q"
                                value="{{ $search ?? request('q') }}"
                                class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                placeholder="Search name, email, or ID..."
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Role</label>
                            <select name="role" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                                <option value="">All roles</option>
                                <option value="admin" {{ ($role ?? request('role')) === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ ($role ?? request('role')) === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                            <a href="{{ route('admin.users.index') }}" class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User accounts</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">A clearer list of users, roles, and recent signups.</p>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                        {{ $users->count() }} shown
                    </span>
                </div>

                @if ($users->isEmpty())
                    <div class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                        No users found.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-left dark:bg-gray-900/70">
                                <tr class="text-xs uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                                    <th class="px-5 py-4 font-semibold">User</th>
                                    <th class="px-5 py-4 font-semibold">Role</th>
                                    <th class="px-5 py-4 font-semibold">Joined</th>
                                    <th class="px-5 py-4 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($users as $user)
                                    <tr class="transition hover:bg-gray-50/80 dark:hover:bg-gray-900/40">
                                        <td class="px-5 py-4">
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">#{{ $user->id }} • {{ $user->email }}</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $user->role === 'admin' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="px-5 py-4">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border border-cyan-200 px-3 py-1.5 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-50 dark:border-cyan-800 dark:text-cyan-300 dark:hover:bg-cyan-900/20">Edit</a>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-800 dark:text-rose-300 dark:hover:bg-rose-900/20">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
