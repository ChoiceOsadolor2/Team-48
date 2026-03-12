<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit FAQ</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-white p-8 shadow-sm">
                <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
                    @csrf
                    @method('PUT')
                    @include('admin.faqs.form', ['faq' => $faq])

                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit" class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-500">
                            Update FAQ
                        </button>
                        <a href="{{ route('admin.faqs.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
