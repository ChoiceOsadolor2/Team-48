<div class="space-y-6">
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">Keyword</label>
        <input type="text"
               name="keyword"
               value="{{ old('keyword', optional($faq)->keyword) }}"
               class="faq-input w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 focus:border-cyan-500 focus:outline-none">
        @error('keyword')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">Category</label>
        <select
            name="category"
            class="faq-input w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 focus:border-cyan-500 focus:outline-none">
            @foreach(($categories ?? \App\Models\Faq::CATEGORIES) as $value => $label)
                <option value="{{ $value }}" {{ old('category', optional($faq)->category ?? 'general') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('category')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">Reply Priority</label>
        <input type="number"
               name="priority"
               min="0"
               max="10"
               value="{{ old('priority', optional($faq)->priority ?? 0) }}"
               class="faq-input w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 focus:border-cyan-500 focus:outline-none">
        <p class="faq-help mt-2 text-sm text-gray-500">Higher priority replies are preferred when multiple FAQs are relevant.</p>
        @error('priority')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">Answer</label>
        <textarea name="answer"
                  rows="7"
                  class="faq-input w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 focus:border-cyan-500 focus:outline-none">{{ old('answer', optional($faq)->answer) }}</textarea>
        @error('answer')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
