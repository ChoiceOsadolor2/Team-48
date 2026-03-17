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

    <style>
        .admin-order-show-page .order-card,
        .admin-order-show-page .order-table-shell {
            background: #fff;
            border-color: #e5e7eb;
        }

        .admin-order-show-page .order-soft,
        .admin-order-show-page .order-table-head,
        .admin-order-show-page .order-table-foot {
            background: #f9fafb;
        }

        .admin-order-show-page .order-row:hover {
            background: rgba(15, 23, 42, 0.035);
        }

        .admin-order-show-page .order-muted {
            color: #6b7280 !important;
        }

        .admin-order-show-page .order-text {
            color: #111827 !important;
        }

        html[data-theme="dark"] .admin-order-show-page .order-card,
        html[data-theme="dark"] .admin-order-show-page .order-table-shell {
            background: #1f2937;
            border-color: #374151;
        }

        html[data-theme="dark"] .admin-order-show-page .order-soft,
        html[data-theme="dark"] .admin-order-show-page .order-table-head,
        html[data-theme="dark"] .admin-order-show-page .order-table-foot {
            background: rgba(17, 24, 39, 0.78);
        }

        html[data-theme="dark"] .admin-order-show-page .order-row:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        html[data-theme="dark"] .admin-order-show-page .order-muted {
            color: #9ca3af !important;
        }

        html[data-theme="dark"] .admin-order-show-page .order-text {
            color: #f9fafb !important;
        }
    </style>

    <div class="admin-order-show-page py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <section class="order-card rounded-3xl border p-7 shadow-sm">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="order-muted text-[0.95rem] uppercase tracking-[0.18em]">Order overview</p>
                        <h1 class="order-text mt-2 text-[2rem] font-bold">Order #{{ $order->id }}</h1>
                        <p class="order-muted mt-2 text-[0.98rem]">
                            Review fulfilment progress, customer details, and order totals before making admin changes.
                        </p>
                        <p class="order-text mt-2 text-[0.98rem]">
                            Placed on {{ $order->created_at->format('d M Y \\a\\t H:i') }}
                        </p>
                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <span class="rounded-full px-3 py-1.5 text-[0.82rem] font-semibold {{ $statusClasses }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <span class="order-soft rounded-full bg-gray-100 px-3 py-1.5 text-[0.82rem] font-semibold order-text">
                                {{ $order->items->count() }} items
                            </span>
                            <span class="rounded-full bg-cyan-50 px-3 py-1.5 text-[0.82rem] font-semibold text-cyan-700">
                                Total £{{ number_format($order->total, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.orders.index') }}"
                           class="order-soft rounded-xl px-4 py-3 text-[0.95rem] font-semibold order-text transition hover:bg-gray-300">
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
                <div class="order-card rounded-3xl border p-6 shadow-sm">
                    <p class="order-muted text-[0.95rem] uppercase tracking-[0.18em]">Customer</p>
                    <div class="mt-4 space-y-3">
                        <div>
                            <p class="order-muted text-[0.82rem] font-semibold uppercase tracking-[0.16em]">Name</p>
                            <p class="order-text mt-1 text-[1.02rem] font-semibold">{{ $order->user?->name ?? 'Unknown customer' }}</p>
                        </div>
                        <div>
                            <p class="order-muted text-[0.82rem] font-semibold uppercase tracking-[0.16em]">Email</p>
                            <p class="order-text mt-1 text-[1.02rem]">{{ $order->user?->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="order-muted text-xs font-semibold uppercase tracking-[0.16em]">Address</p>
                            <p class="order-text mt-1 text-base leading-relaxed">
                                {{ $customerAddress !== '' ? $customerAddress : 'No saved address on profile' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="order-card rounded-3xl border p-6 shadow-sm">
                    <p class="order-muted text-[0.95rem] uppercase tracking-[0.18em]">Order summary</p>
                    <div class="mt-4 space-y-3">
                        <div class="order-soft flex items-center justify-between rounded-2xl px-4 py-3">
                            <span class="order-text text-[0.95rem] font-semibold">Order ID</span>
                            <span class="order-text text-[0.95rem]">#{{ $order->id }}</span>
                        </div>
                        <div class="order-soft flex items-center justify-between rounded-2xl px-4 py-3">
                            <span class="order-text text-[0.95rem] font-semibold">Items</span>
                            <span class="order-text text-[0.95rem]">{{ $order->items->sum('quantity') }}</span>
                        </div>
                        <div class="order-soft flex items-center justify-between rounded-2xl px-4 py-3">
                            <span class="order-text text-sm font-semibold">Items subtotal</span>
                            <span class="order-text text-sm">£{{ number_format($itemsSubtotal, 2) }}</span>
                        </div>
                        <div class="order-soft flex items-center justify-between rounded-2xl px-4 py-3">
                            <span class="order-text text-sm font-semibold">Shipping method</span>
                            <span class="order-text text-sm">{{ $shippingMethod }}</span>
                        </div>
                        <div class="order-soft flex items-center justify-between rounded-2xl px-4 py-3">
                            <span class="order-text text-sm font-semibold">Shipping cost</span>
                            <span class="order-text text-sm">£{{ number_format($shippingCost, 2) }}</span>
                        </div>
                        <div class="order-soft flex items-center justify-between rounded-2xl px-4 py-3">
                            <span class="order-text text-sm font-semibold">Total</span>
                            <span class="order-text text-sm font-semibold">£{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="order-card rounded-3xl border p-6 shadow-sm">
                    <p class="order-muted text-[0.95rem] uppercase tracking-[0.18em]">Management note</p>
                    <div class="order-soft mt-4 rounded-2xl px-4 py-4">
                        <p class="order-text text-[0.98rem] leading-relaxed">
                            Use this page to review the full order contents before updating the status from the main orders table. Cancelling here will still restore product stock automatically.
                        </p>
                    </div>
                </div>
            </section>

            <section class="order-table-shell overflow-hidden rounded-3xl border shadow-sm">
                <div class="flex items-center justify-between border-b border-[#3a3a3d] px-6 py-5">
                    <div>
                        <h2 class="order-text text-[1.2rem] font-semibold">Items in this order</h2>
                        <p class="order-muted text-[0.95rem]">A full line-by-line breakdown of what was purchased.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-[0.95rem]">
                        <thead class="order-table-head text-left">
                            <tr class="order-muted text-[0.82rem] uppercase tracking-[0.18em]">
                                <th class="px-5 py-4 font-semibold">Product</th>
                                <th class="px-5 py-4 font-semibold">Platform</th>
                                <th class="px-5 py-4 font-semibold">Quantity</th>
                                <th class="px-5 py-4 font-semibold">Unit price</th>
                                <th class="px-5 py-4 font-semibold">Shipping</th>
                                <th class="px-5 py-4 font-semibold text-right">Line total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#3a3a3d]">
                            @foreach($order->items as $it)
                                <tr class="order-row transition">
                                    <td class="px-5 py-4">
                                        <p class="order-text font-semibold">{{ $it->product?->name ?? 'Deleted product' }}</p>
                                    </td>
                                    <td class="order-text px-5 py-4">{{ $it->platform ?: ($it->product?->platform ?? 'Universal') }}</td>
                                    <td class="order-text px-5 py-4">x{{ $it->quantity }}</td>
                                    <td class="order-text px-5 py-4">£{{ number_format($it->price, 2) }}</td>
                                    <td class="order-text px-5 py-4">
                                        @if($loop->first)
                                            <div class="font-semibold">{{ $shippingMethod }}</div>
                                            <div class="order-muted text-xs">£{{ number_format($shippingCost, 2) }}</div>
                                        @else
                                            <span class="order-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="order-text px-5 py-4 text-right font-semibold">
                                        £{{ number_format(($it->price * $it->quantity) + ($loop->first ? $shippingCost : 0), 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="order-table-foot border-t">
                                <td class="order-text px-5 py-4 font-semibold" colspan="5">Grand total</td>
                                <td class="order-text px-5 py-4 text-right font-semibold">£{{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
