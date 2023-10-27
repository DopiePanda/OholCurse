<div class="p-6">
    <form wire:submit.prevent="save" enctype="multipart/form-data">
        <div>
            <div class="text-2xl text-center font-bold">Add a new leaderboard</div>
        </div>

        <div class="my-6">
            <div class="py-2 text-gray-800 text-sm uppercase font-bold">Multi objects?</div>
            
            <div class="p-4 border border-blue-400 rounded-lg">
                <input type="checkbox" wire:model="multi" />
            </div>

            <div class="mt-1 text-red-400 italic text-sm font-bold">
                @error('multi') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        @if($multi == 0)

            <div class="my-6">
                <div class="py-2 text-gray-800 text-sm uppercase font-bold">Select Game Object</div>

                <div class="p-4 border border-blue-400 rounded-lg">
                    <div>
                        <select wire:model="object_id" class="w-full">
                            @foreach ($all_objects as $object)
                                <option value="{{ $object->id }}">{{ $object->id.' - '.$object->name }}</option>
                            @endforeach
                            
                        </select>
                    </div>
                </div>

                <div class="mt-1 text-red-400 italic text-sm font-bold">
                    @error('object_id') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>

        @else

            <div class="my-6">
                <div class="py-2 text-gray-800 text-sm uppercase font-bold">Select Multiple Objects</div>

                <div class="p-4 border border-blue-400 rounded-lg">
                    <div>
                        <select wire:model="multi_objects" class="w-full" multiple>
                            @foreach ($all_objects as $object)
                                <option value="{{ $object->id }}">{{ $object->id.' - '.$object->name }}</option>
                            @endforeach
                            
                        </select>
                    </div>
                </div>

                <div class="mt-1 text-red-400 italic text-sm font-bold">
                    @error('multi_objects') <span class="error">{{ $message }}</span> @enderror
                    @error('object_id') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>

        @endif

        <div class="my-6">
            <div class="py-2 text-gray-800 text-sm uppercase font-bold">Choose an image</div>
            <div class="p-4 border border-blue-400 rounded-lg">
                <div><input wire:model="image_url" type="text" class="w-full" placeholder="Paste image URL"></div>
                <div class="my-2 text-center uppercase italic">or</div>
                <div class="text-center">
                    <input type="file" wire:model="image_file" class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-xs font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary">
                    <div wire:loading wire:target="image_file">Uploading...</div>
                </div>
            </div>
            <div class="mt-1 text-red-400 italic text-sm font-bold">
                @error('image_url') <span class="error">{{ $message }}</span> @enderror
                @error('image_file') <span class="error">{{ $message }}</span> @enderror
            </div>
         
        </div>

        <div class="my-6">
            <div class="py-2 text-gray-800 text-sm uppercase font-bold">Label for leaderboard</div>
            
            <div class="p-4 border border-blue-400 rounded-lg">
                <input class="w-full" type="text" wire:model="label" />
            </div>

            <div class="mt-1 text-red-400 italic text-sm font-bold">
                @error('label') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="my-6">
            <div class="py-2 text-gray-800 text-sm uppercase font-bold">Custom title</div>
            
            <div class="p-4 border border-blue-400 rounded-lg">
                <input class="w-full" type="text" wire:model="page_title" />
            </div>

            <div class="mt-1 text-red-400 italic text-sm font-bold">
                @error('page_title') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="my-6">
            <div class="py-2 text-gray-800 text-sm uppercase font-bold">Amount of players shown</div>
            
            <div class="p-4 border border-blue-400 rounded-lg">
                <select wire:model="limit" class="w-full">
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            <div class="mt-1 text-red-400 italic text-sm font-bold">
                @error('limit') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="my-6">
            <div class="py-2 text-gray-800 text-sm uppercase font-bold">Enabled on save</div>
            
            <div class="p-4 border border-blue-400 rounded-lg">
                <input type="checkbox" wire:model="enabled" />
            </div>

            <div class="mt-1 text-red-400 italic text-sm font-bold">
                @error('enabled') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <button class="mt-4 p-2 w-full bg-blue-400 text-white" type="submit">Create leaderboard</button>

    </form>
</div>
