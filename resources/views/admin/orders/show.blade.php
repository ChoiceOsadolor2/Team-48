<x-app-layout>
    @php
        $statusClasses = match($order->status) {
            'cancelled' => 'bg-rose-100 text-rose-800',
            'completed', 'delivered' => 'bg-emerald-100 text-emerald-800',
            default => 'bg-amber-100 text-amber-800',
        };
    @endphp

    <div class="py-10">
        <div class="max-w-6xl mx-auto px-4 space-y-6">
            <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.18em] text-gray-500">Order overview</p>
                        <h1 class="mt-2 text-3xl font-bold text-black">Order #{{ $order->id }}</h1>
                        <p class="mt-2 text-sm text-black">
                            Placed on {{ $order->created_at->format('d M Y \\a\\t H:i') }}
                        </p>
                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-black">
                                {{ $order->items->count() }} items
                            </span>
                            <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">
                                Total £{{ number_format($order->total, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.orders.index') }}"
                           class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-black transition hover:bg-gray-300">
                            Back to orders
                        </a>

                        @if($order->status !== 'cancelled')
                            <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                                  onsubmit="return confirm('Cancel this order and restock items?')">
                                @csrf
                                <button type="submit"
                                        class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-500">
                                    Cancel + Restock
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm uppercase tracking-[0.18em] text-gray-500">Customer</p>
                    <div class="mt-4 space-y-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-500">Name</p>
                            <p class="mt-1 text-base font-semibold text-black">{{ $order->user?->name ?? 'Unknown customer' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-500">Email</p>
                            <p class="mt-1 text-base text-black">{{ $order->user?->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm uppercase tracking-[0.18em] text-gray-500">Order summary</p>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                            <span class="text-sm font-semibold text-black">Order ID</span>
                            <span class="text-sm text-black">#{{ $order->id }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                            <span class="text-sm font-semibold text-black">Items</span>
                            <span class="text-sm text-black">{{ $order->items->sum('quantity') }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                            <span class="text-sm font-semibold text-black">Total</span>
                            <span class="text-sm font-semibold text-black">£{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm uppercase tracking-[0.18em] text-gray-500">Management note</p>
                    <div class="mt-4 rounded-2xl bg-gray-50 px-4 py-4">
                        <p class="text-sm leading-relaxed text-black">
                            Use this page to review the full order contents before updating the status from the main orders table. Cancelling here will still restore product stock automatically.
                        </p>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-semibold text-black">Items in this order</h2>
                        <p class="text-sm text-gray-500">A full line-by-line breakdown of what was purchased.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-left">
                            <tr class="text-xs uppercase tracking-[0.18em] text-gray-500">
                                <th class="px-5 py-4 font-semibold">Product</th>
                                <th class="px-5 py-4 font-semibold">Quantity</th>
                                <th class="px-5 py-4 font-semibold">Unit price</th>
                                <th class="px-5 py-4 font-semibold text-right">Line total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($order->items as $it)
                                <tr class="transition hover:bg-gray-50/80">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-black">{{ $it->product?->name ?? 'Deleted product' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-black">x{{ $it->quantity }}</td>
                                    <td class="px-5 py-4 text-black">£{{ number_format($it->price, 2) }}</td>
                                    <td class="px-5 py-4 text-right font-semibold text-black">
                                        £{{ number_format($it->price * $it->quantity, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t bg-gray-50">
                                <td class="px-5 py-4 font-semibold text-black" colspan="3">Grand total</td>
                                <td class="px-5 py-4 text-right font-semibold text-black">£{{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
