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
                    @if ($contactQueries->isEmpty())
                        <p class="text-gray-600">No contact queries yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">From</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Subject</th>
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
                                            <td class="px-4 py-3 align-top text-gray-600">{{ \Illuminate\Support\Str::limit($contactQuery->message, 180) }}</td>
                                            <td class="px-4 py-3 align-top text-gray-500">{{ $contactQuery->created_at->format('d M Y, H:i') }}</td>
                                            <td class="px-4 py-3 align-top">
                                                <form action="{{ route('admin.contact-queries.destroy', $contactQuery) }}" method="POST" onsubmit="return confirm('Delete this contact query?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-semibold text-red-600 hover:text-red-500">Delete</button>
                                                </form>
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
