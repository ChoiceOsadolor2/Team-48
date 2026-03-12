<x-app-layout>
    <link rel="stylesheet" href="{{ asset('styles/checkout.css') }}" />

    <div class="checkout-main w-full max-w-7xl mx-auto py-8">
        <div class="checkout-page-header">
            <h1 class="checkout-page-title">Checkout</h1>
        </div>
        <div class="checkout-container">
            @if (session('status'))
                <div class="checkout-flash">
                    {{ session('status') }}
                </div>
            @endif

            <section class="checkout-form-section">
                <form id="checkout-form" class="checkout-form" action="{{ route('checkout.place') }}" method="POST" novalidate>
                    @csrf

                    <div class="form-section">
                        <h2>Contact Information</h2>
                        <div class="form-group">
                            <label for="email-display">Email Address</label>
                            <input type="hidden" id="email" name="email" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                            <div
                                id="email-display"
                                class="checkout-editable-field"
                                contenteditable="true"
                                role="textbox"
                                aria-label="Email Address"
                                spellcheck="false"
                                data-placeholder="john@example.com"
                                data-sync-target="email"
                            >{{ auth()->check() ? auth()->user()->email : '' }}</div>
                            <p id="email-error" class="checkout-field-error"></p>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Shipping Address</h2>
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="first-name-display">First Name</label>
                                <input type="hidden" id="first-name" name="first-name" value="{{ auth()->check() ? explode(' ', auth()->user()->name)[0] : '' }}">
                                <div
                                    id="first-name-display"
                                    class="checkout-editable-field"
                                    contenteditable="true"
                                    role="textbox"
                                    aria-label="First Name"
                                spellcheck="false"
                                data-placeholder="First Name"
                                data-sync-target="first-name"
                            >{{ auth()->check() ? explode(' ', auth()->user()->name)[0] : '' }}</div>
                                <p id="first-name-error" class="checkout-field-error"></p>
                            </div>
                            <div class="form-group half">
                                <label for="last-name-display">Last Name</label>
                                <input type="hidden" id="last-name" name="last-name" value="{{ auth()->check() && count(explode(' ', auth()->user()->name)) > 1 ? explode(' ', auth()->user()->name)[1] : '' }}">
                                <div
                                    id="last-name-display"
                                    class="checkout-editable-field"
                                    contenteditable="true"
                                    role="textbox"
                                    aria-label="Last Name"
                                    spellcheck="false"
                                    data-placeholder="Last Name"
                                    data-sync-target="last-name"
                                >{{ auth()->check() && count(explode(' ', auth()->user()->name)) > 1 ? explode(' ', auth()->user()->name)[1] : '' }}</div>
                                <p id="last-name-error" class="checkout-field-error"></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address-display">Address</label>
                            <input type="hidden" id="address" name="address">
                            <div
                                id="address-display"
                                class="checkout-editable-field"
                                contenteditable="true"
                                role="textbox"
                                aria-label="Address"
                                spellcheck="false"
                                data-placeholder="123 Gaming Street"
                                data-sync-target="address"
                            ></div>
                            <p id="address-error" class="checkout-field-error"></p>
                        </div>
                        <div class="form-row">
                            <div class="form-group third">
                                <label for="city-display">City</label>
                                <input type="hidden" id="city" name="city">
                                <div
                                    id="city-display"
                                    class="checkout-editable-field"
                                    contenteditable="true"
                                    role="textbox"
                                    aria-label="City"
                                    spellcheck="false"
                                    data-placeholder="London"
                                    data-sync-target="city"
                                ></div>
                                <p id="city-error" class="checkout-field-error"></p>
                            </div>
                            <div class="form-group third">
                                <label for="country-display">Country</label>
                                <input type="hidden" id="country" name="country" value="">
                                <div
                                    id="country-display"
                                    class="checkout-editable-field"
                                    contenteditable="true"
                                    role="textbox"
                                    aria-label="Country"
                                    spellcheck="false"
                                    data-placeholder="United Kingdom"
                                    data-sync-target="country"
                                ></div>
                                <p id="country-error" class="checkout-field-error"></p>
                            </div>
                            <div class="form-group third">
                                <label for="postal-code-display">Postal Code</label>
                                <input type="hidden" id="postal-code" name="postal-code">
                                <div
                                    id="postal-code-display"
                                    class="checkout-editable-field"
                                    contenteditable="true"
                                    role="textbox"
                                    aria-label="Postal Code"
                                    spellcheck="false"
                                    data-placeholder="AB12 3CD"
                                    data-sync-target="postal-code"
                                ></div>
                                <p id="postal-code-error" class="checkout-field-error"></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-section checkout-payment-section">
                        <h2>Payment Details</h2>
                        <div class="payment-methods">
                            <label class="payment-method-label selected">
                                <input type="radio" name="payment-type" value="card" checked>
                                Credit / Debit Card
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="card-number">Card Number</label>
                            <input type="text" id="card-number" name="card-number" placeholder="0000 0000 0000 0000" maxlength="19" required>
                            <p id="card-number-error" class="checkout-field-error"></p>
                        </div>
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="expiry">Expiry Date (MM/YY)</label>
                                <input type="text" id="expiry" name="expiry" placeholder="12/25" maxlength="5" required>
                                <p id="expiry-error" class="checkout-field-error"></p>
                            </div>
                            <div class="form-group half">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3" required>
                                <p id="cvv-error" class="checkout-field-error"></p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-checkout-btn mt-6">Complete Order</button>
                </form>
            </section>

            <aside class="checkout-summary-section">
                <h2 class="checkout-summary-title">Order Summary</h2>
                <div class="checkout-items">
                    @foreach ($items as $item)
                        <div class="checkout-item">
                            @if ($item['product']->image)
                                <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}">
                            @else
                                <div class="checkout-item-no-image">No image</div>
                            @endif
                            <div class="checkout-item-details">
                                <p class="item-name">
                                    <span class="checkout-item-label">Product Name:</span>
                                    <span class="checkout-item-value">{{ $item['product']->name }}</span>
                                </p>
                                @if ($item['product']->platform)
                                    <p class="item-platform">
                                        <span class="checkout-item-label">Platform:</span>
                                        <span class="checkout-item-value">{{ $item['product']->platform }}</span>
                                    </p>
                                @endif
                                <p class="item-price">
                                    <span class="checkout-item-label">Price:</span>
                                    <span class="checkout-item-value">{{ number_format($item['subtotal'], 2) }} GBP</span>
                                </p>
                                <p class="item-qty">
                                    <span class="checkout-item-label">Quantity:</span>
                                    <span class="checkout-item-value">{{ $item['quantity'] }}</span>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="summary-totals mt-6">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>{{ number_format($total, 2) }} GBP</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>4.99 GBP</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax</span>
                        <span>Included</span>
                    </div>
                    <div class="summary-row grand-total">
                        <span>Total</span>
                        <span>{{ number_format($total + 4.99, 2) }} GBP</span>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('checkout-form');

            document.querySelectorAll('.checkout-decoy-input[data-sync-target]').forEach(function (input) {
                const syncTarget = document.getElementById(input.dataset.syncTarget);
                if (!syncTarget) return;

                const syncValue = function () {
                    syncTarget.value = input.value;
                };

                syncValue();
                input.addEventListener('input', syncValue);
                input.addEventListener('change', syncValue);
            });

            document.querySelectorAll('.checkout-editable-field[data-sync-target]').forEach(function (field) {
                const syncTarget = document.getElementById(field.dataset.syncTarget);
                if (!syncTarget) return;

                const syncValue = function () {
                    syncTarget.value = field.textContent.trim();
                };

                syncValue();
                field.addEventListener('input', syncValue);
                field.addEventListener('blur', syncValue);
            });

            const fieldConfigs = [
                { sourceId: 'email-display', errorId: 'email-error', type: 'contenteditable' },
                { sourceId: 'first-name-display', errorId: 'first-name-error', type: 'contenteditable' },
                { sourceId: 'last-name-display', errorId: 'last-name-error', type: 'contenteditable' },
                { sourceId: 'address-display', errorId: 'address-error', type: 'contenteditable' },
                { sourceId: 'city-display', errorId: 'city-error', type: 'contenteditable' },
                { sourceId: 'country-display', errorId: 'country-error', type: 'contenteditable' },
                { sourceId: 'postal-code-display', errorId: 'postal-code-error', type: 'contenteditable' },
                { sourceId: 'card-number', errorId: 'card-number-error', type: 'input' },
                { sourceId: 'expiry', errorId: 'expiry-error', type: 'input' },
                { sourceId: 'cvv', errorId: 'cvv-error', type: 'input' }
            ];

            function getFieldValue(config) {
                const el = document.getElementById(config.sourceId);
                if (!el) return '';
                if (config.type === 'contenteditable') {
                    return (el.textContent || '').trim();
                }
                return (el.value || '').trim();
            }

            function showFieldError(errorId, message) {
                const errorEl = document.getElementById(errorId);
                if (!errorEl) return;
                errorEl.textContent = message || '';
                if (message) {
                    errorEl.style.display = 'block';
                    errorEl.style.animation = 'none';
                    void errorEl.offsetWidth;
                    errorEl.style.animation = 'fadeSlideUp 0.3s ease';
                } else {
                    errorEl.style.display = 'none';
                    errorEl.style.animation = 'none';
                }
            }

            fieldConfigs.forEach(function (config) {
                const el = document.getElementById(config.sourceId);
                if (!el) return;

                const clearError = function () {
                    showFieldError(config.errorId, '');
                };

                if (config.type === 'contenteditable') {
                    el.addEventListener('input', clearError);
                    el.addEventListener('blur', clearError);
                } else {
                    el.addEventListener('input', clearError);
                    el.addEventListener('change', clearError);
                }
            });

            if (form) {
                form.addEventListener('submit', function (event) {
                    let hasError = false;
                    let firstInvalidField = null;

                    fieldConfigs.forEach(function (config) {
                        const value = getFieldValue(config);
                        const sourceEl = document.getElementById(config.sourceId);

                        if (!value) {
                            showFieldError(config.errorId, 'Empty Field');
                            if (!firstInvalidField) {
                                firstInvalidField = sourceEl;
                            }
                            hasError = true;
                        } else {
                            showFieldError(config.errorId, '');
                        }
                    });

                    if (hasError) {
                        event.preventDefault();
                        if (firstInvalidField) {
                            if (firstInvalidField.isContentEditable) {
                                firstInvalidField.focus();
                            } else {
                                firstInvalidField.focus();
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
