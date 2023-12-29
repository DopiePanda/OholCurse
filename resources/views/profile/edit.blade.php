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
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-slate-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-timezone-form')
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-slate-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-theme-form')
                </div>
            </div>
            <!-- Change username partial
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
            -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-slate-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-slate-700">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
