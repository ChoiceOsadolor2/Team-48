<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit User') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update this account using the same admin controls as the users view.</p>
        </div>
    </x-slot>

    <style>
        .admin-user-edit-page,
        .admin-user-edit-page * {
            font-family: 'MiniPixel', sans-serif !important;
        }

        .admin-user-edit-page {
            color: #fff;
        }

        .admin-user-edit-page .edit-card,
        .admin-user-edit-page .edit-error-box {
            background: #1d1d1f !important;
            border-color: #444 !important;
        }

        .admin-user-edit-page .edit-title {
            font-size: 30px !important;
            font-weight: 400 !important;
            line-height: 1.1 !important;
            color: #fff !important;
        }

        .admin-user-edit-page .edit-copy,
        .admin-user-edit-page .edit-copy-sm,
        .admin-user-edit-page label,
        .admin-user-edit-page input,
        .admin-user-edit-page .edit-editable,
        .admin-user-edit-page textarea,
        .admin-user-edit-page select,
        .admin-user-edit-page li {
            font-size: 20px !important;
            line-height: 1.4 !important;
            font-weight: 400 !important;
        }

        .admin-user-edit-page .edit-copy,
        .admin-user-edit-page .edit-copy-sm {
            color: #888 !important;
        }

        .admin-user-edit-page .font-semibold,
        .admin-user-edit-page .font-bold,
        .admin-user-edit-page .font-extrabold,
        .admin-user-edit-page strong,
        .admin-user-edit-page b {
            font-weight: 400 !important;
        }

        .admin-user-edit-page label,
        .admin-user-edit-page input,
        .admin-user-edit-page .edit-editable,
        .admin-user-edit-page textarea,
        .admin-user-edit-page select,
        .admin-user-edit-page li {
            color: #fff !important;
        }

        .admin-user-edit-page .edit-input {
            min-height: 56px;
            width: 100%;
            border: 1px solid #444 !important;
            border-radius: 18px !important;
            background: #000 !important;
            color: #fff !important;
            box-shadow: none !important;
            position: relative;
            z-index: 1;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .admin-user-edit-page .edit-editable {
            display: flex;
            align-items: center;
            padding: 0 16px;
            min-height: 56px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: text;
        }

        .admin-user-edit-page select.edit-input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none !important;
            padding-right: 52px !important;
        }

        .admin-user-edit-page .edit-input::placeholder {
            color: #888 !important;
        }

        .admin-user-edit-page .edit-field-shell {
            position: relative;
            border-radius: 18px;
            overflow: visible;
        }

        .admin-user-edit-page .edit-field-shell::after {
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

        .admin-user-edit-page .edit-field-shell:hover::after,
        .admin-user-edit-page .edit-field-shell:focus-within::after {
            opacity: 1;
        }

        .admin-user-edit-page .edit-field-shell:hover .edit-input,
        .admin-user-edit-page .edit-field-shell:focus-within .edit-input {
            background: #1d1d1d !important;
            border-color: transparent !important;
            outline: none !important;
        }

        .admin-user-edit-page .edit-select-wrap {
            position: relative;
        }

        .admin-user-edit-page .edit-select-wrap::after {
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

        .admin-user-edit-page .edit-action-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 56px;
            min-width: 140px;
            padding: 0 24px;
            border: 1px solid #444 !important;
            border-radius: 18px !important;
            background: #000 !important;
            color: #fff !important;
            font-size: 20px !important;
            overflow: visible;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
            text-decoration: none !important;
        }

        .admin-user-edit-page .edit-action-btn::after {
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

        .admin-user-edit-page .edit-action-btn:hover,
        .admin-user-edit-page .edit-action-btn:focus-visible {
            background: #1d1d1d !important;
            border-color: transparent !important;
            outline: none;
            transform: translateY(-1px);
        }

        .admin-user-edit-page .edit-action-btn:hover::after,
        .admin-user-edit-page .edit-action-btn:focus-visible::after {
            opacity: 1;
        }

        .admin-user-edit-page .edit-error-box {
            color: #ff9cae !important;
        }

        .admin-user-edit-page .edit-error-box ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>

    <div class="admin-user-edit-page py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="edit-card rounded-2xl border p-6 max-w-5xl mx-auto">
                <div class="mb-6">
                    <h3 class="edit-title">Edit user details</h3>
                    <p class="edit-copy">Update the selected account information and role.</p>
                </div>

                @if ($errors->any())
                    <div class="edit-error-box mb-6 rounded-2xl border px-5 py-4">
                        <ul class="space-y-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6 max-w-4xl" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <input type="text" name="fake_username" autocomplete="username" tabindex="-1" class="hidden" aria-hidden="true">
                        <input type="password" name="fake_password" autocomplete="new-password" tabindex="-1" class="hidden" aria-hidden="true">

                    <div>
                        <label for="name" class="mb-2 block">Name</label>
                        <div class="edit-field-shell">
                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name', $user->name) }}"
                                class="edit-input px-4 py-3"
                                autocomplete="off"
                                autocorrect="off"
                                autocapitalize="off"
                                spellcheck="false"
                                required
                            >
                        </div>
                    </div>

                    <div>
                        <label for="contact_value" class="mb-2 block">Email Address</label>
                        <div class="edit-field-shell">
                            <input type="hidden" name="email" id="email" value="{{ old('email', $user->email) }}">
                            <div
                                id="contact_value"
                                class="edit-input edit-editable"
                                contenteditable="true"
                                data-email-display
                                role="textbox"
                                aria-multiline="false"
                                spellcheck="false"
                            >{{ old('email', $user->email) }}</div>
                        </div>
                    </div>

                    <div>
                        <label for="role" class="mb-2 block">Role</label>
                        <div class="edit-field-shell">
                            <div class="edit-select-wrap">
                                <select name="role" id="role" class="edit-input px-4 py-3" required>
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <button type="submit" class="edit-action-btn">
                            Save Changes
                        </button>

                        <a href="{{ route('admin.users.index') }}" class="edit-action-btn">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emailDisplay = document.querySelector('[data-email-display]');
            const emailHidden = document.getElementById('email');
            const form = emailHidden?.closest('form');

            if (!emailDisplay || !emailHidden || !form) {
                return;
            }

            const syncEmailValue = () => {
                emailHidden.value = emailDisplay.textContent.trim();
            };

            syncEmailValue();

            emailDisplay.addEventListener('input', () => {
                syncEmailValue();
            });

            emailDisplay.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            });

            form.addEventListener('submit', () => {
                syncEmailValue();
            });
        });
    </script>
</x-app-layout>
