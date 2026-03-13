<section class="space-y-6" x-data="{ confirmation: '' }">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>
    </header>

    <div class="profile-field">
        <x-input-label for="delete_confirmation_visible" :value="__('Confirmation')" />
        <div class="profile-input-wrap contact-field-wrap">
            <x-text-input
                id="delete_confirmation_visible"
                type="text"
                class="block w-full contact-field profile-textbox"
                placeholder="Type DELETE"
                x-model="confirmation"
                autocomplete="off"
                spellcheck="false"
            />
        </div>
    </div>

    <div class="flex justify-center">
        <x-danger-button
            class="profile-action-button"
            x-data=""
            x-bind:disabled="confirmation !== 'DELETE'"
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >{{ __('Delete Account') }}</x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')
            <input type="hidden" name="delete_confirmation" x-bind:value="confirmation">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <div class="profile-input-wrap contact-field-wrap w-3/4">
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full contact-field profile-textbox"
                        placeholder="{{ __('Password') }}"
                    />
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button class="profile-action-button" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3 profile-action-button" x-bind:disabled="confirmation !== 'DELETE'">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>

            <x-input-error :messages="$errors->userDeletion->get('delete_confirmation')" class="mt-2" />
        </form>
    </x-modal>
</section>
