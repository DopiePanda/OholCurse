<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <!-- Meta information -->
        <meta charset="utf-8">
        <meta name="description" content="Browse the player profiles from One Hour One Life and grab your spot on the leaderboards">
        <meta name="robots" content="all">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Page title and icon -->
        <title>{{ config('app.name', 'OHOLCurse') }} @yield('page-title')</title>
        <link rel="icon" href="{{ asset('assets/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <!-- Alpine Plugins -->
        @filamentStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <script type="text/javascript">
            let defaultDarkmode = "{{ env('DEFAULT_DARKMODE') }}";
            let userDarkmode = "{{ auth()->user()->darkmode ?? null }}";
            let defaultTheme = "{{ env('DEFAULT_THEME') }}";
            let userTheme = "{{ auth()->user()->theme ?? null }}";
        </script>
        <script src="{{ asset('assets/js/theme-selector.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="https://kit.fontawesome.com/737926afbe.js" crossorigin="anonymous"></script>
        <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
        <script src="{{ asset('assets/js/toastr.min.js') }}"></script>

        <!-- Stylesheets -->
        <link rel="stylesheet" href="{{ asset('assets/css/trix.css') }}">

        @stack('styles')

        @yield('before-head-end')
    </head>
    <body class="font-sans antialiased {{ auth()->user() ? auth()->user()->theme : env('DEFAULT_THEME') }}">
        <div id="background" class="z-10 flex flex-col min-h-screen bg-gray-100 dark:bg-slate-800">
            <x-includes.navigation/>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="z-20 bg-white dark:bg-slate-700 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="z-10 flex flex-grow justify-center pt-6 px-2 lg:px-4 break-normal">
                {{ $slot }}
            </main>

            <div class="z-30 py-4 sm:px-4 flex flex-row items-end">
                <div class="z-20 shrink md:basis-1/4 text-left">
                    @can('access admin panel')
                        <div class="fixed bottom-0 left-0 pb-4 pl-4">
                            <a class="z-30" href="/admin" title="Admin panel">
                                <i class="fa-solid fa-toolbox fa-2x text-skin-base dark:text-skin-base-dark"></i>
                            </a>
                        </div>
                    @endcan
                </div> 
                <div class="z-20 grow md:basis-2/4 text-center">
                    @if(env('DONATION_BANNER') == 'true')
                        <span id="donationBanner" class="text-skin-muted dark:text-skin-muted-dark cursor-default">
                            Want to help support the website?
                            <div class="mt-3">
                                <a href="{{ env('DONATION_URL') }}" id="donationLink" target="_blank" class="border rounded-full py-1 px-2 lg:px-4 text-lg text-skin-base dark:text-skin-base-dark">
                                    <i class="fa-solid fa-mug-hot mr-1 text-skin-base dark:text-skin-base-dark"></i> Donate a coffee
                                </a>
                            </div>
                        </span> 
                        
                        <div class="mt-4">
                            <a class="text-gray-400" href="{{ route('privacy-policy') }}">
                                Privacy Policy
                            </a>
                        </div>
                    @endif
                </div>
                <div class="z-10 shrink md:basis-1/4 text-right">
                    @if(env('CHAT_ENABLED') == 'true')
                        @auth
                            <livewire:conversations.inbox />
                        @endauth
                    @endif
                </div>
            </div>

            <x-effects.snow/>

            @if(date('d.m') == '01.04' || env('APRIL_FOOLS'))
                <x-effects.april-fools/>
            @endif

        </div>

        @filamentScripts
        {{-- modalwidth comment for tailwind purge, used widths: sm:max-w-sm sm:max-w-md sm:max-w-lg sm:max-w-xl sm:max-w-2xl sm:max-w-3xl sm:max-w-4xl sm:max-w-5xl sm:max-w-6xl sm:max-w-7xl --}}
        @livewire('wire-elements-modal')
        @livewireScripts
        @stack('scripts')
    
        <script type="text/javascript">

            $( document ).ready(function() {
                window.addEventListener('alert', event => { 
                         toastr[event.detail.type](event.detail.message, 
                         event.detail.title ?? ''), toastr.options = {
                                "closeButton": true,
                                "progressBar": true,
                            }
                        });
            });
        </script>
        
        <!-- 
        Hot and amazing people:
            - Nezima
            - TheGeniusPhoenix
            - Mingo888
            - Selb
            - Hopie
        -->

        @yield('before-body-end')
        <x-toaster-hub />
    </body>
</html>
