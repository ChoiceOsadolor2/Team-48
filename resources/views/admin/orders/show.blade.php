<x-app-layout>
    <div class="py-8 max-w-5xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold">Order #{{ $order->id }}</h1>
                <p class="text-gray-600">
                    {{ $order->created_at->format('d M Y H:i') }} •
                    Status: <span class="font-semibold">{{ ucfirst($order->status) }}</span>
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.orders.index') }}"
                   class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    Back
                </a>

                @if($order->status !== 'cancelled')
                    <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                          onsubmit="return confirm('Cancel this order and restock items?')">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">
                            Cancel + Restock
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white shadow rounded p-4 mb-6">
            <h2 class="font-semibold mb-2">Customer</h2>
            <p><strong>Name:</strong> {{ $order->user?->name ?? 'Unknown' }}</p>
            <p><strong>Email:</strong> {{ $order->user?->email ?? '-' }}</p>
        </div>

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Product</th>
                        <th class="p-3 text-left">Qty</th>
                        <th class="p-3 text-left">Price</th>
                        <th class="p-3 text-left">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $it)
                        <tr class="border-t">
                            <td class="p-3">
                                {{ $it->product?->name ?? 'Deleted product' }}
                            </td>
                            <td class="p-3">x{{ $it->quantity }}</td>
                            <td class="p-3">£{{ number_format($it->price, 2) }}</td>
                            <td class="p-3">
                                £{{ number_format($it->price * $it->quantity, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t bg-gray-50">
                        <td class="p-3 font-semibold" colspan="3">Total</td>
                        <td class="p-3 font-semibold">£{{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if(session('status'))
            <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('status') }}
            </div>
        @endif
    </div>
</x-app-layout>
