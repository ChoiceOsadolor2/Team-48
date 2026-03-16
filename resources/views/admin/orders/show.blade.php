<x-app-layout>
    @php
        $statusClasses = match($order->status) {
            'cancelled' => 'bg-rose-100 text-rose-800',
            'completed', 'delivered' => 'bg-emerald-100 text-emerald-800',
            default => 'bg-amber-100 text-amber-800',
        };
        $itemsSubtotal = $order->items->sum(fn ($item) => $item->price * $item->quantity);
        $shippingMethod = $order->shipping_method ?: 'Not recorded';
        $shippingCost = (float) ($order->shipping_cost ?? 0);
        $customerAddress = trim((string) ($order->user?->address ?? ''));
    @endphp

    <div class="py-10">
        <div class="max-w-[1180px] mx-auto px-4 space-y-6">
            <section class="rounded-3xl border border-gray-200 bg-white p-7 shadow-sm">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="text-[0.95rem] uppercase tracking-[0.18em] text-gray-500">Order overview</p>
                        <h1 class="mt-2 text-[2rem] font-bold text-black">Order #{{ $order->id }}</h1>
                        <p class="mt-2 text-[0.98rem] text-black">
                            Placed on {{ $order->created_at->format('d M Y \\a\\t H:i') }}
                        </p>
                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <span class="rounded-full px-3 py-1.5 text-[0.82rem] font-semibold {{ $statusClasses }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <span class="rounded-full bg-gray-100 px-3 py-1.5 text-[0.82rem] font-semibold text-black">
                                {{ $order->items->count() }} items
                            </span>
                            <span class="rounded-full bg-cyan-50 px-3 py-1.5 text-[0.82rem] font-semibold text-cyan-700">
                                Total £{{ number_format($order->total, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.orders.index') }}"
                           class="rounded-xl bg-gray-200 px-4 py-3 text-[0.95rem] font-semibold text-black transition hover:bg-gray-300">
                            Back to orders
                        </a>

                        @if($order->status !== 'cancelled')
                            <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                                  onsubmit="return confirm('Cancel this order and restock items?')">
                                @csrf
                                <button type="submit"
                                        class="rounded-xl bg-red-600 px-4 py-3 text-[0.95rem] font-semibold text-white transition hover:bg-red-500">
                                    Cancel + Restock
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-[0.95rem] uppercase tracking-[0.18em] text-gray-500">Customer</p>
                    <div class="mt-4 space-y-3">
                        <div>
                            <p class="text-[0.82rem] font-semibold uppercase tracking-[0.16em] text-gray-500">Name</p>
                            <p class="mt-1 text-[1.02rem] font-semibold text-black">{{ $order->user?->name ?? 'Unknown customer' }}</p>
                        </div>
                        <div>
                            <p class="text-[0.82rem] font-semibold uppercase tracking-[0.16em] text-gray-500">Email</p>
                            <p class="mt-1 text-[1.02rem] text-black">{{ $order->user?->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-500">Address</p>
                            <p class="mt-1 text-base leading-relaxed text-black">
                                {{ $customerAddress !== '' ? $customerAddress : 'No saved address on profile' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-[0.95rem] uppercase tracking-[0.18em] text-gray-500">Order summary</p>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                            <span class="text-[0.95rem] font-semibold text-black">Order ID</span>
                            <span class="text-[0.95rem] text-black">#{{ $order->id }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                            <span class="text-[0.95rem] font-semibold text-black">Items</span>
                            <span class="text-[0.95rem] text-black">{{ $order->items->sum('quantity') }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                            <span class="text-sm font-semibold text-black">Items subtotal</span>
                            <span class="text-sm text-black">£{{ number_format($itemsSubtotal, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                            <span class="text-sm font-semibold text-black">Shipping method</span>
                            <span class="text-sm text-black">{{ $shippingMethod }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                            <span class="text-sm font-semibold text-black">Shipping cost</span>
                            <span class="text-sm text-black">£{{ number_format($shippingCost, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3">
                            <span class="text-sm font-semibold text-black">Total</span>
                            <span class="text-sm font-semibold text-black">£{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-[0.95rem] uppercase tracking-[0.18em] text-gray-500">Management note</p>
                    <div class="mt-4 rounded-2xl bg-gray-50 px-4 py-4">
                        <p class="text-[0.98rem] leading-relaxed text-black">
                            Use this page to review the full order contents before updating the status from the main orders table. Cancelling here will still restore product stock automatically.
                        </p>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5">
                    <div>
                        <h2 class="text-[1.2rem] font-semibold text-black">Items in this order</h2>
                        <p class="text-[0.95rem] text-gray-500">A full line-by-line breakdown of what was purchased.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-[0.95rem]">
                        <thead class="bg-gray-50 text-left">
                            <tr class="text-[0.82rem] uppercase tracking-[0.18em] text-gray-500">
                                <th class="px-5 py-4 font-semibold">Product</th>
                                <th class="px-5 py-4 font-semibold">Platform</th>
                                <th class="px-5 py-4 font-semibold">Quantity</th>
                                <th class="px-5 py-4 font-semibold">Unit price</th>
                                <th class="px-5 py-4 font-semibold">Shipping</th>
                                <th class="px-5 py-4 font-semibold text-right">Line total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($order->items as $it)
                                <tr class="transition hover:bg-gray-50/80">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-black">{{ $it->product?->name ?? 'Deleted product' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-black">{{ $it->platform ?: ($it->product?->platform ?? 'Universal') }}</td>
                                    <td class="px-5 py-4 text-black">x{{ $it->quantity }}</td>
                                    <td class="px-5 py-4 text-black">£{{ number_format($it->price, 2) }}</td>
                                    <td class="px-5 py-4 text-black">
                                        @if($loop->first)
                                            <div class="font-semibold">{{ $shippingMethod }}</div>
                                            <div class="text-xs text-gray-500">£{{ number_format($shippingCost, 2) }}</div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right font-semibold text-black">
                                        £{{ number_format(($it->price * $it->quantity) + ($loop->first ? $shippingCost : 0), 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t bg-gray-50">
                                <td class="px-5 py-4 font-semibold text-black" colspan="5">Grand total</td>
                                <td class="px-5 py-4 text-right font-semibold text-black">£{{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
