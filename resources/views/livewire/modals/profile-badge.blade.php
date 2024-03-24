<div class="py-8 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark dark:text-white text-center">
    <div class="py-2 text-center">
        <img class="mx-auto" src="{{ asset($badge->badge->image_big) }}" alt="{{ $badge->badge->name }}" title="{{ $badge->badge->name }}" />
    </div>
    <div>
        <div class="text-4xl font-bold">
            {{ $badge->badge->name }}
        </div>
        <div class="mt-2 text-md">
            {{ $badge->badge->description }}
        </div>
        <div class="mt-2 italics text-xs">
            Badge achieved at: {{ date('Y-m-d H:i:s', $badge->achieved_at) }}
        </div>
    </div>
</div>
