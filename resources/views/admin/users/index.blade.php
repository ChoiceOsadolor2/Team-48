<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Users') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Search users, check roles, and manage accounts more easily.</p>
        </div>
    </x-slot>

    <style>
        .admin-users-page,
        .admin-users-page * {
            font-family: 'MiniPixel', sans-serif !important;
        }

        .admin-users-page {
            color: #fff;
        }

        .admin-users-page .users-shell,
        .admin-users-page .users-filter-box,
        .admin-users-page .users-table-shell,
        .admin-users-page .users-table-head,
        .admin-users-page .users-table-row,
        .admin-users-page .users-role-chip,
        .admin-users-page .users-pill {
            background: #1d1d1f !important;
            border-color: #444 !important;
        }

        .admin-users-page .users-title {
            font-size: 30px !important;
            font-weight: 400 !important;
            line-height: 1.1 !important;
            color: #fff !important;
        }

        .admin-users-page .users-copy,
        .admin-users-page .users-copy-sm,
        .admin-users-page .users-copy-xs,
        .admin-users-page label,
        .admin-users-page input,
        .admin-users-page th,
        .admin-users-page td,
        .admin-users-page .users-role-chip,
        .admin-users-page .users-pill {
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        .admin-users-page .users-copy,
        .admin-users-page .users-copy-sm,
        .admin-users-page .users-copy-xs {
            color: #888 !important;
        }

        .admin-users-page label,
        .admin-users-page th,
        .admin-users-page td,
        .admin-users-page input {
            color: #fff !important;
            font-weight: 400 !important;
        }

        .admin-users-page .users-input {
            min-height: 56px;
            border: 1px solid #444 !important;
            border-radius: 18px !important;
            background: #000 !important;
            color: #fff !important;
            box-shadow: none !important;
            position: relative;
            z-index: 1;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .admin-users-page select.users-input {
            font-size: 20px !important;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none !important;
            padding-right: 52px !important;
        }

        .admin-users-page .users-select-wrap {
            position: relative;
        }

        .admin-users-page .users-select-wrap::after {
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

        .admin-users-page .users-input::placeholder {
            color: #888 !important;
        }

        .admin-users-page .users-field-shell {
            position: relative;
            border-radius: 18px;
            overflow: visible;
        }

        .admin-users-page .users-field-shell::after {
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

        .admin-users-page .users-field-shell:hover::after,
        .admin-users-page .users-field-shell:focus-within::after {
            opacity: 1;
        }

        .admin-users-page .users-field-shell:hover .users-input,
        .admin-users-page .users-field-shell:focus-within .users-input {
            background: #1d1d1d !important;
            border-color: transparent !important;
            outline: none !important;
        }

        .admin-users-page .users-action-btn {
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
            font-size: 20px !important;
            overflow: visible;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
        }

        .admin-users-page .font-semibold,
        .admin-users-page .font-bold,
        .admin-users-page .font-extrabold,
        .admin-users-page strong,
        .admin-users-page b {
            font-weight: 400 !important;
        }

        .admin-users-page .users-action-btn::after {
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

        .admin-users-page .users-action-btn:hover,
        .admin-users-page .users-action-btn:focus-visible {
            background: #1d1d1d !important;
            border-color: transparent !important;
            outline: none;
            transform: translateY(-1px);
        }

        .admin-users-page .users-action-btn:hover::after,
        .admin-users-page .users-action-btn:focus-visible::after {
            opacity: 1;
        }

        .admin-users-page .users-table-shell {
            overflow: hidden;
        }
    </style>

    <div class="admin-users-page py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="users-shell rounded-3xl border p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.users.index') }}" class="users-filter-box rounded-2xl border p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-2 block">Search</label>
                            <div class="users-field-shell">
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ $search ?? request('q') }}"
                                    class="users-input w-full px-4 py-3"
                                    placeholder="Search name, email, or ID..."
                                    autocomplete="off"
                                    autocorrect="off"
                                    autocapitalize="off"
                                    spellcheck="false"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block">Role</label>
                            <div class="users-field-shell">
                                <div class="users-select-wrap">
                                    <select name="role" class="users-input w-full px-4 py-3">
                                        <option value="">All roles</option>
                                        <option value="admin" {{ ($role ?? request('role')) === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ ($role ?? request('role')) === 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-end gap-3">
                            <button type="submit" class="users-action-btn">Apply</button>
                            <a href="{{ route('admin.users.index') }}" class="users-action-btn">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="users-table-shell rounded-3xl border shadow-sm">
                <div class="flex items-center justify-between border-b border-[#444] px-5 py-4">
                    <div>
                        <h3 class="users-title">User accounts</h3>
                        <p class="users-copy">A list of users and roles</p>
                    </div>
                    <span class="users-pill rounded-full border px-3 py-2 text-white">
                        Showing {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                    </span>
                </div>

                @if ($users->isEmpty())
                    <div class="users-copy px-5 py-10 text-center">
                        No users found.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="users-table-head text-left">
                                <tr class="uppercase tracking-[0.18em] text-[#888]">
                                    <th class="px-5 py-4 font-semibold">User</th>
                                    <th class="px-5 py-4 font-semibold">Role</th>
                                    <th class="px-5 py-4 font-semibold">Joined</th>
                                    <th class="px-5 py-4 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#444]">
                                @foreach ($users as $user)
                                    <tr class="users-table-row transition">
                                        <td class="px-5 py-4">
                                            <div class="text-white">{{ $user->name }}</div>
                                            <div class="users-copy-xs mt-1">#{{ $user->id }} • {{ $user->email }}</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="users-role-chip inline-flex items-center rounded-full border px-3 py-2 text-white">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-white">{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="px-5 py-4">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.users.edit', $user) }}" class="users-action-btn">Edit</a>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="users-action-btn">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($users instanceof \Illuminate\Contracts\Pagination\Paginator && $users->hasPages())
                    <div class="border-t border-[#444] px-5 py-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
