<div class="py-6 px-4">
    <div class="text-md text-center">
        Manage affiliation with
    </div>
    <div class="text-2xl text-center">
        {{ $player->leaderboard_name }}
    </div>

    <div class="w-2/3 mt-4 mx-auto border-b border-blue-400"></div>

    <form wire:submit.prevent='save'>
        @csrf
        <div class="mt-4 grid grid-rows-1">
            <div class="p-2">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-2 border border-green-500 rounded-xl">
                        <div><i class="text-rose-500 fas fa-heart"></i></div>
                        <label for="" class="block text-md font-semibold">Friend</label>
                        <input wire:model="type" value="friend" class="mt-2 rounded-md border border-blue-400" type='radio' />
                    </div>
                    <div class="text-center p-2 border border-orange-500 rounded-xl">
                        <div><i class="text-orange-500 fas fa-question"></i></div>
                        <label for="" class="block text-md font-semibold">Noted</label>
                        <input wire:model="type" value="dubious" class="mt-2 rounded-md border border-blue-400" type='radio' />
                    </div>
                    <div class="text-center p-2 border border-red-500 rounded-xl">
                        <div><i class="text-yellow-950 fas fa-poop"></i></div>
                        <label for="" class="block text-md font-semibold">Enemy</label>
                        <input wire:model="type" value="enemy" class="mt-2 rounded-md border border-blue-400" type='radio' />
                    </div> 
                </div>
                @error('type') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
            </div>
            <div class="p-2">
                <label class="block">Nickname</label>
                <input wire:model="nickname" class="mt-2 w-full rounded-md border border-blue-400" type='text' placeholder="PlayerNick420" />
                @error('nickname') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                <small>Enter a nickname for the contact.</small>
            </div>
            <div class="p-2">
                <label class="block">Phex-hash (Optional)</label>
                <input wire:model="phex_hash" class="mt-2 w-full rounded-md border border-blue-400" type='text' placeholder="f83kd921" />
                @error('phex_hash') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                <small>Enter a Phex-hash for the contact.</small>
            </div>

            <div class="p-2">
                <button type="submit" class="p-4 w-full bg-blue-400 text-white">Save</button>
            </div>

            <div class="mt-6 w-2/3 border-b border-gray-300 mx-auto"></div>

            <div class="p-2">
                <button wire:click="delete" type="button" class="p-4 w-full text-red-400">Delete contact</button>
            </div>
        </div>
    </form>
</div>
