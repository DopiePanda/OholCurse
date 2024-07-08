<x-app-layout>
    @section("page-title")- Search OHOL profiles @endsection
    <div class="flex flex-col items-center">
        <img id="oholcurse-logo-full" class="w-96 mb-8" src="{{ asset('assets/uploads/images/new-logo-transparent.png') }}" alt="oholcurse-logo" />
       
        <div class="mt-8 text-white text-4xl">
            No account could be found with that leaderboard name.
        </div>
        <div class="mt-2 text-white italic">
            Please check again in 24 hours when new public log data has been released.
        </div>

        <a href="{{ route('search') }}">
            <div class="px-4 py-2 mt-6 text-white bg-skin-fill dark:bg-skin-fill-dark pointer-cursor rounded-lg">
                Go to homepage
            </div>
        </a>
    </div>
</x-app-layout>