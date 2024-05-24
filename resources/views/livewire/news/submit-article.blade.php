<div>
    <div class="min-w-7xl max-w-7xl bg-white rounded p-2 lg:p-8 text-center">
        <div class="text-5xl font-bold">
            Got a story for the Cursed Times?
        </div>
        <div class="my-2 text-xl italic">
            Submit your story for review using the form below
        </div>

        <div class="my-4 mx-auto text-center h-px w-2/3 bg-gray-400"></div>

        <div class="text-left">
            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="image">Article Image (Required)</label>
                <input wire:model="image" class="w-full p-4 border rounded-xl" id="image" type="file" />
                <div class="text-sm font-bold text-red-500">@error('image') {{ $message }} @enderror</div>

                @if ($image) 
                    <img class="mx-auto" src="{{ $image->temporaryUrl() }}">
                @endif
            </div>

            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="title">Headline (Required)</label>
                <input wire:model="title" class="w-full" id="content" type="text" placeholder="Headline for my news story" />
                <div class="text-sm font-bold text-red-500">@error('title') {{ $message }} @enderror</div>
            </div>
    
            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="content">Story (Required)</label>
                <textarea wire:model="content" class="w-full" id="content" type="text" placeholder="Headline for my news story" rows="12"></textarea>
                <div class="text-sm font-bold text-red-500">@error('content') {{ $message }} @enderror</div>
            </div>

            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="author">Author (Optional)</label>
                <input wire:model="author" class="w-full" id="author" type="text" placeholder="Robbin Hoods" />
                <div class="text-sm font-bold text-red-500">@error('author') {{ $message }} @enderror</div>
            </div>

            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="agency">Publisher Agency</label>
                <select wire:model="agency" class="w-full" id="agency">
                    @foreach ($agencies as $agency)
                        <option value="{{ $agency->name }}">{{ $agency->name }} ({{ $agency->tag }})</option>
                    @endforeach
                </select>
                <div class="text-sm font-bold text-red-500">@error('agency') {{ $message }} @enderror</div>
            </div>

            <div wire:click="submit" class="mt-4 py-2 bg-skin-fill dark:bg-skin-fill-dark text-white text-center cursor-pointer">
                Submit story for review
            </div>

            @if($submitted == true)
                <div class="mt-4 p-2 w-full border border-green-500 rounded-xl bg-green-500 text-white text-center">Your story has been submitted for review!</div>
            @endif
        </div>
    </div>
</div>
