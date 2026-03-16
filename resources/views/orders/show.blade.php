<x-app-layout>
    <link rel="stylesheet" href="{{ asset('styles/orders.css') }}" />

    <div class="orders-main invoice-page-shell w-full max-w-5xl mx-auto py-8">
        <div class="orders-header invoice-page-header">
            <h1 class="orders-title">Invoice</h1>
        </div>

        <div class="orders-container invoice-container">
            <article class="order-card invoice-card">
                <div class="order-body invoice-body">
                    <div class="orders-header invoice-header">
                        <h1 class="orders-title invoice-title">
                            <span class="invoice-order-label">Order #:</span>
                            <span class="invoice-order-number">VX-{{ $order->id }}</span>
                        </h1>
                    </div>

                    <div class="invoice-summary">
                        <p class="invoice-summary-line">
                            <span class="item-label">Status:</span>
                            <span class="invoice-summary-value">{{ ucfirst($order->status) }}</span>
                        </p>
                        @if ($order->shipping_method || (float) $order->shipping_cost > 0)
                            <p class="invoice-summary-line">
                                <span class="item-label">Shipping:</span>
                                <span class="invoice-summary-value">
                                    {{ $order->shipping_method ?: 'Delivery' }}
                                    @if ((float) $order->shipping_cost > 0)
                                        ({{ number_format($order->shipping_cost, 2) }} GBP)
                                    @endif
                                </span>
                            </p>
                        @endif
                        @if ($order->discount_code || (float) $order->discount_amount > 0)
                            <p class="invoice-summary-line">
                                <span class="item-label">Discount:</span>
                                <span class="invoice-summary-value">
                                    {{ $order->discount_code ?: 'Promo code' }}
                                    @if ((float) $order->discount_amount > 0)
                                        (-{{ number_format($order->discount_amount, 2) }} GBP)
                                    @endif
                                </span>
                            </p>
                        @endif
                        <p class="invoice-summary-line">
                            <span class="item-label">Total:</span>
                            <span class="invoice-summary-value">{{ number_format($order->total, 2) }} GBP</span>
                        </p>
                    </div>

                    <section class="invoice-items-section">
                        <h2 class="invoice-section-title">Items</h2>

                        <table class="invoice-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Platform</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>{{ optional($item->product)->name ?? 'Product Deleted' }}</td>
                                        <td>{{ $item->platform ?: (optional($item->product)->platform ?? 'Universal') }}</td>
                                        <td>{{ number_format($item->price, 2) }} GBP</td>
                                        <td>{{ $item->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>

                    <div class="invoice-actions">
                        <a href="{{ route('orders.index') }}" class="btn-secondary">Back to Orders</a>
                        <a href="{{ url('/pages/index.html') }}" class="btn-secondary">Back to Home</a>
                    </div>
                </div>
            </article>
        </div>
    </div>
</x-app-layout>
