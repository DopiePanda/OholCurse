<div class="w-full pl-1 py-1 text-gray-400 border">
    @if($member->name && $member->name->name)
        <div class="w-full py-1 ml-2">{{ $member->name->name }} ({{ $member->character_id }})</div>
    @endif
    @foreach ($member->children as $child)
        <div class="w-full">
            @if($child->name && $child->name->name)
                <x-family-member :member="$child" />
            @endif
        </div>
    @endforeach
</div>