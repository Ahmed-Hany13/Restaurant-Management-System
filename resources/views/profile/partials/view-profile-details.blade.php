<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Your Profile') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Profile details overview.') }}
        </p>
    </header>

    <div class="mt-6 flex items-center gap-6">
        <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0">
            @php
                $profilePicture = null;
                try {
                    $profilePicture = $user->profile_picture ?? null;
                } catch (\Throwable $e) {
                    $profilePicture = null;
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

        <div>
            <div class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $user->name ?? __('User') }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                {{ $user->email ?? '' }}
            </div>

        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Role') }}</div>
            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->role ?? __('N/A') }}</div>

        </div>

        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Phone') }}</div>
            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                {{ (function () use ($user) { try { return $user->phone ?? __('Not set'); } catch (\Throwable $e) { return __('Not set'); } })() }}
            </div>


        </div>
    </div>
</section>

