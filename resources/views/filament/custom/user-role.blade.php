@php
    $roles = auth()->user()->getRoleNames();
@endphp

<div class="flex flex-row">
    @impersonating($guard = null)
        <div class="inline-block text-gray-600 mr-2">
            <a href="{{ route('impersonate.leave') }}">Leave impersonation</a>
        </div>
    @endImpersonating

    @if (count($roles) > 1)
        <div class="inline-block text-gray-600 mr-2">Roles:</div>
        @foreach ($roles as $role)
            <div class="inline-block p-1 border border-gray-600 rounded-lg text-primary-500">
                {{ ucfirst($role) }}
            </div>
        @endforeach
    @else
        <div class="uppercase text-sm">
            <div class="inline-block text-gray-600 mr-2">Role:</div> 
            <div class="inline-block p-1 border border-gray-600 rounded-lg text-primary-500">{{ ucfirst($roles[0]) }}</div>
        </div>
    @endif
</div>