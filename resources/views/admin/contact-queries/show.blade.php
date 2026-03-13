<x-app-layout>
    <div class="py-8 max-w-5xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold">Contact Query #{{ $contactQuery->id }}</h1>
                <p class="text-gray-600">
                    {{ $contactQuery->created_at->format('d M Y H:i') }}
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.contact-queries.index') }}"
                   class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    Back
                </a>

                <form method="POST" action="{{ route('admin.contact-queries.destroy', $contactQuery) }}"
                      onsubmit="return confirm('Delete this contact query?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white shadow rounded p-5 mb-6">
            <h2 class="font-semibold mb-3">Customer Details</h2>
            <p class="mb-2"><strong>Name:</strong> {{ $contactQuery->name }}</p>
            <p class="mb-2"><strong>Email:</strong> {{ $contactQuery->email }}</p>
            <p><strong>Subject:</strong> {{ $contactQuery->subject }}</p>
        </div>

        <div class="bg-white shadow rounded p-5">
            <h2 class="font-semibold mb-3">Message</h2>
            <div class="text-gray-700 leading-7 whitespace-pre-line break-words">
                {{ $contactQuery->message }}
            </div>
        </div>
    </div>
</x-app-layout>
