<div class="w-full">
    <div class="p-4 mx-auto bg-white w-3/5">

        <!-- Newspaper name -->
        <div class="py-4 text-center font-bold font-serif text-6xl">
            <h1>Cursed Times</h1>
        </div>

        <!-- Articles - Reports -->
        <div class="grow my-4 grid grid-cols-3 gap-4">
            @foreach($articles as $article)
                <div class="max-w-96 p-2 border border-gray-400 flex flex-col">
                    <a class="block w-full h-full" href="{{ route('news.article', ['id' => $article->id, 'slug' => $article->slug]) }}">
                        <div class="mt-2 h-60 border-2 border-black grow">
                            <img class="object-cover w-full h-full" src="{{ asset($article->images->first()->image_url ?? 'MISSING') }}" />
                        </div>
                        <div class="py-2 font-serif text-xl font-bold">
                            {{ $article->title }}
                        </div>
                    </a>
                </div>

                @foreach($ads as $ad)
                    @if($loop->parent->index == $ad->index)
                        <div class="grow max-w-96 p-2 border border-gray-400 flex flex-col">
                            <div class="mt-2 h-60 border-8 border-black grow">
                                <img class="object-cover w-full h-full" src="{{ asset($ad->image_url) }}" />
                            </div>
                            <div class="py-2 uppercase text-center font-serif text-xl font-bold">
                                {{ $ad->title }}
                            </div>
                        </div>
                    @endif
                @endforeach

            @endforeach
        </div>

        <!-- AD TEMPLATE

        <div class="grow max-w-96 p-2 border border-gray-400 flex flex-col">
            <div class="mt-2 h-60 border-8 border-black grow">
                <img class="object-cover w-full h-full" src="{{ asset('/assets/news-articles/ad-1.jpg') }}" />
            </div>
            <div class="py-2 uppercase text-center font-serif text-xl font-bold">
                Register now
            </div>
        </div>

        -->

        <!-- Articles - Lives -->
        <div class="grow my-4 h-96 flex flex-row gap-4">
            <div class="max-w-96 p-2 border border-gray-400 flex flex-col">
                <div class="mt-2 h-60 border grow">
                    <img class="object-cover w-full h-full" src="{{ asset('/assets/news-articles/first-person-in-space.jpg') }}" />
                </div>
                <div class="py-2 font-serif text-xl font-bold">
                    First man going to space - Turns into woman halfway
                </div>
            </div>
            <div class="max-w-96 p-2 border border-gray-400 flex flex-col">
                <div class="mt-2 h-60 border grow">
                    <img class="object-cover w-full h-full" src="{{ asset('/assets/news-articles/heroes-save-the-world.jpg') }}" />
                </div>
                <div class="py-2 font-serif text-xl font-bold">
                    Nunc ut neque nec lorem varius cursus eget id magna.
                </div>
            </div>
            <div class="max-w-96 p-2 border border-gray-400 flex flex-col">
                <div class="mt-2 h-60 border grow">
                    <img class="object-cover w-full h-full" src="#" />
                </div>
                <div class="py-2 font-serif text-xl font-bold">
                    Etiam laoreet mauris dignissim erat hendrerit molestie.
                </div>
            </div>
        </div>
        
        <!-- Articles - Music -->
        <div class="grow    my-4 h-96 flex flex-row gap-4">
            <div class="max-w-96 p-2 border border-gray-400 flex flex-col">
                <div class="mt-2 h-60 border grow">
                    <img class="object-cover w-full h-full" src="{{ asset('/assets/news-articles/first-person-in-space.jpg') }}" />
                </div>
                <div class="py-2 font-serif text-xl font-bold">
                    First man going to space - Turns into woman halfway
                </div>
            </div>
            <div class="max-w-96 p-2 border border-gray-400 flex flex-col">
                <div class="mt-2 h-60 border grow">
                    <img class="object-cover w-full h-full" src="{{ asset('/assets/news-articles/heroes-save-the-world.jpg') }}" />
                </div>
                <div class="py-2 font-serif text-xl font-bold">
                    Nunc ut neque nec lorem varius cursus eget id magna.
                </div>
            </div>
            <div class="max-w-96 p-2 border border-gray-400 flex flex-col">
                <div class="mt-2 h-60 border grow">
                    <img class="object-cover w-full h-full" src="#" />
                </div>
                <div class="py-2 font-serif text-xl font-bold">
                    Etiam laoreet mauris dignissim erat hendrerit molestie.
                </div>
            </div>
        </div>

    </div>
</div>
