<div>

    @section("page-title", "- Submit a story to the Cursed Times")

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
                <div class="text-sm italic">
                    Type: jpg/png | Min image size: 350x350px | Max file size: 1MB
                </div>
                <div class="text-sm font-bold text-red-500">@error('image') {{ $message }} @enderror</div>

                @if ($image) 
                    <img class="mx-auto" src="{{ $image->temporaryUrl() }}">
                @endif
            </div>

            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="type">Article Type (Required)</label>
                <select wire:model="type" class="w-full" id="type">
                    <option value="report">News Report</option>
                    <option value="life">Life Story</option>
                    <option value="guide">Game Guide</option>
                    <option value="music">Song Recommendation</option>
                </select>
                <div class="text-sm font-bold text-red-500">@error('type') {{ $message }} @enderror</div>
            </div>

            <div class="py-2" x-data="{ titleLen: 0 }" x-init="titleLen = $wire.title.length">
                <label class="block text-md uppercase font-bold" for="title">Headline (Required)</label>
                <input wire:model="title" class="w-full" id="title" type="text" minlength="10" maxlength="70" placeholder="Headline for my news story" x-on:keyup="titleLen = $wire.title.length" />
                <div class="text-sm italic">
                    <span x-html="titleLen" :class="titleLen >= 10 && titleLen <= 70 ? 'text-green-500' : 'text-red-500'"></span>/70
                    <span> | Min. length: 10 chars | Max length: 70 chars</span>
                </div>
                <div class="text-sm font-bold text-red-500">@error('title') {{ $message }} @enderror</div>
            </div>
    
            <div class="py-2" x-data="{ contentLen: 0 }" x-init="contentLen = $wire.content.length">
                <label class="block text-md uppercase font-bold" for="content">Story (Required)</label>
                <textarea wire:model="content" class="w-full" id="content" type="text" minlength="100" maxlength="5000" placeholder="Content for my news story" rows="12" x-on:keyup="contentLen = $wire.content.length"></textarea>
                <div class="text-sm italic">
                    <span x-html="contentLen" :class="contentLen >= 100 && contentLen <= 5000 ? 'text-green-500' : 'text-red-500'"></span>/5000
                    <span> | Min. length: 100 chars | Max length: 5000 chars</span>
                </div>
                <div class="text-sm font-bold text-red-500">@error('content') {{ $message }} @enderror</div>
            </div>

            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="author">Author (Optional)</label>
                <input wire:model="author" class="w-full" id="author" type="text" placeholder="Robbin Hoods" />
                <div class="text-sm italic">
                    Publicly displayed author name, field is optional
                </div>
                <div class="text-sm font-bold text-red-500">@error('author') {{ $message }} @enderror</div>
            </div>

            <div class="py-2">
                <label class="block text-md uppercase font-bold" for="agency">Publisher Agency</label>
                <select wire:model="agency" class="w-full" id="agency">
                    <option value="">None (Independent Journalist)</option>
                    @foreach ($agencies as $agency)
                        <option value="{{ $agency->name }}">{{ $agency->name }} ({{ $agency->tag }})</option>
                    @endforeach
                </select>
                <div class="text-sm italic">
                    Chose an agency to write for, or publish article as an independent journalist
                </div>
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
