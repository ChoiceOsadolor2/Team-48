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
        <input
            type="text"
            name="platform"
            class="min-h-[46px] w-full rounded-xl border border-white/10 bg-[#050505] px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-cyan-400"
            placeholder="PlayStation 5, PC, Xbox"
            value="{{ old('platform', optional($product)->platform) }}"
        >
        @error('platform') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
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
