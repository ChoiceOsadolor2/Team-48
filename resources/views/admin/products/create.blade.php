<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[28px] border border-white/10 bg-[#121212]/95 shadow-2xl">
                <div class="border-b border-white/10 px-8 py-6 text-white">
                    <p class="text-xs uppercase tracking-[0.22em] text-cyan-300">Inventory</p>
                    <h1 class="mt-3 text-3xl font-normal">Create a new product</h1>
                    <p class="mt-2 text-sm text-gray-300">Add a new item to the catalogue with pricing, stock, platform, and imagery.</p>
                </div>

                <div class="p-8 text-white">
                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-red-500/30 bg-red-500/10 px-5 py-4 text-sm text-red-200">
                            <p class="font-semibold text-red-100">Please fix the following before saving:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="admin-product-create-form" method="POST"
                        action="{{ route('admin.products.store') }}"
                        enctype="multipart/form-data"
                        class="space-y-8">

                        @csrf

                        @include('admin.products.form', ['product' => null])

                        <div class="flex items-center justify-between gap-4 border-t border-white/10 pt-6">
                            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-400 transition hover:text-white">
                                Back to products
                            </a>
                            <button type="submit" form="admin-product-create-form"
                                class="inline-flex min-h-[46px] items-center justify-center rounded-xl border border-emerald-500/40 bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-500">
                                Save product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
