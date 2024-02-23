<div class="min-h-[70vh] max-h-[90vh] flex flex-col pb-6 text-skin-muted dark:text-skin-muted-dark bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
    <div class="skrink w-full px-4 py-6 bg-skin-fill-muted dark:bg-skin-fill-muted-dark flex flex-col md:flex-row text-center">
        <div wire:click="setTab('friends')" class="mt-1 mx-2 grow cursor-pointer">
            <div class="py-2 rounded-lg @if($tab == 'friends') bg-skin-fill dark:bg-skin-fill-dark text-white @else bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark border border-skin-muted dark:border-skin-muted-dark @endif">
                Friends
            </div>
        </div>
        <div wire:click="setTab('requests')" class="mt-1 mx-2 grow flex flex-row justify-center items-center cursor-pointer">
            <div class="w-full py-2 rounded-lg @if($tab == 'requests') bg-skin-fill dark:bg-skin-fill-dark text-white @else bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark border border-skin-muted dark:border-skin-muted-dark @endif">
                Requests

                @if (count($requests_in) > 0)
                    <div class="inline-block ml-2 w-6 h-6 rounded-full @if($tab == 'requests') bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark text-white @else text-white bg-skin-fill dark:bg-skin-fill-dark @endif">
                        {{ (count($requests_in)) }}
                    </div>
                @endif
            </div>
        </div>
        <div wire:click="setTab('blocked')" class="mt-1 mx-2 grow cursor-pointer">
            <div class="py-2 rounded-lg @if($tab == 'blocked') bg-skin-fill dark:bg-skin-fill-dark text-white @else bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark border border-skin-muted dark:border-skin-muted-dark @endif">
                Blocked
            </div>
        </div>
    </div>

    <div class="relative overflow-y-auto">

        <!-- Send new friend invite -->
        @if($tab == 'friends' || $tab == 'requests')
        <div class="shrink w-full md:w-2/3 px-2 mx-auto mt-4">
            <div class="my-4 p-4 rounded-lg text-left bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                <div class="text-skin-inverted dark:text-skin-inverted-dark mb-2">Add new friend:</div>
                <div class="flex flex-col md:flex-row gap-2 text-left items-center">
                    <div class="grow w-full md:w-auto">
                        <input tabindex="-1" id="friendName" wire:model="new_friend" wire:keyup.enter="createRequest()" class="block w-full rounded-lg" type="text" placeholder="Enter OHOLCurse username.." />
                    </div>
                    <div class="shrink w-full md:w-auto">
                        <button tabindex="-1" wire:click="createRequest()" class="w-full py-2 px-4 rounded-lg text-white bg-skin-fill dark:bg-skin-fill-dark" type="submit">
                            Send request
                        </button>
                    </div>
                </div>
                @error('new_friend')
                    <div class="mt-1 text-sm text-left text-red-500 italic">
                        {{ $message }}
                    </div> 
                @enderror
            </div>
        </div>
        @endif

        @if($tab == 'friends')
        <!-- Friends list -->
        <div class="grow w-full md:w-2/3 px-2 mx-auto py-4 text-left">
            <div class="p-4 rounded-lg text-skin-inverted dark:text-skin-inverted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                <div class="mt-2 text-3xl text-center">
                    My friends
                </div>
                <div class="mt-4 w-full flex flex-col rounded-xl">
                    <table class="rounded-xl">
                        <thead>
                            <tr>
                                <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                                    User
                                </th>
                                <th class="p-2 text-right bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($friends as $friend)
                                <tr class="rounded-lg bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark border border-skin-base dark:border-skin-base-dark">
                                    <td class="p-2">
                                        @if($friend->reciever->username != $user->username)
                                            {{ $friend->reciever->username }}
                                        @else
                                            {{ $friend->sender->username }}
                                        @endif
                                    </td>
                                    <td class="p-2 text-right">
                                        <i wire:click="openChat('{{ $friend->reciever_id }}')" class="px-1 md:px-2 fa-regular fa-envelope text-sm md:text-xl text-skin-inverted dark:text-skin-inverted-dark cursor-pointer" title="Message friend"></i>
                                        <i wire:click="removeFriend('{{ $friend->reciever_id }}')" wire:confirm="Are you sure you want to remove this friend?" class="px-1 md:px-2 fa-regular fa-trash-can text-sm md:text-xl text-orange-500 cursor-pointer" title="Remove friend"></i>
                                        <i wire:click="blockUser('{{ $friend->reciever_id }}')" wire:confirm="Are you sure you want to block this user?" class="px-1 md:px-2 fa-solid fa-user-slash text-sm md:text-xl text-red-500 cursor-pointer" title="Remove and block"></i>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="p-2 italic text-center border border-skin-base dark:border-skin-base-dark" colspan="2">
                                        You don't have any friends added yet..
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if($tab == 'requests')
        <!-- Requests -->
        <div class="grow w-full md:w-2/3 px-2 mx-auto py-4 rounded-lg text-left">
            <div class="grid grid-cols-1 gap-4 text-skin-inverted dark:text-skin-inverted-dark">
                <div class="p-4 rounded-lg bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                    <div class="mt-2 text-3xl text-center">
                        Friend requests recieved
                    </div>
                    <div class="mt-4 w-full flex flex-col bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
                        <table class="rounded-xl">
                            <thead>
                                <tr>
                                    <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                                        User
                                    </th>
                                    <th class="p-2 text-right bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests_in as $req)
                                    <tr class="rounded-lg bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark border border-skin-base dark:border-skin-base-dark">
                                        <td class="p-2">
                                            {{ $req->sender->username }}
                                        </td>
                                        <td class="p-2 text-right">
                                            <i wire:click="acceptRequest('{{ $req->sender_id }}')" class="px-1 md:px-2 fa-regular fa-circle-check text-sm md:text-xl text-green-500 cursor-pointer" title="Accept request"></i>
                                            <i wire:click="rejectRequest('{{ $req->sender_id }}')" class="px-1 md:px-2 fa-regular fa-circle-xmark text-sm md:text-xl text-red-500 cursor-pointer" title="Reject request"></i>
                                            <i wire:click="blockUser('{{ $req->sender_id }}')" class="px-1 md:px-2 fa-solid fa-user-slash text-sm md:text-xl text-red-500 cursor-pointer" title="Block user"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="p-2 border border-skin-base dark:border-skin-base-dark">
                                        <td class="p-2 italic text-center" colspan="2">
                                            No incoming friend requests..
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-4 my-4 rounded-lg bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                    <div class="mt-2 text-3xl text-center">
                        Friend requests sent
                    </div>
                    <div class="mt-4 w-full flex flex-col bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
                        <table class="rounded-xl">
                            <thead>
                                <tr>
                                    <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                                        User
                                    </th>
                                    <th class="p-2 text-right bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests_out as $req)
                                    <tr class="rounded-lg bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark border border-skin-base dark:border-skin-base-dark">
                                        <td class="p-2">
                                            {{ $req->reciever->username }}
                                        </td>
                                        <td class="p-2 text-right">
                                            <i wire:click="cancelRequest('{{ $req->reciever_id }}')" class="fa-regular fa-circle-xmark text-sm md:text-xl text-red-500"></i>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="p-2">
                                        <td class="p-2 italic text-center border border-skin-base dark:border-skin-base-dark" colspan="2">
                                            No outgoing friend requests..
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($tab == 'blocked')
        <!-- Blocked -->
        <div class="grow w-full md:w-2/3 px-2 mx-auto flex flex-col justify-start md:justify-center">
            <div class="my-4 p-4 rounded-lg text-skin-inverted dark:text-skin-inverted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                <div class="mt-2 text-3xl text-center">
                    Users you have blocked
                </div>
                <div class="mt-4 w-full flex flex-col rounded-xl bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
                    <table class="rounded-xl">
                        <thead>
                            <tr>
                                <th class="p-2 bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                                    User
                                </th>
                                <th class="p-2 text-right bg-skin-fill dark:bg-skin-fill-dark text-white border-b border-gray-600">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blocked as $user)
                                <tr class="rounded-lg bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark border border-skin-base dark:border-skin-base-dark">
                                    <td class="p-2">
                                        {{ $user->reciever->username }}
                                    </td>
                                    <td class="p-2 text-right">
                                        <i wire:click="unblockUser('{{ $req->reciever_id }}')" class="px-1 fa-solid fa-unlock text-sm md:text-xl text-red-500 cursor-pointer" title="Block user"></i>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="p-2 italic text-center border border-skin-base dark:border-skin-base-dark" colspan="2">
                                        No users on your block list
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
