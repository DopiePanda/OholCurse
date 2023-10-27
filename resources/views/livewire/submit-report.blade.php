<div>
    <h2 class="text-xl mb-6">Submit a new griefer report</h2>

    <form class="w-96 p-4 bg-gray-200 rounded">
        @csrf

        <div class="mt-4">
            <label for="curse_name" class="mb-2 text-blue-400 block">Curse name</label>
            <input type="text" placeholder="Enter curse name without the X's"
            class="block w-full" />
        </div>

        <div class="mt-4">
            <label for="family_name" class="mb-2 text-blue-400 block">Character name <small class="text-black">(Optional)</small></label>
            <input type="text" placeholder="In-game name of character that griefed"
            class="block w-full" />
        </div>

        <div class="mt-4">
            <label for="phex_name" class="mb-2 text-blue-400 block">Phex name <small class="text-black">(Optional)</small></label>
            <input type="text" placeholder="Griefers Phex name (if available)"
            class="block w-full" />
        </div>

        <div class="mt-4">
            <label for="description" class="mb-2 text-blue-400 block">Description</label>
            <textarea type="text" placeholder="Describe what took place"
            class="block w-full"></textarea>
        </div>

        <div class="mt-4">
            <label for="date" class="mb-2 text-blue-400 block">Date/time</label>
            <input id="date" type="datetime-local" class="block w-full" 
            />
        </div>

        <div class="mt-4">
            <input type="submit" class="bg-blue-400 text-white p-3 w-full" value="Submit report" />
        </div>
    </form>

</div>