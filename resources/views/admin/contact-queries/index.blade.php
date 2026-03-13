<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Contact Queries</h2>
                <p class="text-sm text-gray-500">Messages submitted through the Contact Us forms.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.contact-queries.index') }}" class="mb-6 rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-gray-700">Search</label>
                                <input
                                    type="text"
                                    name="q"
                                    value="{{ $search ?? request('q') }}"
                                    class="w-full rounded border border-gray-300 px-3 py-2"
                                    placeholder="Search name, email, subject..."
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-1 text-gray-700">Status</label>
                                <select name="status" class="w-full rounded border border-gray-300 px-3 py-2">
                                    <option value="">All queries</option>
                                    <option value="resolved" {{ ($status ?? request('status')) === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="unresolved" {{ ($status ?? request('status')) === 'unresolved' ? 'selected' : '' }}>Unresolved</option>
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Apply</button>
                                <a href="{{ route('admin.contact-queries.index') }}" class="px-4 py-2 bg-gray-200 rounded">Clear</a>
                            </div>
                        </div>
                    </form>

                    @if ($contactQueries->isEmpty())
                        <p class="text-gray-600">No contact queries yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">From</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Subject</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Message</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Received</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($contactQueries as $contactQuery)
                                        <tr>
                                            <td class="px-4 py-3 align-top">
                                                <p class="font-semibold text-gray-900">{{ $contactQuery->name }}</p>
                                                <p class="text-gray-500">{{ $contactQuery->email }}</p>
                                            </td>
                                            <td class="px-4 py-3 align-top font-semibold text-gray-900">{{ $contactQuery->subject }}</td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="flex items-center gap-3">
                                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $contactQuery->resolved_at ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
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
                                            <td class="px-4 py-3 align-top text-gray-600">{{ \Illuminate\Support\Str::limit($contactQuery->message, 180) }}</td>
                                            <td class="px-4 py-3 align-top text-gray-500">{{ $contactQuery->created_at->format('d M Y, H:i') }}</td>
                                            <td class="px-4 py-3 align-top">
                                                <div class="flex items-center gap-3">
                                                    <a href="{{ route('admin.contact-queries.show', $contactQuery) }}" class="font-semibold text-cyan-600 hover:text-cyan-500">View</a>
                                                    <form action="{{ route('admin.contact-queries.destroy', $contactQuery) }}" method="POST" onsubmit="return confirm('Delete this contact query?');">
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
