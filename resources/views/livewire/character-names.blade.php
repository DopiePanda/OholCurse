<div class="w-full lg:w-1/3">
    <div class="p-4 dark:bg-slate-700 rounded-lg">
        <div class="text-center text-4xl dark:text-gray-200">Name search/generator</div>
        
        <div class="w-auto mt-6 p-4 border border-gray-400 rounded-lg">
            <div class="text-md font-semibold dark:text-gray-200">First name:</div>
            <div class="flex flex-col lg:flex-row">
                <div class="mt-2">
                    <button class="ml-1 lg:ml-0 p-2" type="button" wire:click="randomFirstName">
                        <i class="text-blue-400 fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="mt-2 lg:text-right">
                    <select class="ml-1 border border-blue-400 text-black rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" wire:change="changeGender" wire:model="gender">
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                    </select>
                </div>
                <div class="mt-2">
                    <input wire:model="first_name" wire:keyup="searchFirstName" class="ml-1 border border-blue-400 text-black rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600">
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
            </div>
        </div>

        <div class="mt-6 p-4 border border-gray-400 rounded-lg">
            <div class="text-md font-semibold dark:text-gray-200">Last name:</div>
            <div class="flex flex-col lg:flex-row">
                <div class="mt-2">
                    <button class="ml-1 lg:ml-0 p-2" type="button" wire:click="randomLastName">
                        <i class="text-blue-400 fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="mt-2">
                    <input wire:model="last_name" wire:keyup="searchLastName" type="text" class="ml-1 border border-blue-400 text-black rounded-lg dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600">
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
            </div>
        </div>
    </div>
</div>
