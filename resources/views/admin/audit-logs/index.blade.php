<x-app-layout>
    <style>
        .admin-audit-page .page-intro-title {
            font-size: 30px !important;
            line-height: 1.1 !important;
            color: #111827;
        }

        .admin-audit-page .page-intro-copy {
            margin-top: 8px;
            color: #6b7280 !important;
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        html[data-theme="dark"] .admin-audit-page .page-intro-title {
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-audit-page .page-intro-copy {
            color: #9ca3af !important;
        }

        @media (min-width: 768px) {
            .admin-audit-page .page-intro {
                min-height: 58px;
                display: flex;
                align-items: center;
                margin-top: -90px;
                margin-left: 210px;
                margin-bottom: 24px;
            }
        }

        .admin-audit-page .audit-shell,
        .admin-audit-page .audit-table-shell,
        .admin-audit-page .audit-filter-shell {
            background: #ffffff;
            border-color: #e5e7eb;
        }

        .admin-audit-page .audit-soft,
        .admin-audit-page .audit-table-head {
            background: #f9fafb;
        }

        .admin-audit-page .audit-activity-head {
            background: #1d1d1d !important;
        }

        .admin-audit-page .audit-text {
            color: #111827 !important;
        }

        .admin-audit-page .audit-muted {
            color: #6b7280 !important;
        }

        .admin-audit-page .audit-control {
            min-height: 56px;
            border-radius: 1rem;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            color: #111827;
            font-size: 1rem;
        }

        .admin-audit-page .audit-control::placeholder {
            color: #6b7280;
        }

        .admin-audit-page .audit-row:hover {
            background: rgba(15, 23, 42, 0.035);
        }

        html[data-theme="dark"] .admin-audit-page .page-intro-title {
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-audit-page .page-intro-copy {
            color: #9ca3af !important;
        }

        html[data-theme="dark"] .admin-audit-page .audit-shell,
        html[data-theme="dark"] .admin-audit-page .audit-table-shell,
        html[data-theme="dark"] .admin-audit-page .audit-filter-shell {
            background: #1f2937;
            border-color: #374151;
        }

        html[data-theme="dark"] .admin-audit-page .audit-soft,
        html[data-theme="dark"] .admin-audit-page .audit-table-head {
            background: rgba(17, 24, 39, 0.78);
        }

        html[data-theme="dark"] .admin-audit-page .audit-activity-head {
            background: #1d1d1d !important;
        }

        html[data-theme="dark"] .admin-audit-page .audit-text {
            color: #f9fafb !important;
        }

        html[data-theme="dark"] .admin-audit-page .audit-muted {
            color: #9ca3af !important;
        }

        html[data-theme="dark"] .admin-audit-page .audit-control {
            border-color: #4b5563;
            background: rgba(17, 24, 39, 0.78);
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-audit-page .audit-control::placeholder {
            color: #9ca3af;
        }

        html[data-theme="dark"] .admin-audit-page .audit-row:hover {
            background: rgba(255, 255, 255, 0.03);
        }
    </style>
    <div class="admin-audit-page py-10">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="page-intro">
                <div>
                    <h1 class="page-intro-title flex items-center gap-3">
                        <svg class="h-7 w-7 text-cyan-600 dark:text-cyan-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3l7 4v5c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V7l7-4z"></path>
                            <path d="M9 12l2 2 4-4"></path>
                        </svg>
                        <span>Admin audit log</span>
                    </h1>
                    <p class="page-intro-copy">Track important admin actions across products, orders, FAQs, discounts, and support workflows.</p>
                </div>
            </div>

            <section class="audit-shell rounded-3xl border p-5 shadow-sm">
                <div class="audit-filter-shell rounded-2xl border p-4">
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-[minmax(0,1fr),420px] lg:items-end">
                        <div>
                            <p class="audit-muted text-sm uppercase tracking-[0.18em]">Audit Trail</p>
                            <h2 class="audit-text mt-2 text-2xl font-bold">Search audit history</h2>
                            <p class="audit-muted mt-2 max-w-2xl text-sm">Track key admin actions across products, orders, support, and promotional changes.</p>
                        </div>

                        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr),auto] sm:items-end">
                            <div>
                                <label class="audit-text mb-1 block text-sm font-semibold">Search logs</label>
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ $search }}"
                                    placeholder="Search actions, summaries, or targets"
                                    class="audit-control w-full px-4 py-3 outline-none transition focus:border-cyan-400"
                                >
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="admin-btn admin-btn--primary">Search</button>
                                <a href="{{ route('admin.audit-logs.index') }}" class="admin-btn admin-btn--secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <section class="audit-table-shell rounded-3xl border shadow-sm overflow-hidden">
                <div class="audit-soft audit-activity-head flex items-center justify-between border-b border-[#3a3a3d] px-5 py-4">
                    <div>
                        <h3 class="audit-text text-lg font-semibold">Recent audit activity</h3>
                        <p class="audit-muted mt-1 text-sm">Review who changed what and when across the store.</p>
                    </div>
                    <span class="audit-soft audit-text rounded-full px-3 py-1 text-xs font-semibold">
                        @if ($auditLogs->total() <= $auditLogs->perPage())
                            Showing {{ $auditLogs->count() }} of {{ $auditLogs->total() }} actions
                        @else
                            Showing {{ $auditLogs->firstItem() }}-{{ $auditLogs->lastItem() }} of {{ $auditLogs->total() }} actions
                        @endif
                    </span>
                </div>

                @if ($auditLogs->isEmpty())
                    <div class="audit-muted px-6 py-10 text-center text-sm space-y-3">
                        <p>No audit log entries match the current search.</p>
                        <p>Try searching for a broader action, admin, or target.</p>
                        <div class="flex justify-center">
                            <a href="{{ route('admin.audit-logs.index') }}" class="admin-btn admin-btn--secondary">View full audit trail</a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="audit-table-head text-left">
                                <tr class="audit-muted text-xs uppercase tracking-[0.18em]">
                                    <th class="px-5 py-4 font-semibold">When</th>
                                    <th class="px-5 py-4 font-semibold">Admin</th>
                                    <th class="px-5 py-4 font-semibold">Action</th>
                                    <th class="px-5 py-4 font-semibold">Target</th>
                                    <th class="px-5 py-4 font-semibold">Summary</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#3a3a3d]">
                                @foreach ($auditLogs as $log)
                                    <tr class="audit-row transition">
                                        <td class="audit-muted px-5 py-4">{{ $log->created_at->format('d M Y H:i') }}</td>
                                        <td class="audit-text px-5 py-4 font-semibold">{{ $log->adminUser?->name ?? 'System / Unknown' }}</td>
                                        <td class="audit-text px-5 py-4">{{ $log->action }}</td>
                                        <td class="audit-text px-5 py-4">
                                            {{ $log->target_type ?? 'General' }}
                                            @if ($log->target_id)
                                                #{{ $log->target_id }}
                                            @endif
                                        </td>
                                        <td class="audit-text px-5 py-4">{{ $log->summary }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($auditLogs->hasPages())
                        <div class="mt-6">
                            {{ $auditLogs->links() }}
                        </div>
                    @endif
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
