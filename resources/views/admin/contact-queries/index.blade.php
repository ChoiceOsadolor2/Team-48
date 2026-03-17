<x-app-layout>
    <style>
        .admin-contact-queries-page,
        .admin-contact-queries-page * {
            font-family: 'MiniPixel', sans-serif !important;
            font-weight: 400 !important;
        }

        .admin-contact-queries-page h3 {
            font-size: 30px !important;
            line-height: 1.1 !important;
        }

        .admin-contact-queries-page p,
        .admin-contact-queries-page label,
        .admin-contact-queries-page input,
        .admin-contact-queries-page select,
        .admin-contact-queries-page th,
        .admin-contact-queries-page td,
        .admin-contact-queries-page button,
        .admin-contact-queries-page a {
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        .admin-contact-queries-page .page-intro {
            margin-bottom: 8px;
        }

        .admin-contact-queries-page .page-intro-title {
            font-size: 30px !important;
            line-height: 1.1 !important;
            color: #111827;
        }

        .admin-contact-queries-page .page-intro-copy {
            margin-top: 8px;
            color: #6b7280 !important;
        }

        @media (min-width: 768px) {
            .admin-contact-queries-page .page-intro {
                min-height: 58px;
                display: flex;
                align-items: center;
                margin-top: -90px;
                margin-left: 210px;
                margin-bottom: 24px;
            }
        }

        .admin-contact-queries-page input,
        .admin-contact-queries-page select {
            min-height: 56px;
            border-radius: 18px !important;
            padding: 0 16px !important;
        }

        .admin-contact-queries-page .rounded-xl {
            border-radius: 18px !important;
        }

        .admin-contact-queries-page button,
        .admin-contact-queries-page a.rounded-xl {
            min-height: 56px;
            padding: 0 22px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .admin-contact-queries-page .rounded-lg {
            border-radius: 18px !important;
        }

        .admin-contact-queries-page .queries-filter-grid {
            align-items: end;
        }

        .admin-contact-queries-page .queries-filter-actions {
            justify-content: flex-start;
        }

        .admin-contact-queries-page .queries-table-head {
            background: #f8fafc;
        }

        .admin-contact-queries-page .queries-filter-shell {
            background: #fff;
            border-color: #e5e7eb;
        }

        .admin-contact-queries-page .queries-filter-form {
            background: #f9fafb;
            border-color: #e5e7eb;
        }

        .admin-contact-queries-page .queries-input,
        .admin-contact-queries-page .queries-select,
        .admin-contact-queries-page .queries-bulk-select {
            background: #fff;
            border-color: #d1d5db;
            color: #111827;
        }

        .admin-contact-queries-page .queries-bulk-bar {
            background: #f9fafb;
            border-color: #e5e7eb;
        }

        .admin-contact-queries-page .queries-table-shell {
            background: #1f2937;
            border-color: #374151;
        }

        .admin-contact-queries-page .queries-table-shell h3,
        .admin-contact-queries-page .queries-table-shell td,
        .admin-contact-queries-page .queries-table-shell p,
        .admin-contact-queries-page .queries-table-shell span,
        .admin-contact-queries-page .queries-table-shell th {
            color: #f9fafb !important;
        }

        .admin-contact-queries-page .queries-table-shell .queries-muted {
            color: #9ca3af !important;
        }

        .admin-contact-queries-page .queries-table-shell .queries-count-pill {
            background: rgba(255, 255, 255, 0.92);
            color: #111827 !important;
        }

        .admin-contact-queries-page .queries-table-shell .queries-bulk-bar,
        .admin-contact-queries-page .queries-table-shell .queries-bulk-bar label,
        .admin-contact-queries-page .queries-table-shell .queries-bulk-bar span,
        .admin-contact-queries-page .queries-table-shell .queries-bulk-bar p,
        .admin-contact-queries-page .queries-table-shell .queries-bulk-bar select,
        .admin-contact-queries-page .queries-table-shell .queries-bulk-bar option {
            color: #f9fafb !important;
        }

        .admin-contact-queries-page .queries-table-shell .queries-status-badge--resolved {
            background: #d1fae5;
            color: #065f46 !important;
        }

        .admin-contact-queries-page .queries-table-shell .queries-status-badge--unresolved {
            background: #fef3c7;
            color: #92400e !important;
        }

        .admin-contact-queries-page .queries-toggle-wrap {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            min-height: 56px;
            padding: 0 16px;
            border-radius: 18px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
        }

        .admin-contact-queries-page .queries-toggle-label {
            color: #111827;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .admin-contact-queries-page .queries-toggle-btn {
            border: 0;
            background: transparent;
            padding: 0;
            line-height: 1;
            cursor: pointer;
        }

        .admin-contact-queries-page .queries-row {
            transition: background 0.2s ease;
        }

        .admin-contact-queries-page .queries-row:hover {
            background: rgba(15, 23, 42, 0.035);
        }

        .admin-contact-queries-page .queries-row td {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }

        html[data-theme="dark"] .admin-contact-queries-page .queries-table-head {
            background: rgba(17, 24, 39, 0.78);
        }

        html[data-theme="dark"] .admin-contact-queries-page .page-intro-title {
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-contact-queries-page .page-intro-copy {
            color: #9ca3af !important;
        }

        html[data-theme="dark"] .admin-contact-queries-page .queries-filter-shell {
            background: #1f2937;
            border-color: #374151;
        }

        html[data-theme="dark"] .admin-contact-queries-page .queries-filter-form {
            background: rgba(17, 24, 39, 0.78);
            border-color: #374151;
        }

        html[data-theme="dark"] .admin-contact-queries-page .queries-input,
        html[data-theme="dark"] .admin-contact-queries-page .queries-select,
        html[data-theme="dark"] .admin-contact-queries-page .queries-bulk-select {
            background: #1f2937;
            border-color: #374151;
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-contact-queries-page .queries-bulk-bar {
            background: rgba(17, 24, 39, 0.78);
            border-color: #374151;
        }

        html[data-theme="dark"] .admin-contact-queries-page .queries-toggle-wrap {
            border-color: #374151;
            background: rgba(17, 24, 39, 0.78);
        }

        html[data-theme="dark"] .admin-contact-queries-page .queries-toggle-label {
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-contact-queries-page .queries-row:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .queries-header-title,
        .queries-header-copy {
            color: #f9fafb !important;
        }
    </style>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="queries-header-title font-semibold text-xl leading-tight">Contact Queries</h2>
                <p class="queries-header-copy mt-1 text-sm">Messages submitted through the Contact Us forms.</p>
            </div>
        </div>
    </x-slot>

    <div class="admin-contact-queries-page py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="page-intro">
                <div>
                    <h1 class="page-intro-title flex items-center gap-3">
                        <svg class="h-7 w-7 text-cyan-600 dark:text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>Contact Queries</span>
                    </h1>
                    <p class="page-intro-copy">View the support inbox, filter customer messages, and resolve requests faster.</p>
                </div>
            </div>
            <div class="queries-filter-shell rounded-3xl border p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.contact-queries.index') }}" class="queries-filter-form rounded-2xl border p-4">
                    <div class="queries-filter-grid grid grid-cols-1 gap-4 md:grid-cols-[minmax(0,1.35fr)_minmax(220px,0.85fr)_auto]">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Search</label>
                            <input
                                type="text"
                                name="q"
                                value="{{ $search ?? request('q') }}"
                                class="queries-input w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm"
                                placeholder="Search name, email, subject..."
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Status</label>
                            <select name="status" class="queries-select w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm">
                                <option value="">All queries</option>
                                <option value="resolved" {{ ($status ?? request('status')) === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="unresolved" {{ ($status ?? request('status')) === 'unresolved' ? 'selected' : '' }}>Unresolved</option>
                            </select>
                        </div>

                        <div class="queries-filter-actions flex items-end gap-2">
                            <button type="submit" class="admin-btn admin-btn--primary">Apply</button>
                            <a href="{{ route('admin.contact-queries.index') }}" class="admin-btn admin-btn--secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="queries-table-shell overflow-hidden rounded-3xl border shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                    <div>
                        <h3 class="text-lg font-semibold">Support inbox</h3>
                        <p class="queries-muted mt-1 text-sm">Review customer messages, update status, and keep support follow-up organised.</p>
                    </div>
                    <span class="queries-count-pill rounded-full px-3 py-1 text-xs font-semibold">
                        Showing {{ $contactQueries->firstItem() ?? 0 }}-{{ $contactQueries->lastItem() ?? 0 }} of {{ $contactQueries->total() }}
                    </span>
                </div>

                @if ($contactQueries->isEmpty())
                    <div class="px-5 py-10 text-center text-sm text-gray-500">
                        No contact queries matched the current filters.
                    </div>
                @else
                    <form id="bulk-queries-form" method="POST" action="{{ route('admin.contact-queries.bulk') }}">
                        @csrf
                    </form>
                    <div class="queries-bulk-bar border-b px-5 py-4">
                        <div class="queries-bulk-bar flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div class="flex items-center gap-3">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <input type="checkbox" data-check-all="queries" class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                        Select all
                                    </label>
                                    <span class="text-xs text-gray-500">Choose queries, then resolve, unresolve, or delete them in one go.</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <select name="action" form="bulk-queries-form" class="queries-bulk-select rounded-xl border border-gray-300 px-3 py-2 text-sm">
                                        <option value="">Bulk action</option>
                                        <option value="resolve">Mark resolved</option>
                                        <option value="unresolve">Mark unresolved</option>
                                        <option value="delete">Delete selected</option>
                                    </select>
                                    <button type="submit" form="bulk-queries-form" class="admin-btn admin-btn--secondary" onclick="return confirm('Apply this bulk action to the selected contact queries?');">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="queries-table-head text-left">
                                <tr class="text-xs uppercase tracking-[0.18em] text-gray-500">
                                    <th class="px-5 py-4 font-semibold"><span class="sr-only">Select</span></th>
                                    <th class="px-5 py-4 font-semibold">From</th>
                                    <th class="px-5 py-4 font-semibold">Subject</th>
                                    <th class="px-5 py-4 font-semibold">Status</th>
                                    <th class="px-5 py-4 font-semibold">Message</th>
                                    <th class="px-5 py-4 font-semibold">Received</th>
                                    <th class="px-5 py-4 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200/80">
                                @foreach ($contactQueries as $contactQuery)
                                    <tr class="queries-row">
                                        <td class="px-5 py-4 align-top">
                                            <input type="checkbox" name="selected[]" value="{{ $contactQuery->id }}" form="bulk-queries-form" data-check-item="queries" class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                    <p class="font-semibold">{{ $contactQuery->name }}</p>
                                    <p class="queries-muted mt-1 text-xs">{{ $contactQuery->email }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <p class="font-semibold">{{ $contactQuery->subject }}</p>
                                </td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="flex flex-col gap-2">
                                                <form action="{{ route('admin.contact-queries.toggle', $contactQuery) }}" method="POST" class="queries-toggle-wrap">
                                                    @csrf
                                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $contactQuery->resolved_at ? 'queries-status-badge--resolved' : 'queries-status-badge--unresolved' }}">
                                                        {{ $contactQuery->resolved_at ? 'Resolved' : 'Unresolved' }}
                                                    </span>
                                                    <button type="submit"
                                                        class="queries-toggle-btn"
                                                        aria-label="Toggle resolved status">
                                                        <span class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors {{ $contactQuery->resolved_at ? 'bg-emerald-500' : 'bg-gray-300' }}">
                                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white transition {{ $contactQuery->resolved_at ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                                        </span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="queries-muted px-5 py-4 align-top">{{ \Illuminate\Support\Str::limit($contactQuery->message, 120) }}</td>
                                        <td class="queries-muted px-5 py-4 align-top">{{ $contactQuery->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.contact-queries.show', $contactQuery) }}" class="admin-btn admin-btn--quiet">View</a>
                                                <button type="submit" form="delete-query-{{ $contactQuery->id }}" class="admin-btn admin-btn--danger" onclick="return confirm('Delete this contact query?');">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @foreach ($contactQueries as $contactQuery)
                        <form id="delete-query-{{ $contactQuery->id }}" action="{{ route('admin.contact-queries.destroy', $contactQuery) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                @endif

                @if ($contactQueries instanceof \Illuminate\Contracts\Pagination\Paginator && $contactQueries->hasPages())
                    <div class="border-t border-gray-200 px-5 py-4">
                        {{ $contactQueries->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const master = document.querySelector('[data-check-all="queries"]');
            const items = document.querySelectorAll('[data-check-item="queries"]');
            if (!master || !items.length) return;

            master.addEventListener('change', function () {
                items.forEach((item) => item.checked = master.checked);
            });
        });
    </script>
</x-app-layout>
