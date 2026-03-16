<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <style>
        .admin-product-edit-page,
        .admin-product-edit-page * {
            font-family: 'MiniPixel', sans-serif !important;
        }

        .admin-product-edit-page {
            color: #fff;
        }

        .admin-product-edit-page .product-edit-card,
        .admin-product-edit-page .product-edit-error {
            background: #1d1d1f !important;
            border-color: #444 !important;
        }

        .admin-product-edit-page .product-edit-kicker,
        .admin-product-edit-page .product-edit-copy,
        .admin-product-edit-page .product-edit-help,
        .admin-product-edit-page .product-edit-note,
        .admin-product-edit-page .product-edit-empty,
        .admin-product-edit-page .product-edit-current-copy,
        .admin-product-edit-page .product-edit-error li,
        .admin-product-edit-page .product-edit-panel p,
        .admin-product-edit-page label,
        .admin-product-edit-page input,
        .admin-product-edit-page textarea,
        .admin-product-edit-page select,
        .admin-product-edit-page button,
        .admin-product-edit-page a,
        .admin-product-edit-page span {
            font-size: 20px !important;
            line-height: 1.4 !important;
            font-weight: 400 !important;
        }

        .admin-product-edit-page .product-edit-title {
            font-size: 30px !important;
            line-height: 1.1 !important;
            font-weight: 400 !important;
            color: #fff !important;
        }

        .admin-product-edit-page .product-edit-kicker {
            color: #67e8f9 !important;
        }

        .admin-product-edit-page .product-edit-copy,
        .admin-product-edit-page .product-edit-help,
        .admin-product-edit-page .product-edit-note,
        .admin-product-edit-page .product-edit-empty,
        .admin-product-edit-page .product-edit-current-copy,
        .admin-product-edit-page .product-edit-panel p {
            color: #888 !important;
        }

        .admin-product-edit-page .font-semibold,
        .admin-product-edit-page .font-bold,
        .admin-product-edit-page .font-extrabold,
        .admin-product-edit-page strong,
        .admin-product-edit-page b {
            font-weight: 400 !important;
        }

        .admin-product-edit-page label,
        .admin-product-edit-page input,
        .admin-product-edit-page textarea,
        .admin-product-edit-page select {
            color: #fff !important;
        }

        .admin-product-edit-page input[type="text"],
        .admin-product-edit-page input[type="number"],
        .admin-product-edit-page textarea,
        .admin-product-edit-page select,
        .admin-product-edit-page .platform-multiselect-trigger {
            min-height: 56px !important;
            width: 100%;
            border: 1px solid #444 !important;
            border-radius: 18px !important;
            background: #000 !important;
            color: #fff !important;
            box-shadow: none !important;
            outline: none !important;
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        .admin-product-edit-page input[type="number"]::-webkit-outer-spin-button,
        .admin-product-edit-page input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .admin-product-edit-page input[type="number"] {
            -moz-appearance: textfield;
        }

        .admin-product-edit-page textarea {
            min-height: 180px !important;
            resize: none;
            display: block;
        }

        .admin-product-edit-page select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none !important;
            padding-right: 52px !important;
        }

        .admin-product-edit-page input::placeholder,
        .admin-product-edit-page textarea::placeholder {
            color: #888 !important;
        }

        .admin-product-edit-page .platform-multiselect,
        .admin-product-edit-page .product-edit-field-shell {
            position: relative;
            border-radius: 18px;
            overflow: visible;
        }

        .admin-product-edit-page .platform-multiselect::after,
        .admin-product-edit-page .product-edit-field-shell::after,
        .admin-product-edit-page .product-edit-action::after {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            border: 1px solid transparent;
            opacity: 0;
            animation: veltrixGlow 2s infinite alternate;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .admin-product-edit-page .product-edit-field-shell.field-shell-textarea::after {
            inset: 0;
        }

        .admin-product-edit-page .platform-multiselect:hover::after,
        .admin-product-edit-page .platform-multiselect:focus-within::after,
        .admin-product-edit-page .product-edit-field-shell:hover::after,
        .admin-product-edit-page .product-edit-field-shell:focus-within::after,
        .admin-product-edit-page .product-edit-action:hover::after,
        .admin-product-edit-page .product-edit-action:focus-visible::after {
            opacity: 1;
        }

        .admin-product-edit-page .platform-multiselect:hover .platform-multiselect-trigger,
        .admin-product-edit-page .platform-multiselect:focus-within .platform-multiselect-trigger,
        .admin-product-edit-page .product-edit-field-shell:hover input,
        .admin-product-edit-page .product-edit-field-shell:hover textarea,
        .admin-product-edit-page .product-edit-field-shell:hover select,
        .admin-product-edit-page .product-edit-field-shell:focus-within input,
        .admin-product-edit-page .product-edit-field-shell:focus-within textarea,
        .admin-product-edit-page .product-edit-field-shell:focus-within select,
        .admin-product-edit-page .product-edit-action:hover,
        .admin-product-edit-page .product-edit-action:focus-visible {
            background: #1d1d1d !important;
            border-color: transparent !important;
            transform: translateY(-1px);
        }

        .admin-product-edit-page .product-edit-field-shell .product-edit-readonly,
        .admin-product-edit-page .product-edit-field-shell:hover .product-edit-readonly,
        .admin-product-edit-page .product-edit-field-shell:focus-within .product-edit-readonly {
            background: #1d1d1f !important;
            color: #fff !important;
            border-color: #444 !important;
            transform: none !important;
        }

        .admin-product-edit-page .product-edit-field-shell.readonly-shell::after,
        .admin-product-edit-page .product-edit-field-shell.readonly-shell:hover::after,
        .admin-product-edit-page .product-edit-field-shell.readonly-shell:focus-within::after {
            opacity: 0 !important;
        }

        .admin-product-edit-page .platform-multiselect-panel,
        .admin-product-edit-page [data-platform-stock-card],
        .admin-product-edit-page [data-platform-stock-fields] > div,
        .admin-product-edit-page .product-edit-image-shell,
        .admin-product-edit-page .product-edit-current-image {
            background: #1d1d1f !important;
            border-color: #444 !important;
        }

        .admin-product-edit-page .product-edit-select-wrap {
            position: relative;
        }

        .admin-product-edit-page .product-edit-select-wrap::after {
            content: '';
            position: absolute;
            right: 20px;
            top: 50%;
            width: 10px;
            height: 10px;
            border-right: 2px solid rgba(255, 255, 255, 0.7);
            border-bottom: 2px solid rgba(255, 255, 255, 0.7);
            transform: translateY(-65%) rotate(45deg);
            pointer-events: none;
            z-index: 2;
        }

        .admin-product-edit-page .platform-multiselect-panel label:hover {
            background: #2a2a2d !important;
        }

        .admin-product-edit-page .product-edit-file-input {
            display: none !important;
        }

        .admin-product-edit-page .product-edit-action {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 56px;
            min-width: 180px;
            padding: 0 24px;
            border: 1px solid #444 !important;
            border-radius: 18px !important;
            background: #000 !important;
            color: #fff !important;
            text-decoration: none !important;
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
            overflow: visible;
        }

        .admin-product-edit-page .product-edit-error {
            color: #ff9cae !important;
        }

        .admin-product-edit-page .product-edit-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .admin-product-edit-page .product-edit-card * {
            font-size: 20px !important;
            line-height: 1.4 !important;
            font-weight: 400 !important;
        }

        .admin-product-edit-page .product-edit-title {
            font-size: 30px !important;
            line-height: 1.1 !important;
        }
    </style>

    <div class="admin-product-edit-page py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="product-edit-card overflow-hidden rounded-[28px] border shadow-2xl">
                <div class="border-b border-white/10 px-8 py-6 text-white">
                    <h1 class="product-edit-title mt-3">Edit product</h1>
                    <p class="product-edit-copy mt-2">Update the catalogue entry, stock count, and product image without leaving the admin panel.</p>
                </div>

                <div class="p-8 text-white">
                    @if ($errors->any())
                        <div class="product-edit-error mb-6 rounded-2xl border px-5 py-4">
                            <p>Please fix the following before saving:</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="admin-product-edit-form" method="POST"
                        action="{{ route('admin.products.update', $product) }}"
                        enctype="multipart/form-data"
                        class="space-y-8">

                        @csrf
                        @method('PUT')

                        @include('admin.products.form', ['product' => $product])

                        <div class="flex items-center justify-between gap-4 border-t border-white/10 pt-6">
                            <a href="{{ route('admin.products.index') }}" class="product-edit-action">
                                Back to products
                            </a>
                            <button type="submit" form="admin-product-edit-form"
                                class="product-edit-action">
                                Update product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
