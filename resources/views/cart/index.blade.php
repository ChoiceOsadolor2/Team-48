<x-app-layout>

    <h1 class="text-2xl font-bold mb-6">Your Cart</h1>

    @if (session('stock_error'))
        <div class="cart-error">
            {{ session('stock_error') }}
        </div>
    @endif

    {{-- Flash message --}}
    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('status') }}
        </div>
    @endif

    @if ($items->count() === 0)
        <p>Your cart is empty.</p>

        <a 
            href="{{ url('/') }}" 
            class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white rounded"
        >
            Continue Shopping
        </a>

    @else

        <table class="min-w-full bg-white shadow rounded">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 border-b text-left">Product</th>
                    <th class="px-6 py-3 border-b text-left">Price</th>
                    <th class="px-6 py-3 border-b text-left">Quantity</th>
                    <th class="px-6 py-3 border-b text-left">Subtotal</th>
                    <th class="px-6 py-3 border-b"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="px-6 py-4 border-b">{{ $item['product']->name }}</td>
                        <td class="px-6 py-4 border-b">£{{ number_format($item['product']->price, 2) }}</td>

                        <!-- Update Quantity -->
                        <td class="px-6 py-4 border-b">
                            <form 
                                action="{{ route('cart.update', $item['product']->id) }}" 
                                method="POST"
                                class="flex items-center"
                            >
                                @csrf
                                @method('PUT')

                                <input 
                                    type="number" 
                                    name="quantity"
                                    value="{{ $item['quantity'] }}"
                                    min="1"
                                    class="w-20 border rounded p-1"
                                >

                                <button 
                                    class="ml-3 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                                >
                                    Update
                                </button>
                            </form>
                        </td>

                        <td class="px-6 py-4 border-b">
                            £{{ number_format($item['subtotal'], 2) }}
                        </td>

                        <!-- Remove Item -->
                        <td class="px-6 py-4 border-b">
                            <form 
                                action="{{ route('cart.remove', $item['product']->id) }}" 
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')
                                <button 
                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                                >
                                    Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total + Clear Cart -->
        <div class="mt-6">
            <h2 class="text-xl font-bold">Total: £{{ number_format($total, 2) }}</h2>

            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                <button 
                    class="mt-3 px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900"
                >
                    Clear Cart
                </button>
            </form>
        </div>

        <!-- Checkout + Orders -->
        <div class="mt-6 flex gap-4">
            <a 
                href="{{ route('checkout.index') }}"
                class="px-6 py-3 bg-green-600 text-white rounded hover:bg-green-700"
            >
                Proceed to Checkout
            </a>

            <a 
                href="{{ route('orders.index') }}"
                class="px-6 py-3 bg-indigo-600 text-white rounded hover:bg-indigo-700"
            >
                View Orders
            </a>
        </div>

    @endif

</x-app-layout>
