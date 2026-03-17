@php
    $selectedPlatforms = old('platform');
    if (!is_array($selectedPlatforms)) {
        $selectedPlatforms = !empty(optional($product)->platform)
            ? array_map('trim', explode(',', optional($product)->platform))
            : [];
    }

    $existingPlatformStocks = old('platform_stock');
    if (!is_array($existingPlatformStocks)) {
        $existingPlatformStocks = optional($product)->relationLoaded('platformStocks')
            ? optional($product)->platformStocks->mapWithKeys(fn ($stock) => [$stock->platform => (int) $stock->stock])->all()
            : [];
    }
@endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-200">Category</label>
        <div class="product-edit-field-shell">
            <div class="product-edit-select-wrap">
                <select name="category_id" class="min-h-[46px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition focus:border-cyan-400">
                    <option value="" style="color:#fff; background-color:#050505;">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" style="color:#fff; background-color:#050505;"
                            @selected(old('category_id', optional($product)->category_id) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        @error('category_id') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-200">Platform</label>
        <div class="platform-multiselect relative" data-platform-multiselect>
            <button
                type="button"
                class="platform-multiselect-trigger flex min-h-[46px] w-full items-center justify-between rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-left text-sm text-white outline-none transition hover:border-cyan-400 focus:border-cyan-400"
            >
                <span
                    class="block truncate {{ count($selectedPlatforms) ? 'text-white' : 'text-gray-500' }}"
                    data-platform-label
                    data-placeholder="Select platform(s)"
                >{{ count($selectedPlatforms) ? implode(', ', $selectedPlatforms) : 'Select platform(s)' }}</span>
                <svg class="h-4 w-4 flex-shrink-0 text-white/70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>

            <div
                class="platform-multiselect-panel absolute left-0 right-0 z-20 mt-2 hidden rounded-xl border border-white/10 bg-[#050505] p-2 shadow-2xl"
                data-platform-panel
            >
                @foreach ($platformOptions as $platformOption)
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm text-white transition hover:bg-white/5">
                        <input
                            type="checkbox"
                            name="platform[]"
                            value="{{ $platformOption }}"
                            class="h-4 w-4 rounded border border-white/20 bg-black text-cyan-500 focus:ring-cyan-400"
                            @checked(in_array($platformOption, $selectedPlatforms, true))
                            data-platform-checkbox
                        >
                        <span>{{ $platformOption }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <p class="product-edit-help mt-2 text-xs text-gray-400">You can choose multiple platforms.</p>
        @error('platform') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
        @error('platform.*') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-gray-200">Name</label>
        <div class="product-edit-field-shell">
            <input
                type="text"
                name="name"
                class="min-h-[46px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-cyan-400"
                placeholder="Enter the product name"
                value="{{ old('name', optional($product)->name) }}"
            >
        </div>
        @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-gray-200">Description</label>
        <div class="product-edit-field-shell field-shell-textarea">
            <textarea
                name="description"
                rows="6"
                class="min-h-[140px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-cyan-400"
                placeholder="Write a short, clear description of the product"
            >{{ old('description', optional($product)->description) }}</textarea>
        </div>
        @error('description') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-200">Price (GBP)</label>
        <div class="product-edit-field-shell">
            <input
                type="number"
                step="0.01"
                name="price"
                class="min-h-[46px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-cyan-400"
                placeholder="0.00"
                value="{{ old('price', optional($product)->price) }}"
            >
        </div>
        @error('price') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-200">Total stock</label>
        <div class="product-edit-field-shell readonly-shell">
            <input
                type="number"
                name="stock"
                id="product_total_stock"
                class="product-edit-readonly min-h-[46px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-cyan-400"
                placeholder="0"
                value="{{ old('stock', optional($product)->stock ?? 0) }}"
            >
        </div>
        <p class="product-edit-help mt-2 text-xs text-gray-400" id="product_total_stock_help">Use this for products without platform-specific stock. If platform stock is set below, this total updates automatically.</p>
        @error('stock') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2 rounded-2xl border border-white/10 bg-[#050505] p-4" data-platform-stock-card data-existing-platform-stock='@json($existingPlatformStocks)'>
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold text-white">Stock by platform</p>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2" data-platform-stock-fields></div>
        <p class="product-edit-empty mt-3 text-xs text-gray-500" data-platform-stock-empty>No platform-specific stock fields yet. Select one or more platforms above to set quantities.</p>

        @error('platform_stock') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
        @error('platform_stock.*') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-gray-200">Product image</label>
        <div class="product-edit-image-shell rounded-2xl border border-dashed border-white/15 bg-[#050505] p-4">
            <div class="flex flex-wrap items-center gap-4">
                <label for="product_image_input" class="product-edit-action cursor-pointer">
                    Choose File
                </label>
                <span class="product-edit-current-copy" data-product-image-name>No file chosen</span>
                <input
                    id="product_image_input"
                    type="file"
                    name="image"
                    accept="image/*"
                    class="product-edit-file-input"
                >
            </div>

            @if(!empty($product?->image_url))
                <div class="product-edit-current-image mt-4 flex items-center gap-4 rounded-xl border border-white/10 bg-black/30 p-3">
                    <img
                        src="{{ asset('storage/' . $product->image_url) }}"
                        alt="Current product image"
                        class="h-20 w-20 rounded-lg object-cover"
                    >
                    <div>
                        <p class="text-sm font-semibold text-white">Current image</p>
                        <p class="product-edit-current-copy text-xs text-gray-400">Uploading a new file will replace the existing one.</p>
                    </div>
                </div>
            @endif
        </div>

        @error('image')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-platform-multiselect]').forEach(function (dropdown) {
        const trigger = dropdown.querySelector('.platform-multiselect-trigger');
        const panel = dropdown.querySelector('[data-platform-panel]');
        const label = dropdown.querySelector('[data-platform-label]');
        const checkboxes = Array.from(dropdown.querySelectorAll('[data-platform-checkbox]'));
        const placeholder = label?.dataset.placeholder || 'Select platform(s)';
        const stockCard = document.querySelector('[data-platform-stock-card]');
        const stockFields = stockCard?.querySelector('[data-platform-stock-fields]');
        const stockEmpty = stockCard?.querySelector('[data-platform-stock-empty]');
        const totalStockInput = document.getElementById('product_total_stock');
        const totalStockHelp = document.getElementById('product_total_stock_help');
        const existingPlatformStock = stockCard ? JSON.parse(stockCard.dataset.existingPlatformStock || '{}') : {};
        const currentPlatformValues = Object.assign({}, existingPlatformStock);

        if (!trigger || !panel || !label) {
            return;
        }

        const selectedPlatforms = function () {
            return checkboxes
                .filter(function (checkbox) { return checkbox.checked; })
                .map(function (checkbox) { return checkbox.value; });
        };

        const updateLabel = function () {
            const selected = selectedPlatforms();

            if (selected.length) {
                label.textContent = selected.join(', ');
                label.classList.remove('text-gray-500');
                label.classList.add('text-white');
            } else {
                label.textContent = placeholder;
                label.classList.remove('text-white');
                label.classList.add('text-gray-500');
            }
        };

        const updateTotalStockFromPlatformFields = function () {
            if (!totalStockInput || !stockFields) return;

            const inputs = Array.from(stockFields.querySelectorAll('input[type="number"]'));
            const hasPlatformFields = inputs.length > 0;
            const total = inputs.reduce(function (sum, input) {
                return sum + Math.max(0, parseInt(input.value || '0', 10) || 0);
            }, 0);

            totalStockInput.readOnly = hasPlatformFields;
            totalStockInput.classList.toggle('opacity-70', hasPlatformFields);
            totalStockInput.classList.toggle('cursor-not-allowed', hasPlatformFields);

            if (hasPlatformFields) {
                totalStockInput.value = String(total);
                if (totalStockHelp) {
                    totalStockHelp.textContent = 'Total stock is currently being calculated from the platform quantities below.';
                }
            } else if (totalStockHelp) {
                totalStockHelp.textContent = 'Use this for products without platform-specific stock. If platform stock is set below, this total updates automatically.';
            }
        };

        const renderPlatformStockFields = function () {
            if (!stockFields || !stockEmpty) return;

            const selected = selectedPlatforms();
            stockFields.innerHTML = '';

            if (!selected.length) {
                stockEmpty.style.display = 'block';
                updateTotalStockFromPlatformFields();
                return;
            }

            stockEmpty.style.display = 'none';

            selected.forEach(function (platform) {
                const wrapper = document.createElement('div');
                wrapper.className = 'rounded-xl border border-white/10 bg-black/30 p-4';

                const labelEl = document.createElement('label');
                labelEl.className = 'mb-2 block text-sm font-semibold text-gray-200';
                labelEl.textContent = platform + ' stock';

                const input = document.createElement('input');
                input.type = 'number';
                input.name = 'platform_stock[' + platform + ']';
                input.min = '0';
                input.value = String(currentPlatformValues[platform] ?? 0);
                input.className = 'min-h-[46px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-cyan-400';
                input.placeholder = '0';
                input.addEventListener('input', function () {
                    currentPlatformValues[platform] = input.value;
                    updateTotalStockFromPlatformFields();
                });

                wrapper.appendChild(labelEl);
                wrapper.appendChild(input);
                stockFields.appendChild(wrapper);
            });

            updateTotalStockFromPlatformFields();
        };

        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            const shouldOpen = panel.classList.contains('hidden');

            document.querySelectorAll('[data-platform-panel]').forEach(function (otherPanel) {
                otherPanel.classList.add('hidden');
            });

            if (shouldOpen) {
                panel.classList.remove('hidden');
            }
        });

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                Array.from(stockFields?.querySelectorAll('input[type="number"]') || []).forEach(function (input) {
                    const match = input.name.match(/^platform_stock\[(.*)\]$/);
                    if (match) {
                        currentPlatformValues[match[1]] = input.value;
                    }
                });
                updateLabel();
                renderPlatformStockFields();
            });
        });

        document.addEventListener('click', function (event) {
            if (!dropdown.contains(event.target)) {
                panel.classList.add('hidden');
            }
        });

        updateLabel();
        renderPlatformStockFields();
    });

    const productImageInput = document.getElementById('product_image_input');
    const productImageName = document.querySelector('[data-product-image-name]');

    if (productImageInput && productImageName) {
        productImageInput.addEventListener('change', function () {
            productImageName.textContent = this.files && this.files.length
                ? this.files[0].name
                : 'No file chosen';
        });
    }
});
</script>
