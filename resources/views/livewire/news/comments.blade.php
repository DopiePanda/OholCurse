<div class="p-4 w-full bg-gray-300 border border-gray-400">

    @auth
    <!-- Leave a new comment -->
    <div>
        <div class="text-2xl">Leave a comment!</div>
        <div class="mt-2">
            <textarea class="w-full rounded-lg" wire:model="user_comment" id="" cols="30" rows="6" placeholder="Write your comment here"></textarea>
        </div>
        <div class="">
            <button type="button" class="p-2 text-white bg-skin-fill dark:bg-skin-fill-dark">Submit comment</button>
        </div>
    </div>

    @endauth

    @guest
        <div class="text-center text-2xl">Login to post a comment!</div>
    @endguest

    <div class="my-8 mx-auto h-1 w-2/3 border-b border-gray-600"></div>


    <!-- User submitted article comments -->
    @forelse ($comments as $comment)
    <div class="p-2 pl-4 bg-gray-200 rounded-lg border border-gray-600">
        <div class="flex flex-col"> 
            <div class="w-1/5 font-bold">
                {{ $comment->user->username }}
            </div>
            <div>
                {{ $comment->comment }}
            </div>
            <div class="mt-2 text-sm italic">
                {{ $comment->created_at }}
            </div>
        </div>
        @forelse ($comment->replies as $reply)
            <div class="mt-2 p-2 pl-4 bg-white ml-2 flex flex-col rounded-lg border border-gray-600"> 
                <div class="shrink font-bold">
                    {{ $reply->user->username }}
                </div>
                <div class="grow">
                    {{ $reply->comment }}
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
