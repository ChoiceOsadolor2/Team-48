<x-app-layout>
    <link rel="stylesheet" href="{{ asset('styles/checkout.css') }}" />

    <div class="checkout-main w-full max-w-7xl mx-auto">
        <div class="checkout-page-header">
            <h1 class="checkout-page-title">Checkout</h1>
        </div>
        <div class="checkout-container">
            <section class="checkout-form-section">
                <form id="checkout-form" class="checkout-form" action="{{ route('checkout.place') }}" method="POST" novalidate>
                    @csrf

                    <div class="form-section">
                        <h2>Contact Information</h2>
                        <div class="form-group">
                            <label for="email-display">Email Address</label>
                            <input type="hidden" id="email" name="email" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                            <div class="checkout-field-wrap">
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
                            </div>
                            <p id="email-error" class="checkout-field-error" @if($errors->has('email')) style="display:block;" @endif>{{ $errors->first('email') }}</p>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Shipping Address</h2>
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="first-name-display">First Name</label>
                                <input type="hidden" id="first-name" name="first-name" value="{{ auth()->check() ? explode(' ', auth()->user()->name)[0] : '' }}">
                                <div class="checkout-field-wrap">
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
                                </div>
                                <p id="first-name-error" class="checkout-field-error" @if($errors->has('first-name')) style="display:block;" @endif>{{ $errors->first('first-name') }}</p>
                            </div>
                            <div class="form-group half">
                                <label for="last-name-display">Last Name</label>
                                <input type="hidden" id="last-name" name="last-name" value="{{ auth()->check() && count(explode(' ', auth()->user()->name)) > 1 ? explode(' ', auth()->user()->name)[1] : '' }}">
                                <div class="checkout-field-wrap">
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
                                </div>
                                <p id="last-name-error" class="checkout-field-error" @if($errors->has('last-name')) style="display:block;" @endif>{{ $errors->first('last-name') }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address-display">Address</label>
                            <input type="hidden" id="address" name="address">
                            <div class="checkout-field-wrap">
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
                            </div>
                            <p id="address-error" class="checkout-field-error" @if($errors->has('address')) style="display:block;" @endif>{{ $errors->first('address') }}</p>
                        </div>
                        <div class="form-row">
                            <div class="form-group third">
                                <label for="city-display">City</label>
                                <input type="hidden" id="city" name="city">
                                <div class="checkout-field-wrap">
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
                                </div>
                                <p id="city-error" class="checkout-field-error" @if($errors->has('city')) style="display:block;" @endif>{{ $errors->first('city') }}</p>
                            </div>
                            <div class="form-group third">
                                <label for="country-display">Country</label>
                                <input type="hidden" id="country" name="country" value="">
                                <div class="checkout-field-wrap">
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
                                </div>
                                <p id="country-error" class="checkout-field-error" @if($errors->has('country')) style="display:block;" @endif>{{ $errors->first('country') }}</p>
                            </div>
                            <div class="form-group third">
                                <label for="postal-code-display">Postal Code</label>
                                <input type="hidden" id="postal-code" name="postal-code">
                                <div class="checkout-field-wrap">
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
                                </div>
                                <p id="postal-code-error" class="checkout-field-error" @if($errors->has('postal-code')) style="display:block;" @endif>{{ $errors->first('postal-code') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-section checkout-shipping-section">
                        <h2>Shipping Options</h2>
                        <div class="shipping-options">
                            @foreach ($shippingOptions as $optionKey => $option)
                                <label class="checkout-option-card shipping-option-label {{ $selectedShipping['key'] === $optionKey ? 'selected' : '' }}">
                                    <input type="radio" name="shipping_option" value="{{ $optionKey }}" {{ $selectedShipping['key'] === $optionKey ? 'checked' : '' }}>
                                    <span class="checkout-option-copy">
                                        <span class="checkout-option-title">{{ $option['label'] }}</span>
                                        <span class="checkout-option-meta">{{ number_format($option['price'], 2) }} GBP</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <p id="shipping-option-error" class="checkout-field-error" @if($errors->has('shipping_option')) style="display:block;" @endif>{{ $errors->first('shipping_option') }}</p>
                    </div>

                    <div class="form-section checkout-payment-section">
                        <h2>Payment Details</h2>
                        <div class="payment-methods">
                            <label class="checkout-option-card payment-method-label selected">
                                <input type="radio" name="payment-type" value="card" checked>
                                Credit / Debit Card
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="card-number">Card Number</label>
                            <div class="checkout-field-wrap">
                                <input type="text" id="card-number" name="card-number" placeholder="0000 0000 0000 0000" maxlength="19" required>
                            </div>
                            <p id="card-number-error" class="checkout-field-error" @if($errors->has('card-number')) style="display:block;" @endif>{{ $errors->first('card-number') }}</p>
                        </div>
                        <div class="form-row">
                            <div class="form-group half">
                                <label for="expiry">Expiry Date (MM/YY)</label>
                                <div class="checkout-field-wrap">
                                    <input type="text" id="expiry" name="expiry" placeholder="12/25" maxlength="5" required>
                                </div>
                                <p id="expiry-error" class="checkout-field-error" @if($errors->has('expiry')) style="display:block;" @endif>{{ $errors->first('expiry') }}</p>
                            </div>
                            <div class="form-group half">
                                <label for="cvv">CVV</label>
                                <div class="checkout-field-wrap">
                                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3" required>
                                </div>
                                <p id="cvv-error" class="checkout-field-error" @if($errors->has('cvv')) style="display:block;" @endif>{{ $errors->first('cvv') }}</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-checkout-btn mt-6">Complete Order</button>
                </form>
            </section>

            <aside class="checkout-summary-section">
                <div class="checkout-summary-sticky">
                    <h2 class="checkout-summary-title">Order Summary</h2>
                    <div class="checkout-items">
                        @foreach ($items as $item)
                            <div class="checkout-item">
                                @if ($item['product']->image_url)
                                    <img src="{{ asset('storage/' . $item['product']->image_url) }}" alt="{{ $item['product']->name }}">
                                @else
                                    <div class="checkout-item-no-image">No image</div>
                                @endif
                                <div class="checkout-item-details">
                                    <p class="item-name">
                                        <span class="checkout-item-label">Product Name:</span>
                                        <span class="checkout-item-value">{{ $item['product']->name }}</span>
                                    </p>
                                    @if (!empty($item['platform']) || !empty($item['product']->platform))
                                        <p class="item-platform">
                                            <span class="checkout-item-label">Platform:</span>
                                            <span class="checkout-item-value">{{ $item['platform'] ?? $item['product']->platform }}</span>
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

                    <div class="checkout-discount-panel">
                        <div class="checkout-discount-copy">
                            <h3>Discount Code</h3>
                            <p>Add a promo code to update your order total before payment.</p>
                        </div>

                        <form action="{{ route('checkout.discount.apply') }}" method="POST" class="checkout-discount-form">
                            @csrf
                            <div class="checkout-discount-input-row">
                                <div class="checkout-field-wrap">
                                    <input
                                        type="text"
                                        name="discount_code"
                                        value="{{ old('discount_code', $appliedDiscount['code'] ?? '') }}"
                                        placeholder="Enter discount code"
                                        autocomplete="off"
                                    >
                                </div>
                                <button type="submit" class="checkout-discount-btn">Apply</button>
                            </div>
                        </form>

                        @if ($appliedDiscount)
                            <div class="checkout-discount-applied">
                                <div>
                                    <p class="checkout-discount-badge">Applied: {{ $appliedDiscount['code'] }}</p>
                                    <p class="checkout-discount-meta">{{ $appliedDiscount['label'] }}</p>
                                </div>

                                <form action="{{ route('checkout.discount.remove') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="checkout-discount-remove">Remove</button>
                                </form>
                            </div>
                        @endif

                        @if (session('discount_error'))
                            <p class="checkout-discount-message is-error">{{ session('discount_error') }}</p>
                        @endif

                        @if (session('discount_success'))
                            <p class="checkout-discount-message is-success">{{ session('discount_success') }}</p>
                        @endif
                    </div>

                    <div class="summary-totals mt-6" data-subtotal="{{ number_format($total, 2, '.', '') }}" data-discount="{{ number_format($appliedDiscount['amount'] ?? 0, 2, '.', '') }}">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="checkout_subtotal_value">{{ number_format($total, 2) }} GBP</span>
                        </div>
                        <div class="summary-row">
                            <span id="checkout_shipping_label">Shipping ({{ $selectedShipping['label'] }})</span>
                            <span id="checkout_shipping_value" class="{{ $selectedShipping['key'] ? '' : 'is-placeholder' }}">{{ number_format($shippingCost, 2) }} GBP</span>
                        </div>
                        <div class="summary-row {{ $appliedDiscount ? '' : 'hidden' }}" id="checkout_discount_row">
                            <span id="checkout_discount_label">Discount{{ !empty($appliedDiscount['code']) ? ' (' . $appliedDiscount['code'] . ')' : '' }}</span>
                            <span id="checkout_discount_value">-{{ number_format($appliedDiscount['amount'] ?? 0, 2) }} GBP</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax</span>
                            <span>Included</span>
                        </div>
                        <div class="summary-row grand-total">
                            <span>Total</span>
                            <span id="checkout_total_value">{{ number_format($total + $shippingCost, 2) }} GBP</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('checkout-form');
            const checkoutContainer = document.querySelector('.checkout-container');
            const checkoutSummarySection = document.querySelector('.checkout-summary-section');
            const checkoutSummarySticky = document.querySelector('.checkout-summary-sticky');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const shippingOptionInputs = Array.from(document.querySelectorAll('input[name="shipping_option"]'));
            const summaryTotals = document.querySelector('.summary-totals');
            const shippingLabel = document.getElementById('checkout_shipping_label');
            const shippingValue = document.getElementById('checkout_shipping_value');
            const totalValue = document.getElementById('checkout_total_value');
            const subtotalValue = document.getElementById('checkout_subtotal_value');
            const discountRow = document.getElementById('checkout_discount_row');
            const discountValue = document.getElementById('checkout_discount_value');
            const subtotal = Number(summaryTotals?.dataset.subtotal ?? 0);
            const discountAmount = Number(summaryTotals?.dataset.discount ?? 0);

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

            function updateOptionCardSelection(groupName) {
                document.querySelectorAll(`input[name="${groupName}"]`).forEach(function (input) {
                    const card = input.closest('.checkout-option-card');
                    if (!card) return;
                    card.classList.toggle('selected', input.checked);
                });
            }

            function syncShippingSummary() {
                const selectedInput = document.querySelector('input[name="shipping_option"]:checked');
                if (!selectedInput || !shippingLabel || !shippingValue || !totalValue) return;

                const card = selectedInput.closest('.shipping-option-label');
                const title = card?.querySelector('.checkout-option-title')?.textContent?.trim() || 'Shipping';
                const priceText = card?.querySelector('.checkout-option-meta')?.textContent?.trim() || '0.00 GBP';
                const price = parseFloat(priceText) || 0;

                shippingLabel.textContent = `Shipping (${title})`;
                shippingValue.textContent = `${price.toFixed(2)} GBP`;
                shippingValue.classList.remove('is-placeholder');
                if (discountRow && discountValue) {
                    discountRow.classList.toggle('hidden', discountAmount <= 0);
                    discountValue.textContent = `-${discountAmount.toFixed(2)} GBP`;
                }
                totalValue.textContent = `${Math.max(0, (subtotal + price) - discountAmount).toFixed(2)} GBP`;
                if (subtotalValue) {
                    subtotalValue.textContent = `${subtotal.toFixed(2)} GBP`;
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

            shippingOptionInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    updateOptionCardSelection('shipping_option');
                    syncShippingSummary();
                    showFieldError('shipping-option-error', '');
                });
            });

            document.querySelectorAll('input[name="payment-type"]').forEach(function (input) {
                input.addEventListener('change', function () {
                    updateOptionCardSelection('payment-type');
                });
            });

            updateOptionCardSelection('shipping_option');
            updateOptionCardSelection('payment-type');
            syncShippingSummary();

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
                        } else if (config.sourceId === 'email-display' && !emailPattern.test(value)) {
                            showFieldError(config.errorId, 'Please enter a valid email format');
                            if (!firstInvalidField) {
                                firstInvalidField = sourceEl;
                            }
                            hasError = true;
                        } else {
                            showFieldError(config.errorId, '');
                        }
                    });

                    const selectedShippingInput = document.querySelector('input[name="shipping_option"]:checked');
                    if (!selectedShippingInput) {
                        showFieldError('shipping-option-error', 'Empty Field');
                        if (!firstInvalidField) {
                            firstInvalidField = shippingOptionInputs[0] || null;
                        }
                        hasError = true;
                    } else {
                        showFieldError('shipping-option-error', '');
                    }

                    if (hasError) {
                        event.preventDefault();
                        if (firstInvalidField) {
                            if (firstInvalidField.isContentEditable) {
                                firstInvalidField.focus();
                            } else if (firstInvalidField.type === 'radio') {
                                firstInvalidField.closest('.shipping-option-label')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            } else {
                                firstInvalidField.focus();
                            }
                        }
                    }
                });
            }

            function syncCheckoutSummaryPosition() {
                if (!checkoutContainer || !checkoutSummarySection || !checkoutSummarySticky) {
                    return;
                }

                const desktopLayout = window.innerWidth >= 700;
                checkoutSummarySection.style.minHeight = '';
                checkoutSummarySticky.style.position = '';
                checkoutSummarySticky.style.top = '';
                checkoutSummarySticky.style.left = '';
                checkoutSummarySticky.style.width = '';
                checkoutSummarySticky.style.maxHeight = '';
                checkoutSummarySticky.style.overflowY = '';

                if (!desktopLayout) {
                    checkoutSummarySticky.style.position = 'static';
                    return;
                }

                const summaryHeight = checkoutSummarySticky.offsetHeight;
                const containerRect = checkoutContainer.getBoundingClientRect();
                const sectionRect = checkoutSummarySection.getBoundingClientRect();
                const containerTop = window.scrollY + containerRect.top;
                const sectionTop = window.scrollY + sectionRect.top;
                const containerBottom = containerTop + checkoutContainer.offsetHeight;
                const stickyTop = 110;
                const startStick = sectionTop - stickyTop;
                const endStick = containerBottom - summaryHeight - stickyTop;

                checkoutSummarySection.style.minHeight = summaryHeight + 'px';

                if (window.scrollY <= startStick) {
                    checkoutSummarySticky.style.position = 'static';
                    return;
                }

                if (window.scrollY >= endStick) {
                    checkoutSummarySticky.style.position = 'absolute';
                    checkoutSummarySticky.style.top = Math.max(0, checkoutContainer.offsetHeight - summaryHeight) + 'px';
                    checkoutSummarySticky.style.left = '0';
                    checkoutSummarySticky.style.width = '100%';
                    return;
                }

                checkoutSummarySticky.style.position = 'fixed';
                checkoutSummarySticky.style.top = stickyTop + 'px';
                checkoutSummarySticky.style.left = sectionRect.left + 'px';
                checkoutSummarySticky.style.width = sectionRect.width + 'px';
                checkoutSummarySticky.style.maxHeight = 'calc(100vh - 130px)';
                checkoutSummarySticky.style.overflowY = 'auto';
            }

            syncCheckoutSummaryPosition();
            window.addEventListener('scroll', syncCheckoutSummaryPosition, { passive: true });
            window.addEventListener('resize', syncCheckoutSummaryPosition);
        });
    </script>
</x-app-layout>
