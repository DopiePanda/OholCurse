<section>
    <header>
        <h2 class="text-lg font-medium text-skin-base dark:text-skin-base-dark">
            {{ __('Change theme') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Select your favorite website color scheme. All themes works with both light -and darkmode.") }}
        </p>
    </header>

    <form method="post" action="{{ route('theme.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <div class="mt-8 text-center grid grid-cols-1 lg:grid-cols-4">
                <div>
                    <label class="block w-24 h-24 mx-auto mb-2 rounded-lg bg-gradient-to-tr from-red-800 to-red-600 shadow-xl shadow-red-900/75 has-[:checked]:shadow-[0px_0px_15px_5px_#2b2b2b] dark:has-[:checked]:shadow-[0px_0px_15px_5px_#cecece]">
                        <input class="hidden checked:hidden" type="radio" name="theme" id="fire-red" value="fire-red" @if(Auth::user()->theme == 'fire-red') checked @endif />
                        <i class="mt-4 fa-solid fa-fire fa-4x text-red-950/50"></i>
                    </label>
                    <div class="mt-4"><label class="dark:text-gray-400" for="fire-red">Fire Red</label></div>
                    <div></div>
                </div>
                <div>
                    <label class="block w-24 h-24 mx-auto mb-2 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-500 shadow-xl shadow-blue-800/75 has-[:checked]:shadow-[0px_0px_15px_5px_#2b2b2b] dark:has-[:checked]:shadow-[0px_0px_15px_5px_#cecece]">
                        <input class="hidden checked:hidden" type="radio" name="theme" id="cool-blue" value="cool-blue" @if(Auth::user()->theme == 'cool-blue') checked @endif />
                        <i class="mt-4 fa-solid fa-droplet fa-4x text-blue-950/50"></i>
                    </label>
                    <div class="mt-4"><label class="dark:text-gray-400" for="cool-blue">Cool Blue</label></div>
                    <div></div>
                </div>
                <div>
                    <label for="royal-purple" class="block w-24 h-24 mx-auto mb-2 rounded-lg bg-gradient-to-r from-purple-500 to-purple-900 shadow-xl shadow-purple-950/75 has-[:checked]:shadow-[0px_0px_15px_5px_#2b2b2b] dark:has-[:checked]:shadow-[0px_0px_15px_5px_#cecece]">
                        <input class="hidden checked:hidden" type="radio" name="theme" id="royal-purple" value="royal-purple" @if(Auth::user()->theme == 'royal-purple') checked @endif />
                        <i class="mt-4 fa-solid fa-crown fa-4x text-purple-950/50"></i>
                    </label>
                    <div class="mt-4"><label class="dark:text-gray-400" for="royal-purple">Royal Purple</label></div>
                    <div></div>
                </div>
                <div>
                    <label class="block w-24 h-24 mx-auto mb-2 rounded-lg bg-gradient-to-r from-fuchsia-500 to-pink-500 shadow-xl shadow-pink-800/75 has-[:checked]:shadow-[0px_0px_15px_5px_#2b2b2b] dark:has-[:checked]:shadow-[0px_0px_15px_5px_#cecece]">
                        <input class="hidden checked:hidden" type="radio" name="theme" id="popping-pink" value="popping-pink" @if(Auth::user()->theme == 'popping-pink') checked @endif />
                        <i class="mt-4 fa-solid fa-heart fa-4x text-pink-950/50"></i>
                    </label>
                    <div class="mt-4"><label class="dark:text-gray-400" for="popping-pink">Popping Pink</label></div>
                    <div></div>
                </div>
                <div>
                    <label class="block w-24 h-24 mx-auto mb-2 rounded-lg bg-gradient-to-r from-rose-300 to-rose-500 shadow-xl shadow-rose-800/75 has-[:checked]:shadow-[0px_0px_15px_5px_#2b2b2b] dark:has-[:checked]:shadow-[0px_0px_15px_5px_#cecece]">
                        <input class="hidden checked:hidden" type="radio" name="theme" id="smooth-pink" value="smooth-pink" @if(Auth::user()->theme == 'smooth-pink') checked @endif />
                        <i class="mt-4 fa-solid fa-cookie-bite fa-4x text-pink-950/50"></i>
                    </label>
                    <div class="mt-4"><label class="dark:text-gray-400" for="smooth-pink">Smooth Pink</label></div>
                    <div></div>
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
