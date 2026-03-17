<x-app-layout>
    <style>
        .admin-faqs-page,
        .admin-faqs-page * {
            font-family: 'MiniPixel', sans-serif !important;
            font-weight: 400 !important;
        }

        .admin-faqs-page h3 {
            font-size: 30px !important;
            line-height: 1.1 !important;
        }

        .admin-faqs-page p,
        .admin-faqs-page label,
        .admin-faqs-page input,
        .admin-faqs-page select,
        .admin-faqs-page th,
        .admin-faqs-page td,
        .admin-faqs-page button,
        .admin-faqs-page a {
            font-size: 20px !important;
            line-height: 1.4 !important;
        }

        .admin-faqs-page .page-intro {
            margin-bottom: 8px;
        }

        .admin-faqs-page .page-intro-title {
            font-size: 30px !important;
            line-height: 1.1 !important;
            color: #111827;
        }

        .admin-faqs-page .page-intro-copy {
            margin-top: 8px;
            color: #6b7280 !important;
        }

        @media (min-width: 768px) {
            .admin-faqs-page .page-intro {
                min-height: 58px;
                display: flex;
                align-items: center;
                margin-top: -90px;
                margin-left: 210px;
                margin-bottom: 24px;
            }
        }

        .admin-faqs-page input,
        .admin-faqs-page select {
            min-height: 56px;
            border-radius: 18px !important;
            padding: 0 16px !important;
        }

        .admin-faqs-page .rounded-xl {
            border-radius: 18px !important;
        }

        .admin-faqs-page button,
        .admin-faqs-page a.rounded-xl {
            min-height: 56px;
            padding: 0 22px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .admin-faqs-page .rounded-lg {
            border-radius: 18px !important;
        }

        .admin-faqs-page .faqs-filter-grid {
            align-items: end;
        }

        .admin-faqs-page .faqs-filter-actions {
            justify-content: flex-start;
        }

        .admin-faqs-page .faqs-table-head {
            background: #f8fafc;
        }

        .admin-faqs-page .faqs-shell,
        .admin-faqs-page .faqs-filter-shell {
            background: #fff;
            border-color: #e5e7eb;
        }

        .admin-faqs-page .faqs-table-shell {
            background: #fff;
            border-color: #e5e7eb;
        }

        .admin-faqs-page .faqs-filter-form {
            background: #f9fafb;
            border-color: #e5e7eb;
        }

        .admin-faqs-page .faqs-input,
        .admin-faqs-page .faqs-select,
        .admin-faqs-page .faqs-bulk-select {
            background: #fff;
            border-color: #d1d5db;
            color: #111827;
        }

        .admin-faqs-page .faqs-bulk-bar {
            background: #f9fafb;
            border-color: #e5e7eb;
        }

        .admin-faqs-page .faqs-row {
            transition: background 0.2s ease;
        }

        .admin-faqs-page .faqs-row:hover {
            background: rgba(15, 23, 42, 0.035);
        }

        .admin-faqs-page .faqs-row td {
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }

        .admin-faqs-page .faqs-table-shell th {
            color: #6b7280 !important;
        }

        .admin-faqs-page .faqs-table-shell td {
            color: #111827 !important;
        }

        .admin-faqs-page .faqs-table-shell .faqs-muted {
            color: #6b7280 !important;
        }

        .admin-faqs-page .faqs-library-title {
            font-size: 28px !important;
            line-height: 1.1 !important;
            color: #111827 !important;
        }

        html[data-theme="dark"] .admin-faqs-page .faqs-table-head {
            background: rgba(17, 24, 39, 0.78);
        }

        html[data-theme="dark"] .admin-faqs-page .page-intro-title {
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-faqs-page .page-intro-copy {
            color: #9ca3af !important;
        }

        html[data-theme="dark"] .admin-faqs-page .faqs-shell,
        html[data-theme="dark"] .admin-faqs-page .faqs-filter-shell,
        html[data-theme="dark"] .admin-faqs-page .faqs-table-shell {
            background: #1f2937;
            border-color: #374151;
        }

        html[data-theme="dark"] .admin-faqs-page .faqs-filter-form {
            background: rgba(17, 24, 39, 0.78);
            border-color: #374151;
        }

        html[data-theme="dark"] .admin-faqs-page .faqs-input,
        html[data-theme="dark"] .admin-faqs-page .faqs-select,
        html[data-theme="dark"] .admin-faqs-page .faqs-bulk-select {
            background: #1f2937;
            border-color: #374151;
            color: #f9fafb;
        }

        html[data-theme="dark"] .admin-faqs-page .faqs-bulk-bar {
            background: rgba(17, 24, 39, 0.78);
            border-color: #374151;
        }

        html[data-theme="dark"] .admin-faqs-page .faqs-row:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        html[data-theme="dark"] .admin-faqs-page .faqs-table-shell th,
        html[data-theme="dark"] .admin-faqs-page .faqs-table-shell .faqs-muted {
            color: #9ca3af !important;
        }

        html[data-theme="dark"] .admin-faqs-page .faqs-table-shell td {
            color: #f9fafb !important;
        }

        html[data-theme="dark"] .admin-faqs-page .faqs-library-title {
            color: #f9fafb !important;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chatbot FAQs</h2>
    </x-slot>

    <div class="admin-faqs-page py-12">
        <div class="max-w-[1180px] mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <div class="page-intro flex items-start justify-between gap-4">
                <div>
                    <h1 class="page-intro-title">Chatbot FAQs</h1>
                    <p class="page-intro-copy">Keep chatbot answers current and easy to manage from one admin section.</p>
                </div>
                <a href="{{ route('admin.faqs.create') }}" class="rounded-xl bg-cyan-600 px-4 py-3 text-[0.95rem] font-semibold text-white shadow-sm transition hover:bg-cyan-500">
                    + Add FAQ
                </a>
            </div>

            <div class="faqs-filter-shell rounded-3xl border p-6 shadow-sm">
                <form method="GET" action="{{ route('admin.faqs.index') }}" class="faqs-filter-form rounded-2xl border p-5">
                    <div class="faqs-filter-grid grid grid-cols-1 gap-4 md:grid-cols-[1fr,220px,auto] md:items-end">
                        <div class="flex-1">
                            <label class="mb-1.5 block text-[0.95rem] font-semibold text-gray-700">Search FAQs</label>
                            <input
                                type="text"
                                name="q"
                                value="{{ $search ?? request('q') }}"
                                class="faqs-input w-full rounded-xl border border-gray-300 px-4 py-3 text-[0.95rem]"
                                placeholder="Search keyword or answer..."
                            />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[0.95rem] font-semibold text-gray-700">Category</label>
                            <select name="category" class="faqs-select w-full rounded-xl border border-gray-300 px-4 py-3 text-[0.95rem]">
                                <option value="">All categories</option>
                                @foreach(($categories ?? \App\Models\Faq::CATEGORIES) as $value => $label)
                                    <option value="{{ $value }}" {{ ($category ?? request('category')) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="faqs-filter-actions flex gap-2">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-3 text-[0.95rem] font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                            <a href="{{ route('admin.faqs.index') }}" class="rounded-xl bg-gray-200 px-4 py-3 text-[0.95rem] font-semibold text-gray-800 transition hover:bg-gray-300">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="faqs-table-shell overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5">
                    <div>
                        <h3 class="faqs-library-title text-[1.2rem] font-semibold text-gray-900">FAQ library</h3>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3.5 py-1.5 text-[0.82rem] font-semibold text-gray-700">
                        @if ($faqs instanceof \Illuminate\Contracts\Pagination\Paginator)
                            Showing {{ $faqs->firstItem() ?? 0 }}-{{ $faqs->lastItem() ?? 0 }} of {{ $faqs->total() }}
                        @else
                            {{ $faqs->count() }} shown
                        @endif
                    </span>
                </div>

                @if ($faqs->isEmpty())
                    <div class="faqs-muted px-6 py-12 text-center text-[0.98rem] text-gray-500">
                        No FAQs matched the current search.
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.faqs.bulk') }}">
                        @csrf
                        <div class="faqs-bulk-bar border-b px-6 py-5">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div class="flex items-center gap-3">
                                    <label class="flex items-center gap-2 text-[0.95rem] font-semibold text-gray-700">
                                        <input type="checkbox" data-check-all="faqs" class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                        Select all
                                    </label>
                                    <span class="text-[0.82rem] text-gray-500">Choose FAQs, then run a bulk action.</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <select name="action" class="faqs-bulk-select rounded-xl border border-gray-300 px-3.5 py-2.5 text-[0.95rem]">
                                        <option value="">Bulk action</option>
                                        <option value="delete">Delete selected</option>
                                    </select>
                                    <button type="submit" class="rounded-xl bg-gray-900 px-4 py-2.5 text-[0.95rem] font-semibold text-white transition hover:bg-gray-800" onclick="return confirm('Apply this bulk action to the selected FAQs?');">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-[0.95rem]">
                            <thead class="faqs-table-head text-left">
                                <tr class="text-[0.82rem] uppercase tracking-[0.18em] text-gray-500">
                                    <th class="px-5 py-4 font-semibold"><span class="sr-only">Select</span></th>
                                    <th class="px-5 py-4 font-semibold">Keyword</th>
                                    <th class="px-5 py-4 font-semibold">Category</th>
                                    <th class="px-5 py-4 font-semibold">Answer preview</th>
                                    <th class="px-5 py-4 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200/80">
                                @foreach ($faqs as $faq)
                                    <tr class="faqs-row">
                                        <td class="px-5 py-4">
                                            <input type="checkbox" name="selected[]" value="{{ $faq->id }}" data-check-item="faqs" class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">
                                                {{ $faq->keyword }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                                {{ \App\Models\Faq::CATEGORIES[$faq->category] ?? ucfirst(str_replace('_', ' ', $faq->category)) }}
                                            </span>
                                        </td>
                                        <td class="faqs-muted px-5 py-4 text-gray-600">{{ \Illuminate\Support\Str::limit($faq->answer, 140) }}</td>
                                        <td class="px-5 py-4">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="rounded-lg border border-cyan-200 px-3.5 py-2 text-[0.82rem] font-semibold text-cyan-700 transition hover:bg-cyan-50">Edit</a>
                                                <button type="submit" form="delete-faq-{{ $faq->id }}" class="rounded-lg border border-rose-200 px-3.5 py-2 text-[0.82rem] font-semibold text-rose-700 transition hover:bg-rose-50" onclick="return confirm('Delete this FAQ?');">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </form>
                    @foreach ($faqs as $faq)
                        <form id="delete-faq-{{ $faq->id }}" action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                @endif

                @if ($faqs instanceof \Illuminate\Contracts\Pagination\Paginator && $faqs->hasPages())
                    <div class="border-t border-gray-200 px-6 py-5">
                        {{ $faqs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const master = document.querySelector('[data-check-all="faqs"]');
            const items = document.querySelectorAll('[data-check-item="faqs"]');
            if (!master || !items.length) return;

            master.addEventListener('change', function () {
                items.forEach((item) => item.checked = master.checked);
            });
        });
    </script>
</x-app-layout>
