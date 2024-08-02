<div class="max-w-7xl px-4 dark:bg-slate-800 py-8">
    <div class="mt-4 text-skin-base dark:text-skin-base-dark text-center text-2xl">Find messages by {{ $leaderboard->leaderboard_name ?? 'UNKNOWN' }}</div>
    <div class="text-skin-muted text-center text-md text-gray-400 dark:text-white">Enter word or phrase below to find matches</div>
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
        <div class="text-gray-600 dark:text-gray-200 text-center text-xl">Chat messages by {{ $leaderboard->leaderboard_name ?? $hash }}</div>
        <div class="h-1 w-1/3 my-2 mx-auto border-b border-skin-muted dark:border-skin-muted-dark"></div>
        <div class="relative my-6 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl overflow-x-auto">
            <table class="overflow-x-auto w-full table-fixed text-left">
                <thead>
                    <tr class="text-left">
                        <th class="w-1/3 text-center p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                            Date
                        </th>
                        <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                            Character name
                        </th>
                        <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                            Message
                        </th>
                    </tr>
                </thead>
                <tbody class="p-2">
                    @foreach ($matches as $match)
                        @if($match->message[0] != ':')
                            <tr class="text-left text-white">
                                <td class="p-2 @if(!$loop->last) border-b @endif border-gray-400 dark:border-gray-800">
                                    {{ date('Y-m-d H:i', $match->timestamp) }}
                                </td>
                                <td class="p-2 @if(!$loop->last) border-b @endif border-gray-400 dark:border-gray-800">
                                    {{ $match->life_name ?? "UNNAMED"  }}
                                </td>
                                <td class="p-2 @if(!$loop->last) border-b @endif border-gray-400 dark:border-gray-800">
                                    {{ $match->message }}
                                </td>                           
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="text-center mt-4 text-skin-base dark:text-skin-base-dark text-2xl">Latest messages</div>
    @if(count($recent_messages) > 0)   
        <div class="relative my-6 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border border-skin-base dark:border-skin-base-dark rounded-xl overflow-x-auto">
            <table class="overflow-x-auto w-full table-fixed text-left">
                <thead>
                    <tr class="text-left">
                        <th class="w-1/3 text-center p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                            Date
                        </th>
                        <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                            Character name
                        </th>
                        <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                            Message
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent_messages as $message)
                        <tr class="text-white">
                            <td class="p-2 text-center @if(!$loop->last) border-b @endif border-gray-400 dark:border-gray-800">
                                {{ date('Y-m-d H:i:s', $message->timestamp) }}
                            </td>
                            <td class="p-2 @if(!$loop->last) border-b @endif border-gray-400 dark:border-gray-800">
                                {{ $message->life_name }}
                            </td>
                            <td class="p-2 @if(!$loop->last) border-b @endif border-gray-400 dark:border-gray-800">
                                {{ $message->message }}
                            </td>
                        </tr>
                    @empty
                        
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $recent_messages->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    @else
        <div class="text-gray-400 dark:text-white text-center">No recent messages found..</div>
    @endif
</div>