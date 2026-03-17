<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create FAQ</h2>
    </x-slot>

    <style>
        .admin-faq-create-page .faq-create-shell,
        .admin-faq-create-page .faq-create-form {
            background: #1d1d1d !important;
            border-color: #444 !important;
        }

        .admin-faq-create-page .faq-create-title,
        .admin-faq-create-page .faq-create-copy,
        .admin-faq-create-page label,
        .admin-faq-create-page .faq-input,
        .admin-faq-create-page .faq-help {
            color: #f9fafb !important;
        }

        .admin-faq-create-page .faq-help {
            color: #9ca3af !important;
        }

        .admin-faq-create-page .faq-input {
            background: #262626 !important;
            border-color: #444 !important;
        }

        .admin-faq-create-page .faq-input::placeholder {
            color: #9ca3af !important;
        }
    </style>

    <div class="admin-faq-create-page py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="faq-create-shell rounded-3xl border p-8 shadow-sm">
                <div class="mb-6">
                    <h1 class="faq-create-title text-2xl font-semibold">Create FAQ</h1>
                    <p class="faq-create-copy mt-2 text-base">Add a new chatbot answer with clear keywords, category, and reply priority.</p>
                </div>

                <form method="POST" action="{{ route('admin.faqs.store') }}" class="faq-create-form rounded-2xl border p-6">
                    @csrf
                    @include('admin.faqs.form', ['faq' => null])

                    <div class="mt-6 flex items-center gap-4">
                        <button type="submit" class="admin-btn admin-btn--primary">
                            Save FAQ
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
