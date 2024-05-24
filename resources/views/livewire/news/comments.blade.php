<div class="p-4 w-full bg-gray-300 border border-gray-400">

    @auth
    <!-- Leave a new comment -->
    <div>
        <div class="text-2xl">Leave a comment!</div>

        @if($replies_to != null)
            <div class="mt-2 italic">
                Replying to comment ID: #{{ $replies_to }}
            </div>
        @endif
        <div class="mt-2">
            <textarea class="w-full rounded-lg" wire:model.live="user_comment" id="" cols="30" rows="6" placeholder="Write your comment here"></textarea>
            <div>
                @if(strlen($user_comment) <= 250)
                    <div class="text-black">
                @else
                    <div class="text-red-600">
                @endif

                {{ strlen($user_comment) ?? 0 }} / 250
                </div>
            </div>
        </div>
        @error('user_comment')
        <div class="text-red-600 font-bold">
            {{ $message }}
        </div>
        @enderror

        <div class="">
            <button wire:click="postComment()" type="button" class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark">Submit comment</button>
        </div>

        @if($user_comment != null || $replies_to != null)
            <div class="my-3">
                <button wire:click="clearForm()" type="button" class="text-red-600">Clear form</button>
            </div>
        @endif
    </div>

    @endauth

    @guest
        <div class="text-center text-2xl">Login to post a comment!</div>
    @endguest

    <div class="my-8 mx-auto h-1 w-2/3 border-b border-gray-600"></div>


    <!-- User submitted article comments -->
    @forelse ($comments as $comment)
    <div class="my-2 p-2 pl-4 bg-gray-200 rounded-lg border border-gray-600">
        <div class="flex flex-col"> 
            <div class="w-full font-bold">
                <span>{{ $comment->user->username }}</span> -
                <span>(Comment ID: {{ $comment->id }})</span>
                @if(Auth::user())
                    - <span wire:click="setReply({{ $comment->id }})" class="italic cursor-pointer hover:color-skin-base dark:hover:color-skin-base-dark">Reply</span>
                    @if($comment->user_id == Auth::user()->id)
                        - <span wire:click="editComment({{ $comment->id }})" class="italic cursor-pointer text-gray-600">Edit</span>
                        - <span wire:click="deleteComment({{ $comment->id }})" class="italic cursor-pointer text-red-600">Delete</span>
                    @endif
                @endif  
            </div>
            <div>
                @if($edit_comment_id != $comment->id)
                    {{ $comment->comment }}
                @else
                    <div>
                        <textarea class="w-full" wire:model="edit_comment_content"></textarea>
                    </div>
                    <div>
                        <input class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark" type="button" wire:click="saveComment" value="Update comment"/>
                    </div>
                @endif
            </div>
            <div class="mt-2 text-sm italic">
                {{ $comment->created_at }}
            </div>
        </div>
        @forelse ($comment->replies as $reply)
            <div class="mt-2 p-2 pl-4 bg-white ml-2 flex flex-col rounded-lg border border-gray-600"> 
                <div class="shrink font-bold">
                    <span>{{ $reply->user->username }}</span>
                    @if(Auth::user())
                        @if($reply->user_id == Auth::user()->id)
                            - <span wire:click="editComment({{ $reply->id }})" class="italic cursor-pointer text-gray-600">Edit</span>
                            - <span wire:click="deleteComment({{ $reply->id }})" class="italic cursor-pointer text-red-600">Delete</span>
                        @endif
                    @endif
                </div>
                <div class="grow">
                    @if($edit_comment_id != $reply->id)
                        {{ $reply->comment }}
                    @else
                        <div>
                            <textarea class="w-full" wire:model="edit_comment_content"></textarea>
                        </div>
                        <div>
                            <input class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark" type="button" wire:click="saveComment" value="Update comment"/>
                        </div>
                    @endif
                </div>
                <div class="mt-2 text-sm italic">
                    {{ $reply->created_at }}
                </div>     
            </div>
        @empty
            
        @endforelse
    </div>
    @empty
        <div class="text-center italic">No comments yet. Be the first one!</div>
    @endforelse
</div>
