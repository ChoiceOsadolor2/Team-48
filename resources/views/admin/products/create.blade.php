<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.products.store') }}" class="space-y-4">
                        @csrf

                        @include('admin.products.form', ['product' => null])

                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded">
                            Save
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
