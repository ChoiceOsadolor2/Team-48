<x-app-layout>
    <link rel="stylesheet" href="{{ asset('styles/orders.css') }}" />

    <div class="orders-main w-full max-w-5xl mx-auto">
        <div class="orders-container">
            
            <div class="orders-header mb-8">
                <h1 class="orders-title text-3xl font-bold">Order History</h1>
            </div>

            @if ($orders->count() === 0)
                <div class="orders-empty-state">
                    <p class="orders-empty-copy">You haven't placed any orders yet.</p>
                    <a href="{{ url('/pages/ShopAll.html') }}" class="btn-secondary orders-empty-action">Start Shopping</a>
                </div>
            @else
                <div class="orders-list">
                    @foreach ($orders as $order)
                        @php($orderRefundStatus = $order->items->pluck('refundRequest.status')->filter()->contains('pending') ? 'pending' : ($order->items->pluck('refundRequest.status')->filter()->contains('approved') ? 'approved' : ($order->items->pluck('refundRequest.status')->filter()->contains('denied') ? 'denied' : null)))
                        <article class="order-card">
                            <div class="order-header">
                                <div class="order-info-block">
                                    <span class="label">Order Placed</span>
                                    <span class="value">{{ $order->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="order-info-block">
                                    <span class="label">Total</span>
                                    <span class="value">{{ number_format($order->total, 2) }} GBP</span>
                                </div>
                                <div class="order-info-block order-number-block">
                                    <span class="label">Order #</span>
                                    <span class="value ml-2">VX-{{ $order->id }}</span>
                                </div>
                                <div class="order-actions">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn-secondary">View Invoice</a>
                                </div>
                            </div>
                            
                            <div class="order-body">
                                <div class="order-status-row">
                                    <div class="order-status {{ $orderRefundStatus ? 'refund-' . $orderRefundStatus : strtolower($order->status) }}">
                                        <div class="status-indicator"></div>
                                        <span>
                                            @if($orderRefundStatus === 'pending')
                                                Refund Request Sent
                                            @elseif($orderRefundStatus === 'approved')
                                                Refund Approved
                                            @elseif($orderRefundStatus === 'denied')
                                                Refund Denied
                                            @else
                                                {{ ucfirst($order->status) }}
                                            @endif
                                        </span>
                                    </div>
                                    @if(strtolower($order->status) === 'processing' || in_array(strtolower((string) $order->status), ['completed', 'delivered']))
                                        <div class="order-status-actions">
                                            @if(strtolower($order->status) === 'processing')
                                                <form method="POST" action="{{ route('orders.cancel', $order->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn-ghost w-full">Cancel Order</button>
                                                </form>
                                            @endif

                                            @if(in_array(strtolower((string) $order->status), ['completed', 'delivered']))
                                                @foreach($order->items as $item)
                                                    @if($item->refundRequest)
                                                        <span class="btn-ghost btn-ghost-disabled block w-full" aria-disabled="true">
                                                            {{ $item->refundRequest->status === 'approved' ? 'Refund Approved' : ($item->refundRequest->status === 'denied' ? 'Refund Denied' : 'Refund Request Sent') }}
                                                        </span>
                                                    @else
                                                        <a href="{{ route('orders.return.form', $item->id) }}" class="btn-ghost block w-full">Request Refund</a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                @if(strtolower($order->status) == 'processing')
                                    <p class="status-desc">Your order is being prepared for shipment.</p>
                                @elseif($orderRefundStatus === 'pending')
                                    <p class="status-desc">We have received your refund request.</p>
                                @elseif($orderRefundStatus === 'approved')
                                    <p class="status-desc">This refund request has been approved.</p>
                                @elseif($orderRefundStatus === 'denied')
                                    <p class="status-desc">This refund request has been denied.</p>
                                @elseif(strtolower($order->status) == 'delivered')
                                    <p class="status-desc">Delivered successfully.</p>
                                @else
                                    <p class="status-desc">Order status updated.</p>
                                @endif
                                
                                <div class="order-items">
                                    @foreach($order->items as $item)
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
                                                <p class="item-price"><span class="item-label">Price:</span> <span class="price-value">{{ number_format($item->price, 2) }} GBP</span></p>
                                                <p class="item-qty"><span class="item-label">Quantity:</span> {{ $item->quantity }}</p>
                                            </div>
                                            <div class="item-actions">
                                                <a href="{{ $item->product ? url('/pages/ProductPage.html?id=' . $item->product->id) : url('/pages/ShopAll.html') }}" class="btn-ghost block w-full">Buy Again</a>
                                                @if(in_array(strtolower((string) $order->status), ['completed', 'delivered']))
                                                    <a href="{{ url('/pages/review.html') . '?' . http_build_query([
                                                        'order_item_id' => $item->id,
                                                    ]) }}" class="btn-ghost block w-full mt-2">Leave Review</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
