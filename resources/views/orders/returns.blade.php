<x-app-layout>
    <link rel="stylesheet" href="{{ asset('styles/orders.css') }}" />

    <div class="orders-main w-full max-w-5xl mx-auto py-8">
        <div class="orders-header mb-8">
            <h1 class="orders-title text-3xl font-bold">Returns & Refunds</h1>
            <p class="status-desc mt-2">Order VX-{{ $order->id }} · Choose the item you need help with.</p>
        </div>

        <div class="orders-container">
            <article class="order-card">
                <div class="order-header">
                    <div class="order-info-block">
                        <span class="label">Order Placed</span>
                        <span class="value">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="order-info-block">
                        <span class="label">Status</span>
                        <span class="value">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="order-info-block">
                        <span class="label">Total</span>
                        <span class="value">{{ number_format($order->total, 2) }} GBP</span>
                    </div>
                    <div class="order-actions">
                        <a href="{{ route('orders.index') }}" class="btn-secondary">Back to Orders</a>
                    </div>
                </div>

                <div class="order-body">
                    <div class="order-items">
                        @foreach($order->items as $item)
                            @php
                                $latestReturnRequest = $item->latestReturnRequest;
                            @endphp
                            <div class="order-item">
                                @if($item->product && $item->product->image_url)
                                    <img src="{{ asset('storage/' . $item->product->image_url) }}" alt="{{ $item->product->name ?? 'Product' }}">
                                @else
                                    <div class="order-item-no-image">No image</div>
                                @endif

                                <div class="item-details">
                                    <h4><span class="item-label">Product Name:</span> {{ $item->product->name ?? 'Unknown Product' }}</h4>
                                    @if($item->platform || ($item->product && $item->product->platform))
                                        <p class="item-meta"><span class="item-label">Platform:</span> <span class="item-meta-value">{{ $item->platform ?: $item->product->platform }}</span></p>
                                    @endif
                                    @if($latestReturnRequest)
                                        <p class="item-meta"><span class="item-label">Request status:</span> <span class="item-meta-value">{{ ucfirst($latestReturnRequest->status) }}</span></p>
                                        @if($latestReturnRequest->admin_notes)
                                            <p class="item-meta"><span class="item-label">Support update:</span> <span class="item-meta-value">{{ \Illuminate\Support\Str::limit($latestReturnRequest->admin_notes, 120) }}</span></p>
                                        @endif
                                    @endif
                                    <p class="item-price"><span class="item-label">Price:</span> <span class="price-value">{{ number_format($item->price, 2) }} GBP</span></p>
                                    <p class="item-qty"><span class="item-label">Quantity:</span> {{ $item->quantity }}</p>
                                </div>

                                <div class="item-actions">
                                    @if($latestReturnRequest && $latestReturnRequest->status === 'pending')
                                        <a href="{{ route('orders.return.form', $item->id) }}" class="btn-ghost block w-full">View Request</a>
                                    @elseif($latestReturnRequest && $latestReturnRequest->status === 'approved')
                                        <a href="{{ route('orders.return.form', $item->id) }}" class="btn-ghost block w-full">View Approved Request</a>
                                    @elseif($latestReturnRequest && $latestReturnRequest->status === 'declined')
                                        <a href="{{ route('orders.return.form', $item->id) }}" class="btn-ghost block w-full">View Declined Request</a>
                                    @else
                                        <a href="{{ route('orders.return.form', $item->id) }}" class="btn-ghost block w-full">Request Return / Refund</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </article>
        </div>
    </div>
</x-app-layout>
