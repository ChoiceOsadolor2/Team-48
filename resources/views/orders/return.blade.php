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
                    @if ($orderItem->latestReturnRequest)
                        <div class="mb-5 rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white/90">
                            Latest request status:
                            <span class="font-semibold">{{ ucfirst($orderItem->latestReturnRequest->status) }}</span>
                            @if ($orderItem->latestReturnRequest->reviewed_at)
                                <span class="opacity-80">· reviewed {{ $orderItem->latestReturnRequest->reviewed_at->diffForHumans() }}</span>
                            @endif
                        </div>

                        @if ($orderItem->latestReturnRequest->admin_notes)
                            <div class="mb-5 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-4 text-sm text-white/95">
                                <p class="text-xs uppercase tracking-[0.18em] text-emerald-200">Support update</p>
                                <p class="mt-2 whitespace-pre-line leading-7">{{ $orderItem->latestReturnRequest->admin_notes }}</p>
                            </div>
                        @endif
                    @endif

                    @php
                        $latestReturnRequest = $orderItem->latestReturnRequest;
                        $isLockedRequest = $latestReturnRequest && in_array($latestReturnRequest->status, ['pending', 'approved'], true);
                    @endphp

                    @if ($isLockedRequest)
                        <p class="return-copy">
                            This request has already been submitted and cannot be sent again from this page.
                        </p>

                        <div class="return-actions">
                            <a href="{{ route('orders.returns.index', $orderItem->order->id) }}" class="btn-secondary">Back to Returns</a>
                        </div>
                    @else
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
                                <a href="{{ route('orders.returns.index', $orderItem->order->id) }}" class="btn-secondary">Back to Returns</a>
                                <button type="submit" class="btn-ghost">Send Request</button>
                            </div>
                        </form>
                    @endif
                </div>
            </article>
        </div>
    </div>
</x-app-layout>
