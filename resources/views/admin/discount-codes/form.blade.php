<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Code</label>
            <input
                type="text"
                name="code"
                value="{{ old('code', $discountCode->code ?? '') }}"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm uppercase"
                placeholder="SUMMER20"
                required
            />
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Discount type</label>
            <select name="type" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm" required>
                @foreach ($types as $value => $label)
                    <option value="{{ $value }}" @selected(old('type', $discountCode->type ?? 'percentage') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <p class="mt-2 text-xs text-gray-500">Choose whether the code gives something like 10% off or £10 off.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Value</label>
            <input
                type="number"
                step="0.01"
                min="0.01"
                name="value"
                value="{{ old('value', $discountCode->value ?? '') }}"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
                placeholder="10"
                required
            />
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Usage limit</label>
            <input
                type="number"
                min="1"
                name="usage_limit"
                value="{{ old('usage_limit', $discountCode->usage_limit ?? '') }}"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
                placeholder="Leave blank for unlimited"
            />
        </div>

        <div class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4">
            <input
                id="is_active"
                type="checkbox"
                name="is_active"
                value="1"
                class="h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500"
                @checked(old('is_active', $discountCode->is_active ?? true))
            />
            <label for="is_active" class="text-sm font-semibold text-gray-700">Code is active</label>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Starts at</label>
            <input
                type="datetime-local"
                name="starts_at"
                value="{{ old('starts_at', isset($discountCode?->starts_at) ? $discountCode->starts_at->format('Y-m-d\\TH:i') : '') }}"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
            />
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">Ends at</label>
            <input
                type="datetime-local"
                name="ends_at"
                value="{{ old('ends_at', isset($discountCode?->ends_at) ? $discountCode->ends_at->format('Y-m-d\\TH:i') : '') }}"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
            />
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold text-gray-700">Internal notes</label>
        <textarea
            name="notes"
            rows="4"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
            placeholder="Optional admin notes about this code..."
        >{{ old('notes', $discountCode->notes ?? '') }}</textarea>
    </div>
</div>
