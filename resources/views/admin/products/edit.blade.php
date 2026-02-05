<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST"
                        action="{{ route('admin.products.update', $product) }}"
                        enctype="multipart/form-data"
                        class="space-y-4">

                    @csrf
                    @method('PUT')

                    @include('admin.products.form', ['product' => $product])

                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded">
                    Update
                </button>
            </form>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
