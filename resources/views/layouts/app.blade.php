<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <!-- Meta information -->
        <meta charset="utf-8">
        <meta name="description" content="Browse the player profiles from One Hour One Life and grab your spot on the leaderboards">
        <meta name="robots" content="all">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Page title and icon -->
        <title>{{ config('app.name', 'OHOL Curse') }} @yield('page-title')</title>
        <link rel="icon" href="{{ asset('assets/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <!-- Alpine Plugins -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://kit.fontawesome.com/737926afbe.js" crossorigin="anonymous"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <!-- Stylesheets -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">

        @yield('before-head-end')
    </head>
    <body class="font-sans antialiased">
        <div class="z-10 flex flex-col min-h-screen bg-gray-100 dark:bg-slate-800">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="z-10 bg-white dark:bg-slate-700 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="z-10 flex flex-grow justify-center pt-6 px-2 lg:px-4 break-normal">
                {{ $slot }}
                
            </main>
            <x-effects.snow/>
        </div>

        
        @livewire('wire-elements-modal')
        @livewireScripts
        @stack('scripts')
    
        <script type="text/javascript">

            let defaultTheme = "{{ env('DEFAULT_THEME') }}";
            let userTheme = "{{ auth()->user()->theme ?? null }}";

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

        <script src="{{ asset('assets/js/theme-selector.js') }}"></script>

        <!-- 
        Hot and amazing people:
            - Nezima
            - TheGeniusPhoenix
            - Mingo888
            - Selb
            - Hopie
        -->

        @yield('before-body-end')
    </body>
</html>
