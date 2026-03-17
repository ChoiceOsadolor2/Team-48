<section class="space-y-6" x-data="{ confirmation: '' }">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
        @csrf
        @method('delete')

        <div class="profile-field">
            <x-input-label for="delete_confirmation_visible" :value="__('Confirmation')" />
            <div class="profile-input-wrap contact-field-wrap">
                <x-text-input
                    id="delete_confirmation_visible"
                    name="delete_confirmation"
                    type="text"
                    class="block w-full contact-field profile-textbox"
                    placeholder="Type DELETE"
                    x-model="confirmation"
                    autocomplete="off"
                    spellcheck="false"
                />
            </div>

            <x-input-error :messages="$errors->userDeletion->get('delete_confirmation')" class="mt-2" />
        </div>

        <div class="flex justify-center">
            <x-danger-button
                class="profile-action-button"
                x-bind:disabled="confirmation !== 'DELETE'"
            >{{ __('Delete Account') }}</x-danger-button>
        </div>
    </form>
</section>
