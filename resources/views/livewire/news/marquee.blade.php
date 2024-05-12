<div class="bg-gray-800 z-20 relative flex overflow-x-hidden">
    <div class="py-2 animate-marquee whitespace-nowrap">
        @foreach ($articles as $article)
            <span class="mx-3 text-lg font-bold">
                <a href="{{ route('news.article', ['id' => $article->id, 'slug' => $article->slug]) }}">
                    <span class="uppercase text-red-600">
                        {{ $article->type }}: 
                    </span>
                    <span class="text-white">
                        {{ $article->title }}
                    </span>
                    <span class="pl-6 text-white h-full w-1 border-r border-gray-600"></span>
                </a>
            </span>
        @endforeach
      </div>
    
      <div class="absolute top-0 py-2 animate-marquee2 whitespace-nowrap">
            @foreach ($articles as $article)
                <span class="mx-3 text-lg font-bold">
                    <a href="{{ route('news.article', ['id' => $article->id, 'slug' => $article->slug]) }}">
                        <span class="uppercase text-red-600">
                            {{ $article->type }}: 
                        </span>
                        <span class="text-white">
                            {{ $article->title }}
                        </span>
                        <span class="pl-6 text-white h-full w-1 border-r border-gray-600"></span>
                    </a>
                </span>
            @endforeach
      </div>
</div>
