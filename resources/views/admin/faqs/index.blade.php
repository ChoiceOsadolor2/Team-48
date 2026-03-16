<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chatbot FAQs</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[1180px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between gap-4 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <div>
                    <h3 class="text-[1.45rem] font-semibold text-gray-900">Chatbot FAQs</h3>
                    <p class="mt-1.5 text-[0.98rem] text-gray-500">Manage the FAQ answers used by the chatbot.</p>
                </div>
                <a href="{{ route('admin.faqs.create') }}" class="rounded-xl bg-cyan-600 px-4 py-3 text-[0.95rem] font-semibold text-white shadow-sm transition hover:bg-cyan-500">
                    + Add FAQ
                </a>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="GET" action="{{ route('admin.faqs.index') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-[1fr,220px,auto] md:items-end">
                        <div class="flex-1">
                            <label class="mb-1.5 block text-[0.95rem] font-semibold text-gray-700">Search FAQs</label>
                            <input
                                type="text"
                                name="q"
                                value="{{ $search ?? request('q') }}"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-[0.95rem]"
                                placeholder="Search keyword or answer..."
                            />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[0.95rem] font-semibold text-gray-700">Category</label>
                            <select name="category" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-[0.95rem]">
                                <option value="">All categories</option>
                                @foreach(($categories ?? \App\Models\Faq::CATEGORIES) as $value => $label)
                                    <option value="{{ $value }}" {{ ($category ?? request('category')) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-3 text-[0.95rem] font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                            <a href="{{ route('admin.faqs.index') }}" class="rounded-xl bg-gray-200 px-4 py-3 text-[0.95rem] font-semibold text-gray-800 transition hover:bg-gray-300">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5">
                    <div>
                        <h3 class="text-[1.2rem] font-semibold text-gray-900">FAQ library</h3>
                        <p class="text-[0.95rem] text-gray-500">Searchable answers powering the site chatbot.</p>
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
                    <div class="px-6 py-12 text-center text-[0.98rem] text-gray-500">
                        No FAQs matched the current search.
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.faqs.bulk') }}">
                        @csrf
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-5">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div class="flex items-center gap-3">
                                    <label class="flex items-center gap-2 text-[0.95rem] font-semibold text-gray-700">
                                        <input type="checkbox" data-check-all="faqs" class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                        Select all
                                    </label>
                                    <span class="text-[0.82rem] text-gray-500">Choose FAQs, then run a bulk action.</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <select name="action" class="rounded-xl border border-gray-300 px-3.5 py-2.5 text-[0.95rem]">
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
                            <thead class="bg-gray-50 text-left">
                                <tr class="text-[0.82rem] uppercase tracking-[0.18em] text-gray-500">
                                    <th class="px-5 py-4 font-semibold"><span class="sr-only">Select</span></th>
                                    <th class="px-5 py-4 font-semibold">Keyword</th>
                                    <th class="px-5 py-4 font-semibold">Category</th>
                                    <th class="px-5 py-4 font-semibold">Answer preview</th>
                                    <th class="px-5 py-4 font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($faqs as $faq)
                                    <tr class="transition hover:bg-gray-50/80">
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
                                        <td class="px-5 py-4 text-gray-600">{{ \Illuminate\Support\Str::limit($faq->answer, 140) }}</td>
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
