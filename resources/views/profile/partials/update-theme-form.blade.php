<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-200">
            {{ __('Select theme') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Select the default website theme") }}
        </p>
    </header>

    <form method="post" action="{{ route('theme.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <div class="mt-2 text-center grid grid-cols-1 lg:grid-cols-3">
                <div>
                    <div><img class="mx-auto mb-2 border rounded-lg border-gray-800 dark:border-gray-400" src="{{ asset('assets/uploads/images/theme-light.png') }}" alt=""></div>
                    <div><label class="dark:text-gray-400" for="theme-light">Light</label></div>
                    <div><input type="radio" name="theme" value="light" @if(Auth::user()->theme == 'light') checked @endif /></div>
                </div>
                <div>
                    <div><img class="mx-auto mb-2 border rounded-lg border-gray-800 dark:border-gray-400" src="{{ asset('assets/uploads/images/theme-dark.png') }}" alt=""></div>
                    <div><label class="dark:text-gray-400" for="theme-dark">Dark</label></div>
                    <div><input type="radio" name="theme" value="dark" @if(Auth::user()->theme == 'dark') checked @endif /></div>
                </div>
                <div>
                    <div><img class="mx-auto mb-2 border rounded-lg border-gray-800 dark:border-gray-400" src="{{ asset('assets/uploads/images/theme-default.png') }}" alt=""></div>
                    <div><label class="dark:text-gray-400" for="theme-auto">Auto</label></div>
                    <div><input type="radio" name="theme" value="auto" @if(Auth::user()->theme == null) checked @endif /></div>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('theme')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'theme-updated')
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
