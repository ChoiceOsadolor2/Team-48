<x-app-layout>
    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.18em] text-gray-400">Audit Trail</p>
                        <h1 class="mt-2 text-2xl font-bold text-gray-900">Admin audit log</h1>
                        <p class="mt-1 text-sm text-gray-500">Track important admin actions across products, orders, FAQs, discounts, and support workflows.</p>
                    </div>

                    <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex w-full max-w-xl gap-3">
                        <input
                            type="text"
                            name="q"
                            value="{{ $search }}"
                            placeholder="Search actions, summaries, or targets"
                            class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-cyan-400 focus:bg-white"
                        >
                        <button type="submit" class="rounded-2xl bg-cyan-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-500">
                            Search
                        </button>
                    </form>
                </div>
            </section>

            <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        @if ($auditLogs->total() <= $auditLogs->perPage())
                            Showing {{ $auditLogs->count() }} of {{ $auditLogs->total() }} actions
                        @else
                            Showing {{ $auditLogs->firstItem() }}-{{ $auditLogs->lastItem() }} of {{ $auditLogs->total() }} actions
                        @endif
                    </p>
                </div>

                @if ($auditLogs->isEmpty())
                    <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center text-sm text-gray-500">
                        No audit log entries match the current search.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="text-left text-xs uppercase tracking-[0.16em] text-gray-500">
                                    <th class="px-4 py-3">When</th>
                                    <th class="px-4 py-3">Admin</th>
                                    <th class="px-4 py-3">Action</th>
                                    <th class="px-4 py-3">Target</th>
                                    <th class="px-4 py-3">Summary</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($auditLogs as $log)
                                    <tr class="bg-white transition hover:bg-gray-50">
                                        <td class="px-4 py-4 text-sm text-gray-600">{{ $log->created_at->format('d M Y H:i') }}</td>
                                        <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ $log->adminUser?->name ?? 'System / Unknown' }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ $log->action }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            {{ $log->target_type ?? 'General' }}
                                            @if ($log->target_id)
                                                #{{ $log->target_id }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ $log->summary }}</td>
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
