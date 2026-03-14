<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-200">Category</label>
        <select name="category_id" class="min-h-[46px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition focus:border-cyan-400">
            <option value="" style="color:#fff; background-color:#050505;">Select a category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" style="color:#fff; background-color:#050505;"
                    @selected(old('category_id', optional($product)->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-200">Platform</label>
        @php
            $selectedPlatforms = old('platform');
            if (!is_array($selectedPlatforms)) {
                $selectedPlatforms = !empty(optional($product)->platform)
                    ? array_map('trim', explode(',', optional($product)->platform))
                    : [];
            }
        @endphp
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
        <p class="mt-2 text-xs text-gray-400">You can choose multiple platforms.</p>
        @error('platform') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
        @error('platform.*') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-gray-200">Name</label>
        <input
            type="text"
            name="name"
            class="min-h-[46px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-cyan-400"
            placeholder="Enter the product name"
            value="{{ old('name', optional($product)->name) }}"
        >
        @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-gray-200">Description</label>
        <textarea
            name="description"
            rows="6"
            class="min-h-[140px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-cyan-400"
            placeholder="Write a short, clear description of the product"
        >{{ old('description', optional($product)->description) }}</textarea>
        @error('description') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-200">Price (GBP)</label>
        <input
            type="number"
            step="0.01"
            name="price"
            class="min-h-[46px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-cyan-400"
            placeholder="0.00"
            value="{{ old('price', optional($product)->price) }}"
        >
        @error('price') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-200">Stock</label>
        <input
            type="number"
            name="stock"
            class="min-h-[46px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-cyan-400"
            placeholder="0"
            value="{{ old('stock', optional($product)->stock ?? 0) }}"
        >
        @error('stock') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-semibold text-gray-200">Product image</label>
        <div class="rounded-2xl border border-dashed border-white/15 bg-[#050505] p-4">
            <input
                type="file"
                name="image"
                accept="image/*"
                class="block w-full text-sm text-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-cyan-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-cyan-500"
            >

            @if(!empty($product?->image_url))
                <div class="mt-4 flex items-center gap-4 rounded-xl border border-white/10 bg-black/30 p-3">
                    <img
                        src="{{ asset('storage/' . $product->image_url) }}"
                        alt="Current product image"
                        class="h-20 w-20 rounded-lg object-cover"
                    >
                    <div>
                        <p class="text-sm font-semibold text-white">Current image</p>
                        <p class="text-xs text-gray-400">Uploading a new file will replace the existing one.</p>
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

        if (!trigger || !panel || !label) {
            return;
        }

        const updateLabel = function () {
            const selected = checkboxes
                .filter(function (checkbox) { return checkbox.checked; })
                .map(function (checkbox) { return checkbox.value; });

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
            checkbox.addEventListener('change', updateLabel);
        });

        document.addEventListener('click', function (event) {
            if (!dropdown.contains(event.target)) {
                panel.classList.add('hidden');
            }
        });

        updateLabel();
    });
});
</script>
