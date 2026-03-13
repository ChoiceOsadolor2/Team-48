<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Contact Queries</h2>
                <p class="mt-1 text-sm text-gray-500">Messages submitted through the Contact Us forms.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.contact-queries.index') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Search</label>
                            <input
                                type="text"
                                name="q"
                                value="{{ $search ?? request('q') }}"
                                class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm"
                                placeholder="Search name, email, subject..."
                            />
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Status</label>
                            <select name="status" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm">
                                <option value="">All queries</option>
                                <option value="resolved" {{ ($status ?? request('status')) === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="unresolved" {{ ($status ?? request('status')) === 'unresolved' ? 'selected' : '' }}>Unresolved</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                            <a href="{{ route('admin.contact-queries.index') }}" class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-300">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Support inbox</h3>
                        <p class="text-sm text-gray-500">Customer messages with quick status control and cleaner scanning.</p>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
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
                    <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div class="flex items-center gap-3">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <input type="checkbox" data-check-all="queries" class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                        Select all
                                    </label>
                                    <span class="text-xs text-gray-500">Choose queries, then resolve, unresolve, or delete them in one go.</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <select name="action" form="bulk-queries-form" class="rounded-xl border border-gray-300 px-3 py-2 text-sm">
                                        <option value="">Bulk action</option>
                                        <option value="resolve">Mark resolved</option>
                                        <option value="unresolve">Mark unresolved</option>
                                        <option value="delete">Delete selected</option>
                                    </select>
                                    <button type="submit" form="bulk-queries-form" class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-800" onclick="return confirm('Apply this bulk action to the selected contact queries?');">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-left">
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
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($contactQueries as $contactQuery)
                                    <tr class="transition hover:bg-gray-50/80">
                                        <td class="px-5 py-4 align-top">
                                            <input type="checkbox" name="selected[]" value="{{ $contactQuery->id }}" form="bulk-queries-form" data-check-item="queries" class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                            <p class="font-semibold text-gray-900">{{ $contactQuery->name }}</p>
                                            <p class="mt-1 text-xs text-gray-500">{{ $contactQuery->email }}</p>
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                            <p class="font-semibold text-gray-900">{{ $contactQuery->subject }}</p>
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="flex flex-col gap-2">
                                                <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $contactQuery->resolved_at ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                                    {{ $contactQuery->resolved_at ? 'Resolved' : 'Unresolved' }}
                                                </span>
                                                <form action="{{ route('admin.contact-queries.toggle', $contactQuery) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $contactQuery->resolved_at ? 'bg-emerald-500' : 'bg-gray-300' }}"
                                                        aria-label="Toggle resolved status">
                                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition {{ $contactQuery->resolved_at ? 'translate-x-5' : 'translate-x-1' }}"></span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 align-top text-gray-600">{{ \Illuminate\Support\Str::limit($contactQuery->message, 120) }}</td>
                                        <td class="px-5 py-4 align-top text-gray-500">{{ $contactQuery->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.contact-queries.show', $contactQuery) }}" class="rounded-lg border border-cyan-200 px-3 py-1.5 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-50">View</a>
                                                <button type="submit" form="delete-query-{{ $contactQuery->id }}" class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50" onclick="return confirm('Delete this contact query?');">Delete</button>
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
