<div>
    <label class="block mb-1">Category</label>
    <select name="category_id" class="w-full border p-2">
        <option value="">-- Select Category --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                @selected(old('category_id', optional($product)->category_id) == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block mb-1">Name</label>
    <input type="text" name="name" class="w-full border p-2"
           value="{{ old('name', optional($product)->name) }}">
    @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block mb-1">Description</label>
    <textarea name="description" class="w-full border p-2" rows="4">{{ old('description', optional($product)->description) }}</textarea>
    @error('description') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block mb-1">Price (£)</label>
    <input type="number" step="0.01" name="price" class="w-full border p-2"
           value="{{ old('price', optional($product)->price) }}">
    @error('price') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block mb-1">Stock</label>
    <input type="number" name="stock" class="w-full border p-2"
           value="{{ old('stock', optional($product)->stock ?? 0) }}">
    @error('stock') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block mb-1">Platform</label>
    <input type="text" name="platform" class="w-full border p-2"
           value="{{ old('platform', optional($product)->platform) }}">
    @error('platform') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block mb-1">Product Image</label>
    <input type="file" name="image" accept="image/*" class="w-full border p-2">

    @if(!empty($product?->image))
        <img src="{{ asset('storage/' . $product->image) }}"
             class="mt-2 h-24 object-cover rounded">
    @endif

    @error('image')
        <p class="text-red-600 text-sm">{{ $message }}</p>
    @enderror
</div>

