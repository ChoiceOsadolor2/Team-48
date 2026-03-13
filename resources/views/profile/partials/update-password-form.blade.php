<section>
    @php
        $formatPasswordErrors = fn ($messages) => collect((array) $messages)
            ->map(fn ($message) => str_contains(strtolower($message), 'required') ? 'Empty Field' : $message)
            ->all();
    @endphp

    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6" autocomplete="off">
        @csrf
        @method('put')

        <div class="profile-field">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="profile-input-wrap contact-field-wrap">
                <x-text-input
                    id="update_password_current_password"
                    name="current_password"
                    type="text"
                    class="block w-full contact-field profile-textbox profile-masked-password"
                    autocomplete="one-time-code"
                    readonly
                    onfocus="this.removeAttribute('readonly');"
                    data-lpignore="true"
                    data-1p-ignore
                    data-form-type="other"
                    autocapitalize="off"
                    spellcheck="false"
                />
            </div>
            <div style="margin-top: 6px;">
                <x-input-error
                    :messages="$formatPasswordErrors($errors->updatePassword->get('current_password'))"
                    class="profile-inline-error"
                />
            </div>
        </div>

        <div class="profile-field">
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="profile-input-wrap contact-field-wrap">
                <x-text-input
                    id="update_password_password"
                    name="password"
                    type="text"
                    class="block w-full contact-field profile-textbox profile-masked-password"
                    autocomplete="one-time-code"
                    readonly
                    onfocus="this.removeAttribute('readonly');"
                    data-lpignore="true"
                    data-1p-ignore
                    data-form-type="other"
                    autocapitalize="off"
                    spellcheck="false"
                />
            </div>
            <div style="margin-top: 6px;">
                <x-input-error
                    :messages="$formatPasswordErrors($errors->updatePassword->get('password'))"
                    class="profile-inline-error"
                />
            </div>
        </div>

        <div class="profile-field">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="profile-input-wrap contact-field-wrap">
                <x-text-input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="text"
                    class="block w-full contact-field profile-textbox profile-masked-password"
                    autocomplete="one-time-code"
                    readonly
                    onfocus="this.removeAttribute('readonly');"
                    data-lpignore="true"
                    data-1p-ignore
                    data-form-type="other"
                    autocapitalize="off"
                    spellcheck="false"
                />
            </div>
            <div style="margin-top: 6px;">
                <x-input-error
                    :messages="$formatPasswordErrors($errors->updatePassword->get('password_confirmation'))"
                    class="profile-inline-error"
                />
            </div>
        </div>

        <div class="flex items-center justify-center gap-4">
            <x-primary-button class="profile-action-button">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
