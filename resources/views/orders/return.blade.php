<x-app-layout>
    <link rel="stylesheet" href="{{ asset('styles/orders.css') }}" />

    <div class="orders-main w-full max-w-5xl mx-auto return-page-main">
        <div class="orders-container return-container">
            <div class="orders-header mb-8">
                <h1 class="orders-title text-3xl font-bold">Request Refund</h1>
            </div>

            <article class="order-card return-card">
                <div class="order-header return-card-header">
                    <div class="order-info-block">
                        <span class="label">Order #</span>
                        <span class="value">VX-{{ $orderItem->order->id }}</span>
                    </div>
                    <div class="order-info-block">
                        <span class="label">Product</span>
                        <span class="value">{{ $orderItem->product?->name ?? 'Unknown Product' }}</span>
                    </div>
                    <div class="order-info-block">
                        <span class="label">Platform</span>
                        <span class="value">{{ $orderItem->platform ?: ($orderItem->product?->platform ?: 'Universal') }}</span>
                    </div>
                    <div class="order-info-block">
                        <span class="label">Quantity</span>
                        <span class="value">{{ $orderItem->quantity }}</span>
                    </div>
                </div>

                <div class="order-body return-card-body">
                    <form method="POST" action="{{ route('orders.return.submit', $orderItem->id) }}" class="return-form">
                        @csrf

                        <label class="return-field">
                            <span>Reason for requesting refund</span>
                            <div class="return-input-shell">
                                <textarea name="reason" rows="3" class="return-input return-textarea" placeholder="Tell us why you'd like to request a refund for this product.">{{ old('reason') }}</textarea>
                            </div>
                            @error('reason')
                                <p class="return-error">{{ $message }}</p>
                            @enderror
                        </label>

                        <div class="return-actions">
                            <a href="{{ route('orders.index') }}" class="btn-secondary">Back to Orders</a>
                            <button type="submit" class="btn-ghost">Send Request</button>
                        </div>
                    </form>
                </div>
            </article>
        </div>
    </div>
</x-app-layout>
