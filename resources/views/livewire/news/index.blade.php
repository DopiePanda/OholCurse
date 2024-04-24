<div class="w-full">
    <div class="p-4 mx-auto bg-white w-3/5">

        <!-- Newspaper name -->
        <div class="py-4 text-center font-bold font-serif text-6xl">
            <h1>Cursed Times</h1>
        </div>

        <!-- Articles - Reports -->
        <div class="grow my-4 grid grid-cols-3 gap-4">
            @foreach($articles as $article)
                <div class="max-w-96 border border-gray-400 flex flex-col">
                    <a class="block w-full h-full" href="{{ route('news.article', ['id' => $article->id, 'slug' => $article->slug]) }}">
                        <div class="relative border-2 border-black grow">
                            <img class="object-cover w-full h-full" src="{{ asset($article->images->first()->image_url ?? 'MISSING') }}" />
                            <div class="absolute left-0 bottom-0 ml-1 mb-1 px-2 text-sm rounded-full bg-red-500 uppercase text-white">
                                {{ $article->type }}
                            </div>
                        </div>
                        <div class="p-2 font-serif text-xl font-bold">
                            {{ $article->title }}
                        </div>
                    </a>
                </div>

                @foreach($ads as $ad)
                    @if($loop->parent->index == $ad->index)
                        <div class="max-w-96 border border-gray-400 flex flex-col">
                            @if($ad->url)
                                <a href="{{ $ad->url }}">
                            @endif
                                
                            <div class="relative border-2 border-black grow">
                                <img class="object-cover w-full h-full" src="{{ asset($ad->image_url) }}" />
                                <div class="absolute left-0 bottom-0 ml-1 mb-1 px-2 text-sm rounded-full bg-red-500 uppercase text-white">
                                    AD
                                </div>
                            </div>
                            <div class="p-2 uppercase text-center font-serif text-xl font-bold">
                                {{ $ad->title }}
                            </div>

                            @if($ad->url)
                                </a>
                            @endif
                        </div>
                    @endif
                @endforeach

            @endforeach
        </div>

    </div>
</div>
