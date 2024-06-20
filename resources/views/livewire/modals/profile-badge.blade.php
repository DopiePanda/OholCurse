<div class="py-8 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark dark:text-white text-center">
    <div class="py-2 text-center">
        <img class="mx-auto mb-1" src="{{ asset($badge->badge->image_big) }}" alt="{{ $badge->badge->name }}" title="{{ $badge->badge->name }}" />
        @if($badge->badge->creator_name)
            @if($badge->badge->creator_url)
                <a href="{{ $badge->badge->creator_url }}" target="_blank">
            @endif

            <div class="text-amber-300 mb-2">
                Credit: <div class="inline font-bold">{{ $badge->badge->creator_name }}</div>
            </div>

            @if($badge->badge->creator_url)
                </a>
            @endif
        @endif
    </div>
    <div>
        <div class="text-6xl font-bold">
            {{ $badge->badge->name }}
        </div>
        <div class="px-1 lg:px-8 mt-2 text-md">
            {{ $badge->badge->description }}
        </div>
        <div class="mt-4 italics text-xs">
            Badge achieved at: {{ date('Y-m-d H:i:s', $badge->achieved_at) }}
        </div>
    </div>
</div>
