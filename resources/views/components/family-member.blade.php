@php
    $colors = [
        'border-blue-400',
        'border-red-400',
        'border-green-400',
        'border-orange-400',
    ];

    $border = \Arr::random($colors);
@endphp

<div class="w-full pl-1 py-1 text-gray-400 border {{ $border }}">
    @if($member->name && $member->name->name)
        <div class="w-full py-1 ml-2" onclick="Livewire.dispatch('openModal', {component: 'modals.character.details', arguments: {character_id: {{$member->character_id}}}})">{{ $member->name->name }} ({{ $member->character_id }})</div>
    @endif
    @foreach ($member->children as $child)
        <div class="w-full">
            @if($child->name && $child->name->name)
                <x-family-member :member="$child" />
            @endif
        </div>
    @endforeach
</div>