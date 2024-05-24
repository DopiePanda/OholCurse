<div class="w-full">

    @section("page-title", "- The hottest OHOL news")

    <div class="flex flex-cols lg:flex-rows gap-2">
        <div class="hidden lg:block basis-1/5 shrink">

        </div>

        <div class="grow p-4 mx-auto bg-white w-full lg:w-3/5">

            <!-- Newspaper name -->
            <div class="py-4 text-center font-bold font-serif text-6xl">
                <h1>Cursed Times</h1>
            </div>

            <!-- Articles - Reports -->
            <div class="grow my-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($articles as $article)
                    <div class="mx-auto max-w-96 border border-gray-400 flex flex-col">
                        <a class="flex flex-col block w-full h-full" href="{{ route('news.article', ['id' => $article->id, 'slug' => $article->slug]) }}">
                            <div class="grow relative border-2 border-black grow">
                                <img class="object-cover w-full h-full" src="{{ asset($article->images->first()->image_url ?? 'MISSING') }}" />
                                <div class="absolute left-0 bottom-0 ml-1 mb-1 px-2 text-sm rounded-full bg-red-500 uppercase text-white">
                                    {{ $article->type }}
                                </div>
                                <div class="absolute right-0 bottom-0 ml-1 mb-1 px-2 text-sm rounded-full bg-red-500 uppercase text-white">
                                    <i class="fa-solid fa-eye"></i>
                                    {{ $article->views }}
                                </div>
                            </div>
                            <div class="shrink p-2 font-serif text-xl font-bold">
                                {{ $article->title }}
                            </div>
                        </a>
                    </div>
                @endforeach      
            </div>
        </div>

        <div class="hidden lg:block basis-1/5 shrink">
            <div class="grid grid-cols-1 gap-2 w-96">
                @foreach($ads as $ad)
                <div class="bg-white mx-auto max-w-96 border border-gray-400 flex flex-col">
                    <a class="flex flex-col block w-full h-full" href="{{ route('news.download', ['id' => $ad->id]) }}">
                        <div class="grow relative border-2 border-black">
                            <img class="object-cover w-full h-full" src="{{ asset($ad->image_url ?? 'MISSING') }}" />
                        </div>
                        <div class="uppercase text-center shrink p-2 font-serif text-xl font-bold">
                            {{ $ad->title }}
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
