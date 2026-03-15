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
                    <p class="return-copy">
                        Tell us what you need and we'll send your request to support for review.
                    </p>

                    <form method="POST" action="{{ route('orders.return.submit', $orderItem->id) }}" class="return-form">
                        @csrf

                        <label class="return-field">
                            <span>Request type</span>
                            <select name="request_type" class="return-input">
                                <option value="return" {{ old('request_type') === 'return' ? 'selected' : '' }}>Return</option>
                                <option value="refund" {{ old('request_type') === 'refund' ? 'selected' : '' }}>Refund</option>
                                <option value="exchange" {{ old('request_type') === 'exchange' ? 'selected' : '' }}>Exchange</option>
                            </select>
                            @error('request_type')
                                <p class="return-error">{{ $message }}</p>
                            @enderror
                        </label>

                        <label class="return-field">
                            <span>Reason</span>
                            <textarea name="reason" rows="6" class="return-input return-textarea" placeholder="Tell us why you'd like to return or refund this product.">{{ old('reason') }}</textarea>
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
