<div class="fixed bottom-0 right-0 flex flex-col items-end">
    @auth
    <div id="conversationsWrapper" class="hidden">
        <!-- Chat wrapper -->
        <div class="fixed flex flex-col text-left bottom-0 pb-16 right-0 w-96 h-5/6 border border-skin-base dark:border-skin-base-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
            
            <!-- Chat navigation / settings -->
            <div id="conversationHeader" class="z-0 flex flex-row p-4 border border-skin-base dark:border-skin-base-dark bg-skin-fill dark:bg-skin-fill-dark">
                <div id="inboxHeaderAvatar" class="z-30 shrink self-center h-10 w-10 text-center rounded-full bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                    <div class="z-30 mt-1 text-2xl font-black text-white">D</div>
                </div>
                <div id="inboxHeaderUsername" class="z-30 grow self-center pl-4 text-md text-white">
                    <div class="z-30">{{ Auth::user()->username }}</div>
                </div>
                <div id="inboxHeaderNav" class="z-30 shrink self-center text-right pl-4 text-lg">
                    <div class="z-30 text-white">
                        <i class="fa-solid fa-users"></i>
                        <i class="ml-2 fa-solid fa-gear"></i>
                    </div>
                </div>
            </div>

            @if(!$conversation)
                <!-- Search -->
                <div class="flex flex-row p-4 border-b border-skin-base dark:border-skin-base-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                    <div class="grow">
                        <input type="text" class="w-full rounded-lg" placeholder="Search conversations">
                    </div>
                </div>

                <!-- Friends -->
                <div>
                    @forelse ($conversations as $conversation)
                        <div wire:key="conversation-{{ $conversation->id }}" class="flex flex-row p-4 border-b border-skin-base dark:border-skin-base-dark bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
                            <div class="self-center h-12 w-12 min-w-12 text-center rounded-full bg-skin-fill dark:bg-skin-fill-dark">
                                <div class="w-full mt-2 text-2xl font-black text-white uppercase">
                                    @if($conversation->sender->username != Auth::user()->username)
                                        {{ $conversation->sender->username[0] }}
                                    @else
                                        {{ $conversation->reciever->username[0] }}
                                    @endif
                                </div>
                            </div>
                            <div class="grow self-center pl-4 text-md text-white">
                                <div class="text-md">
                                    @if($conversation->sender->username != Auth::user()->username)
                                        {{ $conversation->sender->username }}
                                    @else
                                        {{ $conversation->reciever->username }}
                                    @endif
                                </div>
                                <div class="mt-1 text-xs text-gray-400 italics">
                                    {{ $conversation->messages->first()->sender->username }}: {{ $conversation->messages->first()->message }}
                                </div>
                            </div>
                            <div class="shrink self-center text-right pl-4 text-lg text-skin-base dark:text-skin-base-dark">
                                <div class="">
                                    @if($conversation->messages->first()->read == 0)
                                        <i class="fa-solid fa-envelope text-skin-base dark:text-skin-base-dark"></i>
                                    @else
                                        <i class="fa-solid fa-envelope text-skin-muted dark:text-skin-muted-dark"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 italic">
                            No conversations found..
                        </div>
                    @endforelse
                </div>
            @else
                <div>{{ $conversation->sender->username }}</div>
            @endif
        </div>
    </div>
    <!-- Chat menu -->
    <div class="z-30 pb-4 pr-4 grow border-gray-200">
        <i id="conversationToggle" class="fa-solid fa-comments fa-2x text-skin-base dark:text-skin-base-dark"></i>
    </div>
    
    @endauth
</div>
