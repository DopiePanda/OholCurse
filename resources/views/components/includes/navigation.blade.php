<div class="z-20">
    <nav x-data="{ open: false }" class="z-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-600">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 md:flex">
                        @auth
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                            </x-nav-link>
                        @endauth

                        <x-nav-link :href="route('search')" :active="request()->routeIs('search')">
                            {{ __('Search') }}
                        </x-nav-link>
                        
                        <!-- <x-nav-link :href="route('leaderboards.index')" :active="request()->routeIs('leaderboards.*')">
                            {{ __('Leaderboards') }}
                        </x-nav-link> -->

                        <x-dropdown align="left" width="56" :active="request()->routeIs('leaderboards.*')">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-6 pb-5 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-600 hover:text-gray-700 dark:hover:text-gray-400 hover:border-gray-300 dark:hover:border-transparent focus:outline-none focus:text-gray-700 focus:border-gray-300 dark:focus:border-transparent transition duration-150 ease-in-out">
                                    <div class="">Leaderboards</div>
        
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
        
                            <x-slot name="content">
                                <x-dropdown-link :href="route('leaderboards.daily')">
                                    {{ __('Daily') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('leaderboards.weekly')">
                                    {{ __('Weekly') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('leaderboards.all-time')">
                                    {{ __('All-time') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>

                        <x-dropdown align="left" width="56" :active="request()->routeIs('tools.*')">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-6 pb-5 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-600 hover:text-gray-700 dark:hover:text-gray-400 hover:border-gray-300 dark:hover:border-transparent focus:outline-none focus:text-gray-700 focus:border-gray-300 dark:focus:border-transparent transition duration-150 ease-in-out">
                                    <div class="">Tools</div>
        
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
        
                            <x-slot name="content">
                                <x-dropdown-link :href="route('tools.names')">
                                    {{ __('Name generator') }}
                                </x-dropdown-link>
                                @auth
                                    @if(auth()->user()->hasAnyRole(['trusted', 'mod', 'admin', 'anti-griefer', 'system']))
                                        <x-dropdown-link :href="route('tools.calculator.map')">
                                            {{ __('Travel calculator') }}
                                        </x-dropdown-link>
                                    @endif
                                @endauth
                            </x-slot>
                        </x-dropdown>

                        <x-dropdown align="left" width="56">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-6 pb-5 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-600 hover:text-gray-700 dark:hover:text-gray-400 hover:border-gray-300 dark:hover:border-transparent focus:outline-none focus:text-gray-700 focus:border-gray-300 dark:focus:border-transparent transition duration-150 ease-in-out">
                                    <div class="">Resources</div>
        
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
        
                            <x-slot name="content">
                                <x-dropdown-link :href="'https://discord.gg/zmk5BfpX3E'" target="_blank">
                                    {{ __('OHOL Discord') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="'https://www.reddit.com/r/onehouronelife/'" target="_blank">
                                    {{ __('OHOL Reddit') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="'https://onehouronelife.com/forums/'" target="_blank">
                                    {{ __('OHOL Forum') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="'https://onetech.info/'" target="_blank">
                                    {{ __('Recipe Guides') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="'https://github.com/selb/YumLife/releases'" target="_blank">
                                    {{ __('YumLife Mod') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="'https://onemap.wondible.com/'" target="_blank">
                                    {{ __('Wondible World Map') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>

                    </div>
                </div>
                <!-- Settings Dropdown -->
                <div class="hidden md:flex sm:items-center sm:ml-6">
                    @auth

                    @if (auth()->user()->player_hash)
                        <a href="{{ route('player.interactions', auth()->user()->player_hash) }}" class="group relative me-4">
                            <div class="text-center">
                                <i class="text-gray-500 fa-regular fa-id-card fa-xl hover:text-skin-base dark:hover:text-skin-base-dark hover:brightness-90"></i>
                            </div>
                            <span class="hidden md:block lg:block w-8 lg:w-32 py-0 lg:py-2 mt-8 z-50 group-hover:opacity-100 transition-opacity bg-gray-800 px-1 text-sm text-gray-100 rounded-md absolute left-0
                            -translate-x-1/2 translate-y-full opacity-0 m-1 lg:m-4 mx-auto lg:-bottom-6 text-center">
                                My profile
                            </span>
                        </a>
                    @endif
                    
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-skin-base text-skin-base text-sm leading-4 font-medium rounded-md hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 dark:bg-transparent dark:border-skin-base-dark dark:text-skin-base-dark">
                                <div>{{ Auth::user()->username }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">
                                {{ __('Dashboard') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Settings') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                    @endauth
                    @guest
                        <button class="p-2 border border-skin-base dark:border-skin-base-dark text-skin-base dark:text-skin-base-dark rounded-lg" onclick="Livewire.dispatch('openModal', { component: 'modals.authorize-modal' })">Authorize</button>
                        <a class="block ml-2 p-2 dark:text-gray-400" href="{{ route('login') }}">Login</a>
                    @endguest
                </div>
                <!-- Hamburger -->
                <div class="-mr-2 flex items-center md:hidden">
                    @guest
                        <button class="p-2 border border-skin-base dark:border-skin-base-dark text-skin-base dark:text-skin-base-dark rounded-lg" onclick="Livewire.dispatch('openModal', { component: 'modals.authorize-modal' })">Auth</button>
                    @endguest
                    <button @click="open = ! open" class="ml-2 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out dark:focus:bg-slate-700">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden">
            <div class="pt-2 pb-3 space-y-1">
                @guest
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('login')">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                @endguest
                @auth
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                @endauth
                <x-responsive-nav-link :href="route('search')" :active="request()->routeIs('search')">
                    {{ __('Search') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('leaderboards.index')" :active="request()->routeIs('leaderboards.*')">
                    {{ __('Leaderboards') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('tools.names')" :active="request()->routeIs('tools.names')">
                    {{ __('Name generator') }}
                </x-responsive-nav-link>
                @auth
                    @if(auth()->user()->hasAnyRole(['trusted', 'mod', 'admin', 'anti-griefer', 'system']))
                        <x-responsive-nav-link :href="route('tools.calculator.map')" :active="request()->routeIs('tools.calculator.map')">
                            {{ __('Travel calculator') }}
                        </x-responsive-nav-link>
                    @endif
                @endauth
            </div>
            @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 shadow-lg bg-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->username }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Settings') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </nav>
</div>