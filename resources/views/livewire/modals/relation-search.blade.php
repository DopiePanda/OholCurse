<div class="dark:bg-slate-800 py-8">
    <div class="mt-4 text-skin-base dark:text-skin-base-dark text-center text-2xl">Find earlier relationships with {{ $origin->leaderboard_name }}</div>
    <div class="text-skin-muted text-center text-md">Enter a leaderboard name below to find and compare previous relationships.</div>
    <form prevent.default="search">
        <div class="w-1/3 mt-4 mx-auto my-2 text-center">
            <div class="text-left font-bold text-skin-base dark:text-skin-base-dark">
                <label for="input">Enter target leaderboard name:</label>
            </div>
            <div class="mt-2">
                <input class="w-full" type="text" wire:model="input" id="input" placeholder="Enter target leaderboard name" />
            </div>
            @error('input')
                <div class="mt-1 text-sm text-left text-red-500 italic">
                    {{ $message }}
                </div> 
            @enderror
        </div>
        <div class="w-1/3 mx-auto mt-4 mb-4 text-center">
            <div><button class="w-full p-2 text-white bg-skin-fill dark:bg-skin-fill-dark" type="button" wire:click="search">Search</button></div>
        </div>
    </form>

    <div wire:loading class="w-full mx-auto text-center mt-12 text-primary-500">
        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        <div class="text-skin-muted dark:text-skin-muted-dark">Searching for previous relationships</div>
    </div>

    @if($origin_was_parent && count($origin_was_parent) > 0)
        <div class="w-5/6 mx-auto mt-4 p-4 text-white bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
            <div class="text-gray-600 dark:text-gray-200 text-center text-xl">Lives where {{ $origin->leaderboard_name }} was {{ $target->leaderboard_name }}'s parent</div>
            <div class="h-1 w-1/3 my-2 mx-auto border-b border-skin-muted dark:border-skin-muted-dark"></div>
            <table class="w-full mt-4 mx-auto">
                <thead class="p-2">
                    <tr class="p-2">
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Date</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Parent leaderboard</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Parent character</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Child character</td>           
                    </tr>
                </thead>
                <tbody class="p-2">
                    @foreach ($origin_was_parent as $result)
                        <tr class="even:bg-gray-300 odd:bg-white text-gray-600 dark:even:bg-slate-700 dark:odd:bg-slate-800 dark:text-gray-300">
                            <td class="p-2 border border-gray-400">
                                {{ date('Y-m-d H:i', $result->timestamp) }}
                            </td>
                            <td class="p-2 border border-gray-400">
                                {{ $result->parent->leaderboard->leaderboard_name ?? 'MISSING' }}
                            </td>
                            <td class="p-2 border border-gray-400">
                                {{ $result->parent->name->name }}
                            </td>
                            <td class="p-2 border border-gray-400">
                                <a class="hover:text-skin-base dark:hover:text-skin-base-dark" href="{{ route('player.curses', ['hash' => $result->player_hash]) }}">
                                    {{ $result->name->name ?? $result->character_id }}
                                </a>
                            </td>                           
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($origin_was_child && count($origin_was_child) > 0)
        <div class="w-5/6 mx-auto mt-4 p-4 text-white bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
            <div class="text-gray-600 dark:text-gray-200 text-center text-xl">Lives where {{ $origin->leaderboard_name }} was {{ $target->leaderboard_name }}'s child</div>
            <div class="h-1 w-1/3 my-2 mx-auto border-b border-skin-muted dark:border-skin-muted-dark"></div>
            <table class="w-full mt-4 mx-auto">
                <thead class="p-2">
                    <tr class="p-2">
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Date</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Parent leaderboard</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Parent character</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Child character</td>           
                    </tr>
                </thead>
                <tbody class="p-2">
                    @foreach ($origin_was_child as $result)
                    <tr class="even:bg-gray-300 odd:bg-white text-gray-600 dark:even:bg-slate-700 dark:odd:bg-slate-800 dark:text-gray-300">
                        <td class="p-2 border border-gray-400">
                            {{ date('Y-m-d H:i', $result->timestamp) }}
                        </td>
                        <td class="p-2 border border-gray-400">
                            <a class="hover:text-skin-base dark:hover:text-skin-base-dark" href="{{ route('player.curses', ['hash' => $result->parent->leaderboard->player_hash]) }}">
                                {{ $result->parent->leaderboard->leaderboard_name ?? 'MISSING' }}
                            </a>
                        </td>
                        <td class="p-2 border border-gray-400">
                            {{ $result->parent->name->name }}
                        </td>
                        <td class="p-2 border border-gray-400">
                            {{ $result->name->name ?? $result->character_id }}
                        </td>                            
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($origin_was_sibling && count($origin_was_sibling) > 0)
        <div class="w-5/6 mx-auto mt-4 p-4 text-white bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
            <div class="text-gray-600 dark:text-gray-200 text-center text-xl">Lives where {{ $origin->leaderboard_name }} was {{ $target->leaderboard_name }}'s sibling</div>
            <div class="h-1 w-1/3 my-2 mx-auto border-b border-skin-muted dark:border-skin-muted-dark"></div>
            <table class="w-full mt-4 mx-auto">
                <thead class="p-2">
                    <tr class="p-2">
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Date</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Parent leaderboard</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Parent character</td>
                        <td class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border border-gray-600">Sibling character</td>           
                    </tr>
                </thead>
                <tbody class="p-2">
                    @foreach ($origin_was_sibling as $result)
                    <tr class="even:bg-gray-300 odd:bg-white text-gray-600 dark:even:bg-slate-700 dark:odd:bg-slate-800 dark:text-gray-300">
                        <td class="p-2 border border-gray-400">
                            {{ date('Y-m-d H:i', $result->timestamp) }}
                        </td>
                        <td class="p-2 border border-gray-400">
                            <a class="hover:text-skin-base dark:hover:text-skin-base-dark" href="{{ route('player.curses', ['hash' => $result->parent->leaderboard->player_hash]) }}">
                                {{ $result->parent->leaderboard->leaderboard_name ?? 'MISSING' }}
                            </a>
                        </td>
                        <td class="p-2 border border-gray-400">
                            {{ $result->parent->name->name }}
                        </td>
                        <td class="p-2 border border-gray-400">
                            <a class="hover:text-skin-base dark:hover:text-skin-base-dark" href="{{ route('player.curses', ['hash' => $result->player_hash]) }}">
                                {{ $result->name->name ?? $result->character_id }}
                            </a>
                        </td>                          
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>