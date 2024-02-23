<div class="fixed bottom-0 right-0 flex flex-col items-end">
    @auth
    @if($show_chat == true)
        <div id="conversationsWrapper">
            <!-- Chat wrapper -->
            <div class="w-screen sm:w-96 md:w-96 h-5/6 fixed flex flex-col w-full text-left left-0 right-auto sm:right-0 sm:left-auto bottom-0 pb-16 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
                
                <!-- Chat navigation / settings -->
                <div id="conversationHeader" class="z-0 flex flex-row p-4 border border-skin-base dark:border-skin-base-dark bg-skin-fill dark:bg-skin-fill-dark">
                    <div id="inboxHeaderAvatar" class="z-30 shrink self-center h-10 w-10 text-center rounded-full bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                        <div class="z-30 mt-1 text-2xl font-black text-white">{{ $user->username[0] }}</div>
                    </div>
                    <div id="inboxHeaderUsername" class="z-30 grow self-center pl-4 text-md text-white">
                        <div class="z-30">{{ $user->username }}</div>
                    </div>
                    <div id="inboxHeaderNav" class="z-30 shrink self-center text-right pl-4 text-lg">
                        <div class="z-30 text-white">
                            <div class="flex flex-row">
                                <div class="flex flex-row cursor-pointer" wire:click="$dispatch('openModal', {component: 'conversations.manage-friends'})">
                                    @if($friend_requests > 0)
                                        <div class="h-4 w-4 rounded-full text-xs bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark text-white">
                                            <div class="mr-1">{{ $friend_requests }}</div>
                                        </div>
                                    @endif
                                    <div>
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                </div>
                                <div><i class="ml-2 fa-solid fa-gear cursor-pointer"></i></div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                @if(!$selected_conversation && $tab == 'inbox')
                    <!-- Search -->
                    <div class="flex flex-row gap-2 p-4 border-b border-skin-muted dark:border-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                        <div class="shrink self-center">
                            <i wire:click="newConversation()" class="p-2 bg-skin-fill dark:bg-skin-fill-dark rounded-full fa-solid fa-plus text-white cursor-pointer"></i>
                        </div>
                        <div class="grow">
                            <input wire:model="search" wire:keyup.debounce.200ms="searchConversations()" type="text" class="w-full rounded-lg" placeholder="Search conversations">
                        </div>
                    </div>

                    <!-- Conversations -->
                    <div class="relative overflow-y-auto">
                        @forelse ($conversations as $conversation)
                            @if(count($conversation->messages) > 0)
                                <div wire:click="openConversation({{ $conversation->id }})" wire:key="conversation-{{ $conversation->id }}" class="flex flex-row p-4 border-b border-skin-muted dark:border-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark hover:bg-skin-fill-wrapper dark:hover:bg-skin-fill-wrapper-dark cursor-pointer">
                                    <div class="self-center h-12 w-12 min-w-12 text-center rounded-full bg-skin-fill dark:bg-skin-fill-dark">
                                        <div class="w-full mt-2 text-2xl font-black text-white uppercase">
                                            @if($conversation->sender->username != $user->username)
                                                {{ $conversation->sender->username[0] }}
                                            @else
                                                {{ $conversation->reciever->username[0] }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="max-w-64 grow self-center pl-4 text-md text-white">
                                        <div class="text-md text-gray-800 dark:text-white">
                                            @if($conversation->sender->username != $user->username)
                                                {{ $conversation->sender->username }}
                                            @else
                                                {{ $conversation->reciever->username }}
                                            @endif
                                        </div>
                                        <div class="mt-1 text-xs text-skin-muted dark:text-gray-400 italic break-words">
                                            @if(count($conversation->messages) > 0)
                                                @if($conversation->messages->first()->sender->username == $user->username)
                                                    You: 
                                                @else
                                                    {{ $conversation->messages->first()->sender->username }}:
                                                @endif 
                                                {{ Str::of($conversation->messages->first()->message)->limit(24, ' ...') }}
                                            @else
                                                <span class="italic">No messages</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="shrink self-center text-right pl-4 text-lg text-skin-base dark:text-skin-base-dark">
                                        <div class="">
                                            @if(count($conversation->messages) > 0 && $conversation->messages->first()->read == 0 && $conversation->messages->first()->sender_id != Auth::id())
                                                <i class="fa-solid fa-envelope text-skin-base dark:text-skin-base-dark"></i>
                                            @else
                                                <i class="fa-solid fa-envelope-open text-skin-muted dark:text-skin-muted-dark"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="w-5/6 mx-auto mt-4 px-6 py-4 text-center bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                                <div class="text-center font-bold text-skin-base dark:text-skin-base-dark">
                                    No conversations found..
                                </div>
                                <button wire:click="newConversation()" class="text-center mt-4 px-4 py-2 rounded-lg bg-skin-fill dark:bg-skin-fill text-white">
                                    Start new conversation
                                </button>
                            </div>
                        @endforelse
                    </div>
                @elseif($selected_conversation && $tab == 'conversation')
                    <div class="flex flex-row items-center justify-center p-4 bg-skin-fill-muted dark:bg-skin-fill-muted-dark border-b border-skin-muted dark:border-skin-muted-dark">
                        <div wire:click="closeConversation()" class="shrink pr-4 cursor-pointer">
                            <i class="fa-solid fa-circle-arrow-left fa-2x text-skin-base dark:text-skin-base-dark"></i>
                        </div>
                        <div class="grow">
                            <div class="ml-2 text-lg text-gray-800 dark:text-white">
                                @if($selected_conversation->sender->username != $user->username)
                                    {{ $selected_conversation->sender->username }}
                                @else
                                    {{ $selected_conversation->reciever->username }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="relative pb-8 overflow-y-auto" id="chatHistory">
                        <div class="px-4 flex flex-col">
                            @foreach($selected_conversation->messages->sortBy('id') as $msg)
                                <div wire:key="msg-{{ $msg->id }}" class="mt-3 p-3 shadow-md rounded-lg break-words @if($msg->sender->username != $user->username) ml-8 bg-skin-fill dark:bg-skin-fill-dark text-white @else mr-8 bg-skin-fill-muted dark:bg-skin-fill-muted-dark text-skin-muted dark:text-white @endif">
                                    <div class="font-bold">
                                        @if($msg->sender_id != $user->id)
                                            <div class="text-white">{{ $msg->sender->username }}:</div>
                                        @else
                                            <div class="text-skin-inverted dark:text-skin-inverted-dark">You:</div>
                                        @endif
                                    </div>
                                    <div>{!! nl2br($msg->message) !!}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                @elseif($tab == 'new-conversation')
                    <div class="flex flex-row items-center justify-center p-4 border-b border-skin-muted dark:border-skin-muted-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                        <div wire:click="closeConversation()" class="shrink pr-4 cursor-pointer">
                            <i class="fa-solid fa-circle-arrow-left fa-2x text-skin-base dark:text-skin-base-dark"></i>
                        </div>
                        <div class="grow text-skin-inverted dark:text-skin-inverted-dark text-lg">
                            Start a conversation
                        </div>
                    </div>
                    
                    <div class="w-5/6 mx-auto mt-4 px-6 py-4 bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                        @if(count($friends) > 0)
                            <div>
                                <label class="text-left text-skin-base dark:text-skin-base-dark" for="friends_select">Who do you want to message?</label>
                                <select wire:model="selected_friend" id="friends_select" class="block mt-2 w-full rounded-lg">
                                    <option value="">Select friend</option>
                                    @forelse ($friends as $friend)
                                        <option value="{{ $friend->reciever_id }}" wire:key="friend-{{ $friend->reciever_id }}">
                                            {{ $friend->reciever->username }}
                                        </option>
                                    @empty
                                    <option value="" disabled>NO FRIENDS</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="mt-6">
                                <button wire:click="startConversation" class="w-full py-2 px-4 bg-skin-fill dark:bg-skin-fill-dark text-white">
                                    Start conversation
                                </button>
                            </div>

                            @error('selected_friend')
                                <div class="mt-1 text-sm text-left text-red-500 italic">
                                    {{ $message }}
                                </div> 
                            @enderror
                        @else
                            <div class="text-center text-skin-base dark:text-skin-base-dark">
                                It seems like you don't have any friends added yet
                            </div>
                            <div class="mt-6">
                                <button wire:click="$dispatch('openModal', {component: 'conversations.manage-friends'})" class="w-full py-2 px-4 bg-skin-fill dark:bg-skin-fill-dark text-white">
                                    Add friends
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif
    <!-- Chat menu -->
        <div class="w-screen sm:w-96 z-50 py-4 px-4 flex flex-row gap-4 grow border-gray-200 justify-between align-center items-center @if($selected_conversation !== null) bg-skin-fill-muted dark:bg-skin-fill-muted-dark border-t border-skin-muted dark:border-skin-muted-dark @endif">
            <div class="grow -mb-2 h-full">
                @if($selected_conversation !== null)
                    <textarea id="chatReply" wire:model="message" wire:keydown.enter="$dispatch('process-message')" class="w-full rounded-lg" placeholder="Enter your message here" rows="1"></textarea>
                @endif
            </div>
            <div class="shrink cursor-pointer">
                @if($show_load_time == 1)
                    <span class="text-skin-base dark:text-white">
                        {{ round(microtime(true) - $time_start, 4) }}s
                    </span>
                @endif
                <i wire:click="toggleChat()" id="conversationToggle" class="fa-solid fa-comments fa-2x text-skin-base dark:text-skin-base-dark cu"></i>
            </div>
        </div>

         @script
        <script>
            $wire.on('conversation-opened', () => {
                setTimeout(function() {
                    var chatHistory = document.getElementById("chatHistory");
                    chatHistory.scrollTop = chatHistory.scrollHeight;
                    $("#chatReply").focus();
                }, 5);
            });

            $wire.on('process-message', () => {
                $("#chatReply").keypress(function(event) {
                    if (event.keyCode == 13 && !event.shiftKey) {
                        $wire.dispatchSelf('send-private-message');
                        console.log("Sent");
                        return false;
                    }
                });
            });
        </script>
        @endscript
    @endauth
</div>
