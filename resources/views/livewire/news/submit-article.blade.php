<div>
    <div class="min-w-7xl max-w-7xl bg-white rounded px-8 p-4 text-center">
        <div class="text-4xl">
            Have a story for the Cursed Times?
        </div>
        <div class="text-xl italic">
            Submit your story for review using the form below
        </div>

        <div class="h-1 w-2/3 bg-gray-600"></div>

        <div class="text-left">
            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="image">Article Image</label>
                <input wire:model="image" class="w-full p-4 border rounded-xl" id="image" type="file" />
                <div>@error('image') {{ $message }} @enderror</div>
            </div>

            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="title">Headline</label>
                <input wire:model="title" class="w-full" id="content" type="text" placeholder="Headline for my news story" />
                <div>@error('title') {{ $message }} @enderror</div>
            </div>
    
            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="content">Story:</label>
                <textarea wire:model="content" class="w-full" id="content" type="text" placeholder="Headline for my news story" rows="12"></textarea>
                <div>@error('content') {{ $message }} @enderror</div>
            </div>

            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="author">Author</label>
                <input wire:model="author" class="w-full" id="author" type="text" placeholder="Robbin Hoods" />
                <div>@error('author') {{ $message }} @enderror</div>
            </div>

            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="agency">Publisher Agency</label>
                <select wire:model="agency" class="w-full" id="agency">
                    <option>My Agency</option>
                </select>
                <div>@error('agency') {{ $message }} @enderror</div>
            </div>

            <div wire:click="submit" class="py-2 bg-skin-fill dark:bg-skin-fill-dark text-white text-center">
                Submit story for review
            </div>

            @if($submitted == true)
                <div class="mt-2 p-2 w-full border border-green-500 rounded-xl bg-green-500 text-white text-center">Your story has been submitted for review!</div>
            @endif
        </div>
    </div>
</div>
