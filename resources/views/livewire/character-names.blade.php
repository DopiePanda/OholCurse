<div class="w-full lg:w-1/3">
    <div class="p-4">
        <div class="text-center text-4xl">Name search/generator</div>
        
        <div class="w-auto mt-6 p-4 border border-gray-400 rounded-lg">
            <div class="text-md font-semibold">First name:</div>
            <div class="flex flex-col lg:flex-row">
                <div>
                    <input wire:model="first_name" wire:keyup="searchFirstName" class="border border-blue-400 text-black rounded-lg">
                    @if($first_names && count($first_names) >= 1 && $first_name != '')
                        <div class="absolute w-48">
                            @foreach ($first_names as $name)
                                <div class="p-2 bg-blue-400 text-white border-b cursor-pointer" wire:click="setFirstName('{{ $name->name }}')">
                                    {{ $name->name }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="lg:ml-2 lg:text-right">
                    <select class="border border-blue-400 text-black rounded-lg" wire:change="changeGender" wire:model="gender">
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                    </select>
                </div>
                <div>
                    <button class="ml-2 p-2" type="button" wire:click="randomFirstName">
                        <i class="text-blue-400 fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-6 p-4 border border-gray-400 rounded-lg">
            <div class="text-md font-semibold">Last name:</div>
            <div class="flex flex-col lg:flex-row">
                <div>
                    <input wire:model="last_name" wire:keyup="searchLastName" type="text" class="border border-blue-400 text-black rounded-lg">
                    @if($last_names && count($last_names) >= 1 && $last_name != '')
                        <div class="absolute w-48">
                            @foreach ($last_names as $name)
                            <div class="p-2 bg-blue-400 text-white border-b cursor-pointer" wire:click="setLastName('{{ $name->name }}')">
                                {{ $name->name }}
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div>
                    <button class="ml-2 p-2" type="button" wire:click="randomLastName">
                        <i class="text-blue-400 fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
