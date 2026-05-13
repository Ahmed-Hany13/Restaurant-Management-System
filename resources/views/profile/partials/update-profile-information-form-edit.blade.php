<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your name, email, role, phone, and profile picture.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Profile Picture --}}
        <div class="flex items-center gap-6">
            <div class="shrink-0">
                <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    @php
                        $profilePicture = null;
                        if (isset($user) && !empty($user->profile_picture)) {
                            $profilePicture = $user->profile_picture;
                        }
                    @endphp

                    @if (!empty($profilePicture))

                        <img
                            src="{{ str_starts_with($profilePicture, 'http') ? $profilePicture : asset('storage/'.$profilePicture) }}"
                            alt="{{ __('Profile picture') }}"
                            class="w-full h-full object-cover"
                        />
                    @else
                        <span class="text-gray-500 dark:text-gray-300 text-sm">{{ __('No photo') }}</span>
                    @endif
                </div>
            </div>

            <div class="flex-1">
                <x-input-label for="profile_picture" :value="__('Profile picture (JPG/PNG, max 2MB)')" />
                <input
                    id="profile_picture"
                    name="profile_picture"
                    type="file"
                    accept="image/png,image/jpeg"
                    class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-200"
                />

                <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />

                <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                    {{ __('Upload a JPG or PNG file (max 2MB).') }}
                </p>
            </div>
        </div>

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

<button
                            form="send-verification"
                            class="btn btn-link px-0"
                            type="submit"
                        >
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Role (read-only) --}}
        <div>
            <x-input-label for="role" :value="__('Role')" />
            <x-text-input
                id="role"
                name="role"
                type="text"
                class="mt-1 block w-full"
                :value="old('role', $user->role)"
                disabled
                autocomplete="off"
            />
        </div>

        {{-- Phone --}}
        <div>
            <x-input-label for="phone" :value="__('Phone')" />
                <x-text-input
                id="phone"
                name="phone"
                type="text"
                class="mt-1 block w-full"
                :value="old('phone', (function () use ($user) { try { return $user->phone ?? ''; } catch (\Throwable $e) { return ''; } })())"

                placeholder="{{ __('e.g. +2010XXXXXXX') }}"
                autocomplete="tel"
            />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div class="flex items-center gap-4">
<button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

