<div class="max-w-4xl bg-gray-200 px-2 lg:px-4 py-4">
    <span class="px-2 py-1 bg-skin-fill dark:bg-skin-fill-dark rounded">
        <a href="{{ route('news.index') }}">
            <i class="fa-regular fa-square-caret-left text-white"> Back</i>
        </a>
    </span>
    <div class="mt-1 bg-gray-800 p-2">
        <div>
            <img class="mx-auto object-cover" src="{{ asset($images['primary'] ? $images['primary']['image_url'] : 'MISSING') }}" />
        </div>
        <!--
        <span class="px-2 py-1 rounded bg-white">
            Article by: 
        </span>
        -->
    </div>
    <div class="mt-4 text-6xl font-bold">{{ $article->title }}</div>
    <div class="mt-4 pt-2 text-lg">
        {!! nl2br($article->content) !!}
    </div>

    <!-- Comment section -->
    <div class="mt-6">
        <livewire:news.comments :article="$article->id">
    </div>
    
</div>
