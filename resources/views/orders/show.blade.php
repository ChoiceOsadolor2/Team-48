<x-app-layout>

    <div class="max-w-4xl mx-auto py-8">

        {{-- Page title --}}
        <h1 class="text-3xl font-bold mb-6">
            Order #{{ $order->id }}
        </h1>

        {{-- Order Details Card --}}
        <div class="bg-white p-6 shadow rounded">

            {{-- Flash success message --}}
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <p class="mb-2"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p class="mb-4"><strong>Total:</strong> £{{ number_format($order->total, 2) }}</p>

            <h2 class="text-xl font-semibold mt-6 mb-4">Items</h2>

            <table class="min-w-full bg-white rounded">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 border-b text-left">Product</th>
                        <th class="px-6 py-3 border-b text-left">Quantity</th>
                        <th class="px-6 py-3 border-b text-left">Price</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 border-b">
                                {{ optional($item->product)->name ?? 'Product Deleted' }}
                            </td>
                            <td class="px-6 py-4 border-b">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 border-b">
                                £{{ number_format($item->price, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Back to orders --}}
            <div class="mt-6">
                <a 
                    href="{{ route('orders.index') }}"
                    class="px-5 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                >
                    Back to Orders
                </a>
            </div>

        </div>

    </div>

</x-app-layout>
