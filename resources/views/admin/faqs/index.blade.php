<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chatbot FAQs</h2>
                <p class="text-sm text-gray-500">Manage the FAQ answers used by the chatbot.</p>
            </div>
            <a href="{{ route('admin.faqs.create') }}" class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-500">
                + Add FAQ
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.faqs.index') }}" class="mb-6 rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <div class="flex flex-col gap-4 md:flex-row md:items-end">
                            <div class="flex-1">
                                <label class="block text-sm font-semibold mb-1 text-gray-700">Search FAQs</label>
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ $search ?? request('q') }}"
                                    class="w-full rounded border border-gray-300 px-3 py-2"
                                    placeholder="Search keyword or answer..."
                                />
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Apply</button>
                                <a href="{{ route('admin.faqs.index') }}" class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-800">Clear</a>
                            </div>
                        </div>
                    </form>

                    @if ($faqs->isEmpty())
                        <p class="text-gray-600">No FAQs yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Keyword</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Answer</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($faqs as $faq)
                                        <tr>
                                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $faq->keyword }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($faq->answer, 120) }}</td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="font-semibold text-cyan-600 hover:text-cyan-500">Edit</a>
                                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Delete this FAQ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="font-semibold text-red-600 hover:text-red-500">Delete</button>
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
    </div>
</x-app-layout>
