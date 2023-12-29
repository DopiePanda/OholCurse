<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label class="my-1 text-skin-base dark:text-skin-base-dark" for="username" :value="__('Username')" />
            <x-text-input  placeholder="Enter username" id="username" class="block mt-2 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label class="my-1 text-skin-base dark:text-skin-base-dark" for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-2 w-full"
                            placeholder="Enter password"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-skin-muted dark:text-skin-muted-dark">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4 text-center">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <div class="text-center w-full">
                <button class="w-full p-2 mx-auto text-white rounded-lg bg-skin-fill dark:bg-skin-fill-dark" type="submit">
                    Log in
                </button>
            </div>
            
        </div>
    </form>

    <div class="w-full text-center py-3 my-2 text-skin-muted dark:text-skin-muted-dark"> 
        Or 
    </div>

    <div>
        <button class="w-full p-2 mx-auto border border-skin-base dark:border-skin-base-dark text-skin-base rounded-lg dark:text-skin-base-dark" onclick="Livewire.dispatch('openModal', { component: 'modals.authorize-modal' })">
            Authorize
        </button>
    </div>    
</x-guest-layout>