<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chatbot FAQs</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between gap-4 rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Chatbot FAQs</h3>
                    <p class="mt-1 text-sm text-gray-500">Manage the FAQ answers used by the chatbot.</p>
                </div>
                <a href="{{ route('admin.faqs.create') }}" class="rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-cyan-500">
                    + Add FAQ
                </a>
            </div>

            <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                <form method="GET" action="{{ route('admin.faqs.index') }}" class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-[1fr,220px,auto] md:items-end">
                        <div class="flex-1">
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Search FAQs</label>
                            <input
                                type="text"
                                name="q"
                                value="{{ $search ?? request('q') }}"
                                class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm"
                                placeholder="Search keyword or answer..."
                            />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Category</label>
                            <select name="category" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm">
                                <option value="">All categories</option>
                                @foreach(($categories ?? \App\Models\Faq::CATEGORIES) as $value => $label)
                                    <option value="{{ $value }}" {{ ($category ?? request('category')) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500">Apply</button>
                            <a href="{{ route('admin.faqs.index') }}" class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-300">Clear</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">FAQ library</h3>
                        <p class="text-sm text-gray-500">Searchable answers powering the site chatbot.</p>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                        {{ $faqs->count() }} shown
                    </span>
                </div>

                @if ($faqs->isEmpty())
                    <div class="px-5 py-10 text-center text-sm text-gray-500">
                        No FAQs matched the current search.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 text-left">
                                <tr class="text-xs uppercase tracking-[0.18em] text-gray-500">
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
                                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="rounded-lg border border-cyan-200 px-3 py-1.5 text-xs font-semibold text-cyan-700 transition hover:bg-cyan-50">Edit</a>
                                                <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Delete this FAQ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50">Delete</button>
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
