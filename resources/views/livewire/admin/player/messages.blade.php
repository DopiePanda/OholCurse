<div class="dark:bg-slate-800 py-8">
    <div class="mt-4 text-skin-base dark:text-skin-base-dark text-center text-2xl">Find messages by {{ $leaderboard->leaderboard_name ?? 'UNKNOWN' }}</div>
    <div class="text-skin-muted text-center text-md">Enter word or phrase below to find matches</div>
    <form wire:submit="search">
        <div class="w-full lg:w-1/3 px-2 mt-4 mx-auto my-2 text-center">
            <div class="text-left font-bold text-skin-base dark:text-skin-base-dark">
                <label for="query">Word/Phrase:</label>
            </div>
            <div class="mt-2">
                <input class="w-full" type="text" wire:model="query" id="query" placeholder="Enter search phrase" />
            </div>
            @error('query')
                <div class="mt-1 text-sm text-left text-red-500 italic">
                    {{ $message }}
                </div> 
            @enderror
        </div>
        <div class="w-full lg:w-1/3 px-2 mx-auto mt-4 mb-4 text-center">
            <div>
                <button class="w-full p-2 text-white bg-skin-fill dark:bg-skin-fill-dark" type="button" wire:click="search">Search</button>
            </div>
        </div>
    </form>

    <div wire:loading class="w-full mx-auto text-center mt-12 text-primary-500">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        <div class="text-skin-muted dark:text-skin-muted-dark">Searching through player messages</div>
    </div>

    @if($matches && count($matches) > 0)
        <div class="w-5/6 mx-auto mt-4 p-4 text-white bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
            <div class="text-gray-600 dark:text-gray-200 text-center text-xl">Chat messages by {{ $leaderboard->leaderboard_name ?? $hash }}</div>
            <div class="h-1 w-1/3 my-2 mx-auto border-b border-skin-muted dark:border-skin-muted-dark"></div>
            <div class="relative overflow-x-auto">
                <table class="w-full mt-4 mx-auto">
                    <thead class="p-2">
                        <tr class="p-2">
                            <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Date</td>
                            <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Character name</td>
                            <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Message</td>           
                        </tr>
                    </thead>
                    <tbody class="p-2">
                        @foreach ($matches as $match)
                            @if($match->message[0] != ':')
                                <tr class="even:bg-gray-300 odd:bg-white text-gray-600 dark:even:bg-slate-700 dark:odd:bg-slate-800 dark:text-gray-300">
                                    <td class="p-2 border border-gray-400">
                                        {{ date('Y-m-d H:i', $match->timestamp) }}
                                    </td>
                                    <td class="p-2 border border-gray-400">
                                        {{ $match->life_name ?? "UNNAMED"  }}
                                    </td>
                                    <td class="p-2 border border-gray-400">
                                        {{ $match->message }}
                                    </td>                           
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>