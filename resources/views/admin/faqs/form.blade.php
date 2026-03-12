<div class="space-y-6">
    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">Keyword</label>
        <input type="text"
               name="keyword"
               value="{{ old('keyword', optional($faq)->keyword) }}"
               class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 focus:border-cyan-500 focus:outline-none">
        @error('keyword')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">Answer</label>
        <textarea name="answer"
                  rows="7"
                  class="w-full rounded-lg border border-gray-300 px-4 py-3 text-gray-900 focus:border-cyan-500 focus:outline-none">{{ old('answer', optional($faq)->answer) }}</textarea>
        @error('answer')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
