<x-app-layout>
    <link rel="stylesheet" href="{{ asset('styles/orders.css') }}" />

    <main class="orders-main w-full max-w-5xl mx-auto py-8">
        <div class="orders-container">
            
            <div class="orders-header mb-8">
                <h1 class="orders-title text-3xl font-bold">Order History</h1>
                <p class="orders-subtitle text-gray-600">Check the status of recent orders, manage returns, and discover similar products.</p>
            </div>

            @if ($orders->count() === 0)
                <div class="bg-white p-8 text-center rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xl text-gray-500 mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ url('/pages/ShopAll.html') }}" class="inline-block px-6 py-3 bg-black text-white font-bold rounded-lg hover:bg-gray-800 transition">Start Shopping</a>
                </div>
            @else
                <div class="orders-list">
                    @foreach ($orders as $order)
                        <article class="order-card">
                            <div class="order-header">
                                <div class="order-info-block">
                                    <span class="label">Order placed</span>
                                    <span class="value">{{ $order->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="order-info-block">
                                    <span class="label">Total</span>
                                    <span class="value">£{{ number_format($order->total, 2) }}</span>
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
                                <div class="order-status {{ strtolower($order->status) }}">
                                    <div class="status-indicator"></div>
                                    <span>{{ ucfirst($order->status) }}</span>
                                </div>
                                @if(strtolower($order->status) == 'processing')
                                    <p class="status-desc">Your order is being prepared for shipment.</p>
                                @elseif(strtolower($order->status) == 'delivered')
                                    <p class="status-desc">Delivered successfully.</p>
                                @else
                                    <p class="status-desc">Order status updated.</p>
                                @endif
                                
                                <div class="order-items">
                                    @foreach($order->items as $item)
                                        <div class="order-item">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name ?? 'Product' }}">
                                            @else
                                                <img src="{{ asset('assets/gameHeadset.png') }}" alt="Fallback Product">
                                            @endif
                                            
                                            <div class="item-details">
                                                <h4>{{ $item->product->name ?? 'Unknown Product' }}</h4>
                                                @if($item->product && $item->product->platform)
                                                    <p class="item-meta">Platform: {{ $item->product->platform }}</p>
                                                @endif
                                                <p class="item-price">£{{ number_format($item->price, 2) }} <span class="item-qty">Qty: {{ $item->quantity }}</span></p>
                                            </div>
                                            <div class="item-actions">
                                                <a href="{{ url('/pages/ShopAll.html') }}" class="btn-ghost block w-full">Buy it again</a>
                                                <a href="{{ url('/pages/index.html#contact-us') }}" class="btn-ghost block w-full mt-2">Leave a review</a>
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
    </main>

</x-app-layout>
