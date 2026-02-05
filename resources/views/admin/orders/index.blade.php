<x-app-layout>
    <div class="py-8 max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">All Orders</h1>
        </div>

        {{-- Flash message --}}
        @if (session('status'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white shadow rounded mb-6 p-4">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="bg-white shadow rounded p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-semibold mb-1">Search</label>
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                class="w-full border rounded px-3 py-2"
                placeholder="Search name/email/order id/product..."
            />
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                <option value="" {{ request('status')==='' ? 'selected' : '' }}>All</option>
                <option value="processing" {{ request('status')==='processing' ? 'selected' : '' }}>Processing</option>
                <option value="cancelled" {{ request('status')==='cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="completed" {{ request('status')==='completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">From</label>
            <input
                type="date"
                name="from"
                value="{{ request('from') }}"
                class="w-full border rounded px-3 py-2"
            />
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">To</label>
            <input
                type="date"
                name="to"
                value="{{ request('to') }}"
                class="w-full border rounded px-3 py-2"
            />
        </div>
    </div>

    <div class="mt-4 flex gap-2">
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
            Apply
        </button>

        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-200 rounded">
            Clear
        </a>
    </div>
</form>

        </div>

        <div class="bg-white shadow rounded">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Order #</th>
                            <th class="p-3 text-left">User</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Items</th>
                            <th class="p-3 text-left">Total</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Placed</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr class="border-t">
                                <td class="p-3 font-semibold">#{{ $order->id }}</td>
                                <td class="p-3">{{ $order->user?->name ?? 'Unknown' }}</td>
                                <td class="p-3">{{ $order->user?->email ?? '-' }}</td>

                                <td class="p-3">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($order->items as $it)
                                            <li>
                                                {{ $it->product?->name ?? 'Deleted product' }}
                                                (x{{ $it->quantity }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>

                                <td class="p-3">£{{ number_format($order->total, 2) }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($order->status === 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="p-3">{{ $order->created_at->format('d M Y H:i') }}</td>

                                <td class="p-3">
                                    <a class="text-indigo-600 hover:underline"
                                       href="{{ route('admin.orders.show', $order) }}">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="p-6 text-gray-500" colspan="8">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
