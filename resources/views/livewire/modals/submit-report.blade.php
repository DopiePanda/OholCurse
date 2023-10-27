<div class="" wire:ignore.self>
    <div class="flex">
        @if($current_step == 1)
            <img class="w-full shadow-lg"
            src="{{ asset('assets/uploads/images/griefer-1.jpg') }}" 
            alt="oholcurse-logo" />
        @elseif($current_step == 2)
            <img class="w-full shadow-lg"
            src="{{ asset('assets/uploads/images/griefer-2.jpg') }}" 
            alt="oholcurse-logo" />
        @elseif($current_step == 3)
            <img class="w-full shadow-lg" 
            src="{{ asset('assets/uploads/images/griefer-3.jpg') }}" 
            alt="oholcurse-logo" />
        @elseif($current_step == 4)

        @endif
    </div>
    <div class="py-2 text-xl text-center bg-blue-400 text-white font-bold uppercase">
        Step: {{ $current_step }} / {{ $last_step }}
    </div>

    <form wire:submit.prevent="submitReport" class="p-4">
    @if($current_step == 1)
        <div class="mt-2 text-lg text-center font-bold">
            Did the incident occur within the last hour?
        </div>
        <div class="mt-4 grid grid-cols-2 gap-4">
            <div wire:click="autoFillDateTime()" class="bg-green-400 text-white px-2 py-4 text-center cursor-pointer">
                Yes
            </div>

            <div wire:click="manuallyFillDateTime()" class="bg-red-400 text-white px-2 py-4 text-center cursor-pointer">
                No
            </div>
        </div>
    @endif

    @if($current_step == 2)
        <div class="mt-4 text-lg text-center font-bold">
            When did the incident occur?
        </div>
        <div class="mt-2 grid grid-rows-1 gap-4">

            <div class="py-2 border border-gray-400">
                <div class="px-2">
                    Estimated time of incident:
                </div>
                <div class="grid grid-cols-2 gap-4 mt-2">
                    <div class="px-2">
                        <label class="block text-sm">Start time</label>
                        <input wire:model="time_start" class="w-full rounded-md border border-blue-400" type='datetime-local' id='time_start' />
                        @error('time_start') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                    </div>
                    <div class="px-2">
                        <label class="block text-sm">End time</label>
                        <input wire:model="time_end" class="w-full rounded-md border border-blue-400" type='datetime-local' id='time_end' />
                        @error('time_end') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            
            <div class="grid grid-rows-1 gap-4">
                <div class="p-2">

                    <label class="block">Timezone</label>
                    
                    <select class="mt-2 w-full rounded-md border border-blue-400" wire:model="user_timezone">
                        @foreach($timezones as $timezone)
                            <option wire:key="item-{{ $timezone->id }}" value="{{ $timezone->name }}">{{ $timezone->name }} ({{ $timezone->offset }})</option>
                        @endforeach 
                    </select>
                    @error('user_timezone') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                    
                </div>
                <div class="p-2">
                    <div wire:click="confirmDateTime()" class="p-4 text-center bg-blue-400 text-white cursor-pointer">
                        Set date & time
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($current_step == 3)
        <div class="mt-2 text-lg text-center font-bold">
            Describe who did it and what happened.
        </div>
        <div class="mt-4 grid grid-rows-1 gap-4">
            <div class="px-2">
                <label class="block">In-game name of the griefer</label>
                <input wire:model="character_name" class="mt-1 w-full rounded-md border border-blue-400" type='text' placeholder="Example: Anne Summer" />
                @error('character_name') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
            </div>
            <div class="px-2">
                <label class="block">Curse name of the griefer (without X's)</label>
                <input wire:model="curse_name" class="mt-1 w-full rounded-md border border-blue-400" type='text' placeholder="Example: Bone Dirt (Optional)" />
                @error('curse_name') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
            </div>
            <div class="px-2">
                <label class="block">Description of the incident</label>
                <textarea wire:model="description" class="mt-1 w-full rounded-md border border-blue-400" id="" cols="30" rows="5" placeholder="Example: Killed sheep and lured bears to town."></textarea>
                @error('description') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
            </div>
            <div class="px-2">
                <label class="block">Griefers Phex-username</label>
                <input wire:model="phex_name" class="mt-1 w-full rounded-md border border-blue-400" type='text' placeholder="Example: GriefGuy92 (Optional)" />
                @error('phex_name') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
            </div>
            <div class="px-2">
                <label class="block">Griefers Phex-hash (8 characters)</label>
                <input wire:model="phex_hash" class="mt-1 w-full rounded-md border border-blue-400" type='text' placeholder="Example: d9kg8a0d (Optional)" />
                @error('phex_hash') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mt-4 p-2">
            <div wire:click="validateReport()" class="p-4 text-center bg-blue-400 text-white cursor-pointer">
                Review & submit
            </div>
        </div>
    @endif

    @if($current_step == 4)
        <div class="mt-2 text-lg text-center font-bold">
            Review and submit your report
        </div>
        
        <div class="mt-4 p-2">
            <div class="grid grid-cols-2 gap-4 border border-gray-300 rounded-lg p-2">
                <div>
                    <div class="font-bold text-blue-400">Date/time start</div>
                    <div class="mt-1">{{ $time_start }}</div>
                </div>
                <div>
                    <div class="font-bold text-blue-400">Date/time end</div>
                    <div class="mt-1">{{ $time_end }}</div>
                </div>
                <div>
                    <div class="font-bold text-blue-400">Character name</div>
                    <div class="mt-1 capitalize">{{ $character_name }}</div>
                </div>
                <div>
                    <div class="font-bold text-blue-400">Curse name</div>
                    <div class="mt-1 uppercase">{!! $curse_name ?? '<i>- empty -</i>' !!}</div>
                </div>
            </div>
            <div class="mt-4 grid grid-rows-1 gap-4 border border-gray-300 rounded-lg p-2">
                
                <div>
                    <div class="font-bold text-blue-400">Description</div>
                    <div class="mt-1 break-all">{{ $description }}</div>
                </div>
                
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4 border border-gray-300 rounded-lg p-2">
                <div>
                    <div class="font-bold text-blue-400">Phex name</div>
                    <div class="mt-1">{!! $phex_name ?? '<i>- empty -</i>' !!}</div>
                </div>
                <div>
                    <div class="font-bold text-blue-400">Phex hash</div>
                    <div class="mt-1">{!! $phex_hash ?? '<i>- empty -</i>' !!}</div>
                </div>
            </div>
        </div>

        <div class="mt-4 p-2">
            <div wire:click="submitReport()" class="p-4 text-center bg-blue-400 text-white cursor-pointer">
                Confirm & submit
            </div>
        </div>
    @endif

    @if($current_step != 1)
        <button type="button" wire:click="previousStep()" class="w-full p-2 text-blue-400 text-center">
            Go back
        </button>
    @endif

    </form>

</div>
