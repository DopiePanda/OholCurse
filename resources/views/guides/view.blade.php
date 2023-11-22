<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-400">
            {{ $guide->title }}
        </h2>
    </x-slot>

    <div class="grow py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="min-w-2/3 w-full bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-slate-700">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200 dark:bg-slate-700 dark:border-gray-900">
                    <div class="trix-editor">
                        {!! $guide->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>