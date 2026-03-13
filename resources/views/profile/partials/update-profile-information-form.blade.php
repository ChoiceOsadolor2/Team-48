<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Information') }}
        </h2>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Name --}}
        <div class="profile-field">
            <x-input-label for="name" :value="__('Name')" />
            <div class="profile-input-wrap contact-field-wrap">
                <x-text-input id="name" name="name" type="text" class="block w-full contact-field profile-textbox"
                    :value="old('name', $user->name)" required autocomplete="off" readonly
                    onfocus="this.removeAttribute('readonly');" autocapitalize="words" spellcheck="false" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div class="profile-field">
            <x-input-label for="email" :value="__('Email')" />
            <div class="profile-input-wrap contact-field-wrap">
                <x-text-input id="email" name="email" type="email" class="block w-full contact-field profile-textbox"
                    :value="old('email', $user->email)" required autocomplete="off" readonly
                    onfocus="this.removeAttribute('readonly');" autocapitalize="off" spellcheck="false" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Phone --}}
        <div class="profile-field">
            <x-input-label for="phone_display" :value="__('Phone')" />
            <div class="profile-input-wrap contact-field-wrap">
                <input type="hidden" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                <textarea
                    id="phone_display"
                    name="vx_phone_display"
                    class="block w-full contact-field profile-textbox profile-textarea-singleline"
                    autocomplete="off"
                    readonly
                    onfocus="this.removeAttribute('readonly');"
                    oninput="document.getElementById('phone').value = this.value"
                    onchange="document.getElementById('phone').value = this.value"
                    data-form-type="other"
                    aria-autocomplete="none"
                    autocapitalize="off"
                    spellcheck="false"
                    rows="1"
                >{{ old('phone', $user->phone) }}</textarea>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        {{-- Address --}}
        <div class="profile-field">
            <x-input-label for="address_display" :value="__('Address')" />
            <div class="profile-input-wrap contact-field-wrap">
                <input type="hidden" id="address" name="address" value="{{ old('address', $user->address) }}">
                <div
                    id="address_display"
                    class="contact-field contact-editable-field profile-textbox profile-editable-field"
                    contenteditable="true"
                    role="textbox"
                    aria-label="Address"
                    spellcheck="false"
                    autocapitalize="words"
                    oninput="document.getElementById('address').value = this.textContent"
                    onkeydown="if (event.key === 'Enter') event.preventDefault();"
                >{{ old('address', $user->address) }}</div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        {{-- Date of Birth --}}
        <div class="profile-field">
            <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
            <div class="profile-input-wrap contact-field-wrap">
                <x-text-input
                    id="date_of_birth"
                    name="date_of_birth"
                    type="date"
                    class="block w-full contact-field profile-textbox"
                    :value="old('date_of_birth', $user->date_of_birth)"
                    autocomplete="off"
                    readonly
                    onfocus="this.removeAttribute('readonly');"
                />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
        </div>

        <div class="flex items-center justify-center gap-4">
            <x-primary-button class="profile-action-button">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
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
