<x-app-layout>

    <div class="max-w-4xl mx-auto py-8">

        <h1 class="text-3xl font-bold mb-6">Your Orders</h1>

        @if ($orders->count() === 0)
            <p>You have not placed any orders yet.</p>
        @else
            <div class="bg-white p-6 rounded shadow">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-3 border-b text-left">Order #</th>
                            <th class="px-6 py-3 border-b text-left">Total</th>
                            <th class="px-6 py-3 border-b text-left">Status</th>
                            <th class="px-6 py-3 border-b text-left">Date</th>
                            <th class="px-6 py-3 border-b"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="px-6 py-4 border-b">{{ $order->id }}</td>
                                <td class="px-6 py-4 border-b">£{{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4 border-b">{{ ucfirst($order->status) }}</td>
                                <td class="px-6 py-4 border-b">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 border-b">
                                    <a 
                                        href="{{ route('orders.show', $order->id) }}"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                                    >
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        @endif

    </div>

</x-app-layout>
