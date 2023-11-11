<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-200">
            {{ __('Set your Timezone') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Select the timezone you wish all dates to be displayed as.") }}
        </p>
    </header>

    <form method="post" action="{{ route('timezone.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label class="dark:text-gray-200" for="timezone" :value="__('Timezone')" />
            <select id="timezone" name="timezone" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600">
                @foreach ($timezones as $tz)
                    <option value="{{ $tz->name }}" @if($user->timezone == $tz->name) selected @endif>{{ $tz->name }} ({{ $tz->offset }})</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'timezone-updated')
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
