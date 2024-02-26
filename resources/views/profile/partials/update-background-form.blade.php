<section>
    <header>
        <h2 class="text-lg font-medium text-skin-base dark:text-skin-base-dark">
            {{ __('EXCLUSIVE: Animated profile background') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Customize your public player profile with an exclusive animated background") }}
        </p>
    </header>

    <form method="post" action="{{ route('background.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label class="dark:text-gray-200" for="background" :value="__('Background')" />
            <select id="background" name="background" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600">
                <option value="none" @if($user->background == "none" || $user->background == null) selected @endif>
                    None
                </option>
                <option value="squares" @if($user->background == "squares") selected @endif>
                    Squares
                </option>
                <option value="gradient" @if($user->background == "gradient") selected @endif>
                    Gradient
                </option>
                <option value="hearts" @if($user->background == "hearts") selected @endif>
                    Hearts
                </option>
                <option value="dots" @if($user->background == "dots") selected @endif>
                    Dots
                </option>
                <option value="goobs" @if($user->background == "goobs") selected @endif>
                    Goobs
                </option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('background')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'background-updated')
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
