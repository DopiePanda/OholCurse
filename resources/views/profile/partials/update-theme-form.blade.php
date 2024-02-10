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
                    <div class="w-24 h-24 mx-auto mb-2 rounded-lg bg-gradient-to-tr from-red-800 to-red-600 shadow-xl shadow-red-900/75">
                        <i class="mt-4 fa-solid fa-fire fa-4x text-red-950"></i>
                    </div>
                    <div><label class="dark:text-gray-400" for="theme-light">Fire Red</label></div>
                    <div><input type="radio" name="theme" value="fire-red" @if(Auth::user()->theme == 'disabled') checked @endif /></div>
                </div>
                <div>
                    <div class="w-24 h-24 mx-auto mb-2 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 shadow-xl shadow-blue-800/75">
                        <i class="mt-4 fa-solid fa-droplet fa-4x text-blue-950"></i>
                    </div>
                    <div><label class="dark:text-gray-400" for="theme-dark">Cool Blue</label></div>
                    <div><input type="radio" name="theme" value="cool-blue" @if(Auth::user()->theme == 'enabled') checked @endif /></div>
                </div>
                <div>
                    <div class="w-24 h-24 mx-auto mb-2 border rounded-lg border-gray-800 dark:border-gray-400">
                        <i class="mt-4 fa-regular fa-clock fa-4x text-gray-500"></i>
                    </div>
                    <div><label class="dark:text-gray-400" for="theme-auto">Coming soon</label></div>
                    <div><input type="radio" name="theme" value="NA" @if(Auth::user()->theme == 'NA') checked @endif disabled /></div>
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
