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

        .admin-users-page .users-role-chip {
            background: #1d1d1f !important;
            border-color: #444 !important;
        }

        .admin-users-page .users-shell {
            background: #fff !important;
            border-color: #e5e7eb !important;
        }

        .admin-users-page .users-filter-box {
            background: #f9fafb !important;
            border-color: #e5e7eb !important;
        }

        .admin-users-page .users-title {
            font-size: 30px !important;
            font-weight: 400 !important;
            line-height: 1.1 !important;
            color: #fff !important;
        }

        .admin-users-page .users-page-intro {
            margin-bottom: 8px;
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
        .admin-users-page input {
            color: #111827 !important;
            font-weight: 400 !important;
        }

        .admin-users-page .users-input {
            min-height: 56px;
            border: 1px solid #d1d5db !important;
            border-radius: 18px !important;
            background: #fff !important;
            color: #111827 !important;
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
            border-right: 2px solid rgba(17, 24, 39, 0.7);
            border-bottom: 2px solid rgba(17, 24, 39, 0.7);
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
            border: 1px solid #d1d5db !important;
            border-radius: 18px !important;
            background: #fff !important;
            color: #111827 !important;
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
            background: #f9fafb !important;
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
            background: #fff !important;
            border-color: #e5e7eb !important;
        }

        .admin-users-page .users-filter-grid {
            align-items: end;
        }

        .admin-users-page .users-filter-actions {
            justify-content: flex-start;
        }

        .admin-users-page .users-table-head {
            background: #f9fafb !important;
        }

        .admin-users-page .users-table-row {
            transition: background 0.2s ease;
            background: #fff !important;
        }

        .admin-users-page .users-table-row:hover {
            background: rgba(15, 23, 42, 0.035) !important;
        }

        .admin-users-page .users-table-shell th {
            color: #6b7280 !important;
        }

        .admin-users-page .users-table-shell td,
        .admin-users-page .users-table-shell .text-white {
            color: #111827 !important;
        }

        .admin-users-page .users-table-shell .users-copy,
        .admin-users-page .users-table-shell .users-copy-sm,
        .admin-users-page .users-table-shell .users-copy-xs {
            color: #6b7280 !important;
        }

        .admin-users-page .users-table-shell .users-pill {
            background: #f3f4f6 !important;
            border-color: #e5e7eb !important;
            color: #374151 !important;
        }

        .admin-users-page .users-table-shell .users-role-chip {
            background: #f3f4f6 !important;
            border-color: #e5e7eb !important;
            color: #374151 !important;
        }

        .admin-users-page .users-table-shell .users-action-btn {
            background: #fff !important;
            color: #111827 !important;
            border-color: #d1d5db !important;
        }

        .admin-users-page .users-table-shell .users-action-btn:hover,
        .admin-users-page .users-table-shell .users-action-btn:focus-visible {
            background: #f9fafb !important;
        }

        html[data-theme="dark"] .admin-users-page .users-table-shell {
            background: #1f2937 !important;
            border-color: #374151 !important;
        }

        html[data-theme="dark"] .admin-users-page .users-shell {
            background: #1f2937 !important;
            border-color: #374151 !important;
        }

        html[data-theme="dark"] .admin-users-page .users-filter-box {
            background: rgba(17, 24, 39, 0.78) !important;
            border-color: #374151 !important;
        }

        html[data-theme="dark"] .admin-users-page label,
        html[data-theme="dark"] .admin-users-page input {
            color: #f9fafb !important;
        }

        html[data-theme="dark"] .admin-users-page .users-input {
            background: #1f2937 !important;
            color: #f9fafb !important;
            border-color: #374151 !important;
        }

        html[data-theme="dark"] .admin-users-page .users-select-wrap::after {
            border-right-color: rgba(249, 250, 251, 0.7);
            border-bottom-color: rgba(249, 250, 251, 0.7);
        }

        html[data-theme="dark"] .admin-users-page .users-table-head {
            background: rgba(17, 24, 39, 0.78) !important;
        }

        html[data-theme="dark"] .admin-users-page .users-table-row {
            background: #1f2937 !important;
        }

        html[data-theme="dark"] .admin-users-page .users-table-row:hover {
            background: rgba(255, 255, 255, 0.03) !important;
        }

        html[data-theme="dark"] .admin-users-page .users-table-shell th,
        html[data-theme="dark"] .admin-users-page .users-table-shell .users-copy,
        html[data-theme="dark"] .admin-users-page .users-table-shell .users-copy-sm,
        html[data-theme="dark"] .admin-users-page .users-table-shell .users-copy-xs {
            color: #9ca3af !important;
        }

        html[data-theme="dark"] .admin-users-page .users-table-shell td,
        html[data-theme="dark"] .admin-users-page .users-table-shell .text-white {
            color: #f9fafb !important;
        }

        html[data-theme="dark"] .admin-users-page .users-table-shell .users-pill,
        html[data-theme="dark"] .admin-users-page .users-table-shell .users-role-chip {
            background: #374151 !important;
            border-color: #4b5563 !important;
            color: #e5e7eb !important;
        }

        html[data-theme="dark"] .admin-users-page .users-table-shell .users-action-btn {
            background: #111827 !important;
            color: #f9fafb !important;
            border-color: #374151 !important;
        }

        html[data-theme="dark"] .admin-users-page .users-table-shell .users-action-btn:hover,
        html[data-theme="dark"] .admin-users-page .users-table-shell .users-action-btn:focus-visible {
            background: #1f2937 !important;
        }

        @media (min-width: 768px) {
            .admin-users-page .users-page-intro {
                min-height: 58px;
                display: flex;
                align-items: center;
                margin-top: -90px;
                margin-left: 210px;
                margin-bottom: 24px;
            }
        }
    </style>

    <div class="admin-users-page py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="users-page-intro">
                <div>
                    <h1 class="flex items-center gap-3 text-[1.7rem] font-bold text-gray-900 dark:text-white">
                        <svg class="h-7 w-7 text-cyan-600 dark:text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Users</span>
                    </h1>
                    <p class="mt-1.5 text-[0.98rem] text-gray-500 dark:text-gray-400">Search accounts, review roles, and manage customer access more efficiently.</p>
                </div>
            </div>

            <div class="users-shell rounded-3xl border p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.users.index') }}" class="users-filter-box rounded-2xl border p-4">
                    <div class="users-filter-grid grid grid-cols-1 gap-4 md:grid-cols-[minmax(0,1.4fr)_minmax(220px,0.8fr)_auto]">
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

                        <div class="users-filter-actions flex items-end gap-3">
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
                            <tbody class="divide-y divide-[#3a3a3d]">
                                @foreach ($users as $user)
                                    <tr class="users-table-row transition">
                                        <td class="px-5 py-5">
                                            <div class="text-white">{{ $user->name }}</div>
                                            <div class="users-copy-xs mt-1">#{{ $user->id }} • {{ $user->email }}</div>
                                        </td>
                                        <td class="px-5 py-5">
                                            <span class="users-role-chip inline-flex items-center rounded-full border px-3 py-2 text-white">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-5 text-white">{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="px-5 py-5">
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
