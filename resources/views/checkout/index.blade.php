<x-app-layout>
    <link rel="stylesheet" href="{{ asset('styles/checkout.css') }}" />

    <main class="checkout-main w-full max-w-7xl mx-auto py-8">
        <div class="checkout-container">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <section class="checkout-form-section">
                <h1 class="checkout-title">Checkout</h1>
                <p class="checkout-subtitle">Please enter your shipping and payment details.</p>

                <form id="checkout-form" class="checkout-form" action="{{ route('checkout.place') }}" method="POST">
                    @csrf
                  
                    <div class="form-section">
                        <h2 class="text-xl font-bold mb-4">Contact Information</h2>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ auth()->check() ? auth()->user()->email : '' }}" placeholder="john@example.com" required>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2 class="text-xl font-bold mb-4">Shipping Address</h2>
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="first-name">First Name</label>
                                <input type="text" id="first-name" name="first-name" value="{{ auth()->check() ? explode(' ', auth()->user()->name)[0] : '' }}" required>
                            </div>
                            <div class="form-group half">
                                <label for="last-name">Last Name</label>
                                <input type="text" id="last-name" name="last-name" value="{{ auth()->check() && count(explode(' ', auth()->user()->name)) > 1 ? explode(' ', auth()->user()->name)[1] : '' }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" placeholder="123 Gaming Street" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group third">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" placeholder="London" required>
                            </div>
                            <div class="form-group third">
                                <label for="country">Country</label>
                                <select id="country" name="country" required>
                                    <option value="UK">United Kingdom</option>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                </select>
                            </div>
                            <div class="form-group third">
                                <label for="postal-code">Postal Code</label>
                                <input type="text" id="postal-code" name="postal-code" placeholder="AB12 3CD" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section pb-0 border-b-0">
                        <h2 class="text-xl font-bold mb-4">Payment Details</h2>
                        <div class="payment-methods">
                            <label class="payment-method-label selected">
                                <input type="radio" name="payment-type" value="card" checked>
                                Credit / Debit Card
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label for="card-number">Card Number</label>
                            <input type="text" id="card-number" name="card-number" placeholder="0000 0000 0000 0000" maxlength="19" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="expiry">Expiry Date (MM/YY)</label>
                                <input type="text" id="expiry" name="expiry" placeholder="12/25" maxlength="5" required>
                            </div>
                            <div class="form-group half">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-checkout-btn mt-6">Complete Order</button>
                </form>
            </section>

            <aside class="checkout-summary-section">
                <h2 class="text-xl font-bold mb-6">Order Summary</h2>
                <div class="checkout-items">
                    @foreach ($items as $item)
                        <div class="checkout-item">
                            @if ($item['product']->image)
                                <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}">
                            @else
                                <img src="{{ asset('assets/gameHeadset.png') }}" alt="{{ $item['product']->name }}">
                            @endif
                            <div class="checkout-item-details">
                                <p class="item-name">{{ $item['product']->name }}</p>
                                <p class="item-qty">Qty: {{ $item['quantity'] }}</p>
                            </div>
                            <div class="checkout-item-price whitespace-nowrap">£{{ number_format($item['subtotal'], 2) }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="summary-totals mt-6">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>£{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>£4.99</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax</span>
                        <span>Included</span>
                    </div>
                    <div class="summary-row grand-total">
                        <span>Total</span>
                        <span>£{{ number_format($total + 4.99, 2) }}</span>
                    </div>
                </div>
            </aside>

        </div>
    </main>

</x-app-layout>
