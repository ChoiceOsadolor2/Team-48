<x-app-layout>

    <div class="max-w-4xl mx-auto py-8">

        <h1 class="text-3xl font-bold mb-6">Checkout</h1>

        {{-- Flash message --}}
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">Order Summary</h2>

            <table class="min-w-full bg-white rounded">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 border-b text-left">Product</th>
                        <th class="px-6 py-3 border-b text-left">Price</th>
                        <th class="px-6 py-3 border-b text-left">Qty</th>
                        <th class="px-6 py-3 border-b text-left">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td class="px-6 py-4 border-b">{{ $item['product']->name }}</td>
                            <td class="px-6 py-4 border-b">£{{ number_format($item['product']->price, 2) }}</td>
                            <td class="px-6 py-4 border-b">{{ $item['quantity'] }}</td>
                            <td class="px-6 py-4 border-b">£{{ number_format($item['subtotal'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 text-right">
                <h2 class="text-xl font-bold">
                    Total: £{{ number_format($total, 2) }}
                </h2>
            </div>
        </div>

        {{-- Place Order --}}
        <form 
            action="{{ route('checkout.place') }}" 
            method="POST" 
            class="mt-6"
        >
            @csrf
            <button
                type="submit"
                class="px-6 py-3 bg-green-600 text-white rounded shadow hover:bg-green-700"
            >
                Place Order
            </button>
        </form>

    </div>

</x-app-layout>
