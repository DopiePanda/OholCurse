<section>
    <header>
        <h2 class="text-lg font-medium text-skin-base dark:text-skin-base-dark">
            {{ __('DONATOR EXCLUSIVE: Show/hide profile badges') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Choose between showing or hiding your aquired profile badges") }}
        </p>
    </header>

    <form method="post" action="{{ route('badges.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label class="dark:text-gray-200" for="show_badges" :value="__('Display badges')" />
            <select id="show_badges" name="show_badges" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600">
                <option value="show" @if($user->show_badges == "show") selected @endif>
                    Show
                </option>
                <option value="hide" @if($user->show_badges == "hide") selected @endif>
                    Hide
                </option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('show_badges')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'badges-updated')
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
