@php
$timezone = Auth::user()->timezone ?? 'UTC';
date_default_timezone_set($timezone);

@endphp

@section("page-title")- Roadmap @endsection


<div class="w-full lg:max-w-2/3">
    <x-slot name="header">
        <div class="flex flex-row">
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-gray-300 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            <div class="flex-1 text-right">
                @auth
                    <div>
                        <a class="w-full px-4 py-2 bg-blue-400 text-white rounded-md" href="{{ route('roadmap.idea.create') }}">
                            Submit a new idea
                        </a>
                    </div>
                @endauth
            </div>
        </div>
        
        
    </x-slot>

    <div class="w-full z-10 lg:w-2/3 lg:max-w-2/3 mx-auto mt-2 lg:py-12 border border-0 dark:bg-slate-700">
        
        <div class="text-3xl text-center">Hottest submitted by users</div>

        <div class="w-2/3 mx-auto">
            @forelse ($hottest as $idea)
                <div class="border rounded-lg p-3 my-2">
                    <div class="p-2">{{ $idea->title }}</div>
                    <div class="p-2">{!! $idea->description !!}</div>
                </div>
            @empty
                <div class="border rounded-lg p-3 -my2">
                    <div>There are no ideas. Submit the very first one!</div>
                </div>
            @endforelse
        </div>

        <div class="text-3xl text-center">Newest ideas submitted by users</div>

        <div class="w-2/3 text-center mx-auto">
            @forelse ($newest as $idea)
                <div class="border rounded-lg p-3 -my2">
                    {{ $idea->title }}
                </div>
            @empty
                <div class="border rounded-lg p-3 -my2">
                    <div>There are no ideas. Submit the very first one!</div>
                </div>
            @endforelse
        </div>
</div>