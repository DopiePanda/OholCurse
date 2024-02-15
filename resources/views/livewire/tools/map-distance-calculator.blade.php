<div class="w-full xl:w-2/3">
    <div class="h-full p-2 flex flex-col md:flex-row">
        <div class="grow w-full md:w-1/2 xl:w-2/3 mt-4 md:mt-0 me-4 p-6 rounded-xl bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
            <form wire:submit.prevent="calculate">
                <div>
                    <div class="text-4xl text-center font-bold dark:text-gray-200">Map Travel Calculator</div>
                    <div class="mt-6 text-lg font-bold dark:text-gray-200">How to use the calculator:</div>
                    <div class="max-w-2xl mt-1 dark:text-gray-400">
                        <div>Step 1: 
                            <a class="text-skin-base dark:text-skin-base-dark" href="https://onemap.wondible.com/#t={{ time() }}" target="_blank">
                                Open Wondible's OHOL Map in a new tab
                            </a>
                        </div>
                        <div>Step 2: Locate and zoom in on your start point</div>
                        <div>Step 3: Copy the page URL containing the coordinates and paste into tbe "From"-field</div>
                        <div>Step 4: Locate and zoom in on your end point</div>
                        <div>Step 5: Copy the changed new URL and paste in into the "To"-field</div>
                        <div>Step 6: Click the "Calculate"-button</div>
                    </div>
                    
                </div>
                <div class="mt-6">
                    <div>
                        <label class="text-skin-base dark:text-skin-base-dark" for="from">From:</label>
                    </div>
                    <input class="w-full mt-1" wire:model="from" id="to" type="text" placeholder="https://onemap.wondible.com/#x=-583597&y=117&z=28&s=17&t=1707511757" />
                    <div class="mt-1 text-red-400 italic text-sm font-bold">
                        @error('from') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <div>
                        <label class="text-skin-base dark:text-skin-base-dark" for="to">To:</label>
                    </div>
                    <input class="w-full mt-1" wire:model="to" id="to" type="text" placeholder="https://onemap.wondible.com/#x=-582261&y=129&z=27&s=17&t=1707511757" />
                    <div class="mt-1 text-red-400 italic text-sm font-bold">
                        @error('to') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <div>
                        <label class="text-skin-base dark:text-skin-base-dark" for="label">Label: (optional)</label>
                    </div>
                    <input class="w-full mt-1" wire:model="label" id="label" type="text" placeholder="Ex: Dead Jungle Town" />
                    <div class="mt-1 text-red-400 italic text-sm font-bold">
                        @error('label') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
            
                <button class="mt-4 p-2 w-full text-white bg-skin-fill dark:bg-skin-fill-dark" type="submit">Calculate</button>
            </form>

            @if ($x_distance != null && $y_distance != null)
            
                <div class="py-2 mt-4 grid grid-cols-2 gap-4 text-skin-muted dark:text-skin-muted-dark">
                    <div wire:click="invert()" class="text-right">
                        <span class="group px-4 py-2 hover:text-gray-600 dark:hover:text-gray-300 border border-skin-muted hover:border-gray-600 dark:border-skin-muted-dark dark:hover:border-gray-300 cursor-pointer">
                            <i class="mr-1 fa-solid fa-repeat group-hover:text-orange-500 group-hover:rotate-180 transition-transform duration-500 ease-in-out"></i>
                            Invert
                        </span>
                    </div>
                    <div wire:click="clear()" class="text-left">
                        <span class="group px-4 py-2 hover:text-gray-600 dark:hover:text-gray-300 border border-skin-muted hover:border-gray-600 dark:border-skin-muted-dark dark:hover:border-gray-300 cursor-pointer">
                            <i class="mr-1 fa-solid fa-eraser group-hover:text-red-500 group-hover:-translate-x-1 transition-transform duration-300"></i>
                            Reset
                        </span>
                    </div>
                </div>

                <div class="mt-4 mb-4 h-1 w-2/3 mx-auto border-b border-skin-base dark:border-skin-base-dark"></div>

                <div class="mt-6 text-center grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                    <div class="p-6 bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                        <div class="text-xl text-gray-700 dark:text-gray-300 uppercase">Relative X:</div>
                        <div class="mt-1 text-5xl text-skin-base dark:text-skin-base-dark">{{ $x_distance }}</div>
                        <div class="mt-1 text-skin-muted dark:text-skin-muted-dark">
                            @if ($x_distance < 0)
                                (Go West)
                            @else
                                (Go East)
                            @endif
                        </div>
                    </div>
                    <div class="p-6 bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                        <div class="text-xl text-gray-700 dark:text-gray-300 uppercase">Relative Y:</div>
                        <div class="mt-1 text-5xl text-skin-base dark:text-skin-base-dark">{{ $y_distance }}</div>
                        <div class="mt-1 text-skin-muted dark:text-skin-muted-dark">
                            @if ($y_distance < 0)
                                (Go South)
                            @else
                                (Go North)
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-2xl text-center text-skin-base dark:text-skin-base-dark">
                    Estimated travel time
                </div>

                <div class="mt-4 relative overflow-x-auto rounded-lg border border-skin-base dark:border-skin-base-dark">
                    <table class="w-full table-auto">
                        <thead class="text-left">
                            <tr class="bg-skin-fill dark:bg-skin-fill-dark text-white">
                                <th class="p-2 pl-4">Method</th>
                                <th class="p-2">Time (Normal)</th>
                                <th class="p-2">Time (Road)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-skin-fill-muted dark:bg-skin-fill-muted-dark text-gray-700 dark:text-gray-300">
                            <tr class="border-b border-gray-400 dark:border-gray-800">
                                <td  class="p-2 pl-4">Walking</td>
                                <td  class="p-2">{{ $estimated_times["ground"]["walking"] }}</td>
                                <td  class="p-2">{{ $estimated_times["road"]["walking"] }}</td>
                            </tr>
                            <tr class="border-b border-gray-400 dark:border-gray-800">
                                <td  class="p-2 pl-4">Horse</td>
                                <td  class="p-2">{{ $estimated_times["ground"]["horse"] }}</td>
                                <td  class="p-2">{{ $estimated_times["road"]["horse"] }}</td>
                            </tr>
                            <tr class="border-b border-gray-400 dark:border-gray-800">
                                <td  class="p-2 pl-4">Truck</td>
                                <td  class="p-2">{{ $estimated_times["ground"]["truck"] }}</td>
                                <td  class="p-2">{{ $estimated_times["road"]["truck"] }}</td>
                            </tr>
                            <tr class="border-b border-gray-400 dark:border-gray-800">
                                <td  class="p-2 pl-4">Car</td>
                                <td  class="p-2">{{ $estimated_times["ground"]["car"] }}</td>
                                <td  class="p-2">{{ $estimated_times["road"]["car"] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        
        <div class="shrink relative sm:w-full md:w-1/2 xl:w-1/3 max-h-screen mt-4 md:mt-0 rounded-xl overflow-y-auto pt-6 bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
            <div class="mt-2 px-6 text-lg font-bold dark:text-gray-200">Session history:</div>
            
                @forelse ($calculations as $key => $data)
                    <div class="px-6">

                        
                        <div class="mt-6 text-md font-bold text-skin-base dark:text-skin-base-dark">
                            {{ $data["label"] ?? 'No label' }}
                        </div>
                        <div class="mt-1 flex flex-row gap-4 items-center place-items-center">
                            <div class="grow text-skin-muted dark:text-skin-muted-dark">
                                <div>
                                    X: {{ $data["x"] }}
                                    @if ($data["x"] < 0)
                                        [West]
                                    @else
                                        [East]
                                    @endif
                                </div>
                                <div>
                                    Y: {{ $data["y"] }}
                                    @if ($data["y"] < 0)
                                        [South]
                                    @else
                                        [North]
                                    @endif
                                </div>
                            </div>
                            <div class="shrink flex flex-row">
                                <div wire:click="edit('{{ $key }}')" class="mr-2 cursor-pointer">
                                    <i class="fa-solid fa-pencil fa-xl text-skin-muted dark:text-skin-muted-dark hover:text-skin-base dark:hover:text-skin-base-dark"></i>
                                </div>
                                <div wire:click="remove('{{ $key }}')" class="ml-2 cursor-pointer">
                                    <i class="fa-solid fa-trash-can fa-xl text-skin-muted dark:text-skin-muted-dark hover:text-skin-base dark:hover:text-skin-base-dark"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="mt-2 px-6 italic text-skin-muted dark:text-skin-muted-dark">
                        Session history is empty
                    </div>
                @endforelse

                @if(is_array($calculations) && count($calculations) > 0)
                <div wire:click="flush()" class="w-full h-12 pr-2 mt-4 pt-3 border-t border-skin-base dark:border-skin-base-dark text-center sticky bottom-0 left-0 text-skin-base dark:text-skin-base-dark cursor-pointer bg-gray-300 dark:bg-slate-900">
                    Delete all
                </div>
            @endif
        </div>
    </div>
</div>
