<div class="max-w-4xl bg-gray-200 px-2 lg:px-4 py-4">

    @section("page-title", "- $article->title" ?? 'Read this article now')

    @section("before-head-end")
        <meta content="OHOLCurse - Cursed Times" property="og:site_name" />
        <meta property="og:image" content="{{ asset($images['primary'] ? $images['primary']['image_url'] : 'MISSING') }}" />
        <meta content="{{ $article->title }}" property="og:title" />
        <meta content="{{ Str::take($article->content, 200).' ..' }}" property="og:description" />
    @endsection

    <span class="px-2 py-1 bg-skin-fill dark:bg-skin-fill-dark rounded">
        <a href="{{ route('news.index') }}">
            <i class="fa-regular fa-square-caret-left text-white"> Back</i>
        </a>
    </span>

    <div class="mt-4 py-2 text-6xl font-bold">
        {{ $article->title }}
    </div>

    <div class="mt-1 bg-gray-800 p-2">
        <div>
            <img class="mx-auto object-cover" src="{{ asset($images['primary'] ? $images['primary']['image_url'] : 'MISSING') }}" />
        </div>

        @if($images['primary']['caption'])
            <div class="p-2 text-gray-200 italic">
                Caption: {{ $images['primary']['caption'] }}
            </div>
        @endif

    </div>

    <div class="flex flex-rows py-2 px-2 bg-gray-300">
        <div class="grow">
            @if($article->author)
                <div class="font-bold">
                    <span>Author: {{ $article->author }}</span>
                    @if($article->agency)
                        ({{ $article->agency }})
                    @endif
                </div>
            @endif
            <div class="mt-1 text-sm">
                Published: {{ $article->created_at->format('Y-m-d H:i') }} 
                @if($article->created_at != $article->updated_at) 
                | Updated: {{ $article->updated_at->format('Y-m-d H:i') }}
                @endif
            </div>
        </div>
        <div class="grow content-center text-right">
            <span class="font-bold">
                <i class="fa-solid fa-eye text-skin-base dark:text-skin-base-dark"></i> {{ $article->views }}
            </span>
        </div>
    </div>
    
    <div class="mt-4 pt-2 text-lg">
        {!! nl2br($article->content) !!}
    </div>

    <!-- Comment section -->
    <div class="mt-6">
        <livewire:news.comments :article="$article->id">
    </div>
    
</div>
