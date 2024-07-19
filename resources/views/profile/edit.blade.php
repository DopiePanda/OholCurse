<x-app-layout>
    @section("page-title")
        - Profile settings
    @endsection

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-skin-base dark:text-skin-base-dark leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="relative overflow-hidden p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-skin-fill-wrapper-dark">
                <i style="font-size: 12em;" class="z-0 absolute -bottom-9 -left-9 rotate-45 fa-regular fa-clock text-gray-200 dark:text-slate-800/50"></i>
                <div class="relative z-10 max-w-xl">
                    @include('profile.partials.update-timezone-form')
                </div>
            </div>
            @if($user->donator == 1 && $user->player_hash != null)
                <div class="relative overflow-hidden p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-skin-fill-wrapper-dark">
                    <i style="font-size: 12em;" class="z-0 absolute -bottom-9 -right-9 -rotate-45 fa-solid fa-yin-yang text-gray-200 dark:text-slate-800/50"></i>
                    <div class="relative z-10 max-w-xl">
                        @include('profile.partials.update-badges-form')
                    </div>
                </div>

                <div class="relative overflow-hidden p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-skin-fill-wrapper-dark">
                    <i style="font-size: 12em;" class="z-0 absolute -bottom-9 -right-9 -rotate-45 fa-solid fa-yin-yang text-gray-200 dark:text-slate-800/50"></i>
                    <div class="relative z-10 max-w-xl">
                        @include('profile.partials.update-background-form')
                    </div>
                </div>
            @endif
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-skin-fill-wrapper-dark">
                <div class="max-w-xl">
                    @include('profile.partials.update-theme-form')
                </div>
            </div>
            <div class="relative overflow-hidden p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-skin-fill-wrapper-dark">
                <i style="font-size: 12em;" class="z-0 absolute -bottom-9 -right-9 -rotate-45 fa-solid fa-yin-yang text-gray-200 dark:text-slate-800/50"></i>
                <div class="relative z-10 max-w-xl">
                    @include('profile.partials.update-darkmode-form')
                </div>
            </div>
            <!-- Change username partial
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
            -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-skin-fill-wrapper-dark">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-skin-fill-wrapper-dark">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
