<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>OHOLCurse - @yield('title')</title>

        

        <style>
            body {
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script type="text/javascript">
            let defaultDarkmode = "{{ env('DEFAULT_DARKMODE') }}";
            let userDarkmode = "{{ auth()->user()->darkmode ?? null }}";
            let defaultTheme = "{{ env('DEFAULT_THEME') }}";
            let userTheme = "{{ auth()->user()->theme ?? null }}";
        </script>
        <script src="{{ asset('assets/js/theme-selector.js') }}"></script>
    </head>
    <body class="px-4 antialiased bg-gray-100 dark:bg-slate-800">
        <div class="pt-4 text-center">
            <img class="w-96 mx-auto" src="{{ asset('assets/uploads/images/new-logo-transparent.png') }}" alt="oholcurse-logo" />
        </div>
        <div class="relative flex flex-col items-top justify-center sm:items-center sm:pt-0">
            <div class="py-12 max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 items-center pt-8 sm:justify-start sm:pt-0">
                    <div class="">
                        <img class="max-w-96 mx-auto rounded-lg" src="@yield('image')" alt="oholcurse-logo" />
                    </div>
                    <div class="px-4 text-6xl font-bold text-center text-skin-base dark:text-skin-base-dark tracking-wider">
                        @yield('code')
                        <div class="text-xs uppercase">@yield('title')</div>
                    </div>

                    <div class="text-white ml-4 text-center text-md text-gray-500 uppercase tracking-wider">
                        @yield('message')
                        <div class="italic text-gray-800">@yield('sub-message')</div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
