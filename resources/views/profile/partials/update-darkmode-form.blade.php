<section>
    <header>
        <h2 class="text-lg font-medium text-skin-base dark:text-skin-base-dark">
            {{ __('Toggle darkmode') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Here you can enable or disable darkmode for the website. If set to 'auto', it will use the default settings for your browser/operating system.") }}
        </p>
    </header>

    <form method="post" action="{{ route('darkmode.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <div class="mt-2">
                <div>
                        <select name="darkmode" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600">
                            <option value="enabled" @if(Auth::user()->darkmode == 'enabled') selected @endif>Enabled</option>
                            <option value="disabled" @if(Auth::user()->darkmode == 'disabled') selected @endif>Disabled</option>
                            <option value="auto" @if(Auth::user()->darkmode == 'auto') selected @endif>Auto</option>
                        </select>
                </div>

            </div>
            <x-input-error class="mt-2" :messages="$errors->get('darkmode')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'darkmode-updated')
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
