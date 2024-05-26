<div class="w-full">

    @section("page-title", "- The hottest OHOL news")

    <div class="flex flex-cols lg:flex-rows gap-2">
        <div class="hidden lg:block basis-1/5 shrink">
            <div class="p-4 bg-white">
                <div class="text-lg font-bold">Navigation</div>
                <div class="h-px w-1/3 my-2 bg-gray-400"></div>

                <ul class="list-none leading-relaxed ml-2">
                    <li>
                        <a class="font-semibold" href="{{ route('news.submit') }}">Submit article</a>
                    </li>
                    <li class="italic">My articles (coming soon)</li>
                </ul>
            </div>

            <div class="p-4 bg-white">
                <div class="text-lg font-bold">Filters</div>
                @if($active_filter)
                    <div class="p-2 bg-gray-300">
                        <div>
                            <span class="capitalize">{{ $active_filter }}</span>
                        </div>
                        <div class="inline-block mt-6 px-4 py-2 bg-skin-fill dark:bg-skin-fill-dark text-white cursor-pointer" wire:click="resetFilters()">
                            Remove filter
                        </div>
                    </div>
                @endif
                <div class="h-px w-1/3 my-2 bg-gray-400"></div>

                <div class="font-bold mt-2">Type:</div>
                <div class="mt-2 grid grid-cols-3 gap-2 text-center">
                    @foreach($article_categories as $category)
                    <div wire:click="filterByType('{{ $category }}')" class="bg-skin-fill dark:bg-skin-fill-dark text-white px-2 py-1 font-bold capitalize rounded-xl cursor-pointer">
                        {{ $category }}
                    </div>
                    @endforeach
                </div>

                <div class="font-bold mt-2">Agency:</div>
                <div class="mt-2 grid grid-cols-3 gap-2 text-center">
                    @foreach ($agencies as $agency)
                        <div wire:click="filterByAgency('{{ $agency->name }}')" class="border rounded-xl p-2 font-bold rounded-xl cursor-pointer">
                            {{ $agency->name }} ({{ $agency->articles_count }})
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="grow p-4 mx-auto bg-white w-full lg:w-3/5">

            <!-- Newspaper name -->
            <div class="py-4 text-center font-bold font-serif text-6xl">
                <h1>Cursed Times</h1>
            </div>

            <!-- Articles - Reports -->
            <div class="grow my-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($articles as $article)
                    <div class="w-full mx-auto max-w-96 border border-gray-400 flex flex-col">
                        <a class="flex flex-col block w-full h-full" href="{{ route('news.article', ['id' => $article->id, 'slug' => $article->slug]) }}">
                            <div class="grow relative border-2 border-black">
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
                @empty
                    <div class="col-span-3 text-center">
                        <div class="p-4 bg-gray-300">
                            <div class="text-xl italic">
                                No articles found..
                            </div>
                            @if($active_filter)
                                <div wire:click="resetFilters()" class="inline-block py-2 px-4 mt-2 font-bold text-white bg-skin-fill dark:bg-skin-fill-dark">
                                    Remove active filters
                                </div>
                            @endif
                        </div>
                    </div>
                @endforelse      
            </div>
        </div>

        <div class="hidden lg:block basis-1/5 shrink">
            <div class="grid grid-cols-1 gap-2 w-96">
                @foreach($ads as $ad)
                <div class="w-full bg-white mx-auto max-w-96 border border-gray-400 flex flex-col">
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
