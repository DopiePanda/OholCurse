<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center">
            <a href="{{ route('guides.index') }}">
                <div class="shrink self-center text-lg bg-skin-fill border border-skin-muted rounded-lg py-2 px-4 text-white">
                    <i class="fa-solid fa-circle-arrow-left"></i>
                    <span class="inline-block ml-1 uppercase">Back</span>
                </div>
            </a>
            <div class="grow">
                <h2 class="uppercase text-center text-5xl leading-tight text-skin-base dark:text-skin-base-dark">
                    {{ $guide->title }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="grow py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="min-w-2/4 w-full bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-slate-700">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200 dark:bg-slate-700 dark:border-gray-900">
                    <div class="trix-editor dark:text-gray-200">
                        {!! $guide->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>