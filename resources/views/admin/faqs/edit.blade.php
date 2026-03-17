<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit FAQ</h2>
    </x-slot>

    <style>
        .admin-faq-edit-page .faq-edit-shell,
        .admin-faq-edit-page .faq-edit-form {
            background: #1d1d1d !important;
            border-color: #444 !important;
        }

        .admin-faq-edit-page .faq-edit-title,
        .admin-faq-edit-page .faq-edit-copy,
        .admin-faq-edit-page label,
        .admin-faq-edit-page .faq-input,
        .admin-faq-edit-page .faq-help {
            color: #f9fafb !important;
        }

        .admin-faq-edit-page .faq-help {
            color: #9ca3af !important;
        }

        .admin-faq-edit-page .faq-input {
            background: #262626 !important;
            border-color: #444 !important;
        }

        .admin-faq-edit-page .faq-input::placeholder {
            color: #9ca3af !important;
        }
    </style>

    <div class="admin-faq-edit-page py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="faq-edit-shell rounded-3xl border p-8 shadow-sm">
                <div class="mb-6">
                    <h1 class="faq-edit-title text-2xl font-semibold">Edit FAQ</h1>
                    <p class="faq-edit-copy mt-2 text-base">Update the chatbot response, keyword matching, category, and reply priority.</p>
                </div>

                <form method="POST" action="{{ route('admin.faqs.update', $faq) }}" class="faq-edit-form rounded-2xl border p-6">
                    @csrf
                    @method('PUT')
                    @include('admin.faqs.form', ['faq' => $faq])

                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit" class="admin-btn admin-btn--primary">
                            Update FAQ
                        </button>
                        <a href="{{ route('admin.faqs.index') }}" class="admin-btn admin-btn--secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
