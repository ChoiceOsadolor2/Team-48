<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Create discount code</h1>
                    <p class="mt-2 text-sm text-gray-500">Add a new promo code for future checkout campaigns.</p>
                </div>

                <form method="POST" action="{{ route('admin.discount-codes.store') }}" class="space-y-6">
                    @csrf
                    @include('admin.discount-codes.form', ['discountCode' => null])

                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.discount-codes.index') }}" class="text-sm font-semibold text-gray-500 hover:text-gray-700">
                            Cancel
                        </a>
                        <button type="submit" class="rounded-xl bg-cyan-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-500">
                            Save discount code
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
