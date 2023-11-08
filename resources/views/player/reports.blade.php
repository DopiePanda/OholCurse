<x-app-layout>
    <x-slot name="header">
        <livewire:player.profile-header :hash="$hash">
    </x-slot>

    <div class="w-full lg:grow lg:max-w-5xl flex flex-col">
        <x-player.menu :hash="$hash" />

        <div class="mt-6 bg-gray-200 p-2 lg:p-6">

            <div class="text-3xl mt-2 mb-6 text-center">Reports submitted by verified users</div>

            <div class="my-6 p-4 border border-blue-400 rounded-xl overflow-x-scroll">
                @if(count($reports) > 0)
                    <table class="w-full mx-auto mt-4">
                        <thead class="p-2">
                            <tr class="p-2">
                                <td class="p-2 bg-blue-400 text-white border border-gray-600">Reports</td>
                                <td class="p-2 bg-blue-400 text-white border border-gray-600">Status</td>
                                <td class="p-2 bg-blue-400 text-white border border-gray-600">Life ID</td>
                                <td class="p-2 bg-blue-400 text-white border border-gray-600">Life name</td>
                                <td class="p-2 bg-blue-400 text-white border border-gray-600">Curse name</td>
                                <td class="p-2 bg-blue-400 text-white border border-gray-600">Age</td>
                                <td class="p-2 bg-blue-400 text-white border border-gray-600">Died to</td>
                                <td class="p-2 bg-blue-400 text-white border border-gray-600">Reported at</td>
                            </tr>
                        </thead>
                        <tbody class="p-2">
                            @forelse ($reports as $report)
                                <tr class="even:bg-gray-300 odd:bg-white">
                                    <td class="p-2 border border-gray-400">{{ $report->count }}</td>
                                    <td class="p-2 border border-gray-400">
                                        @if($report->status == 0)
                                            <i title="Pending" class="text-orange-400 fa-solid fa-circle-question"></i>
                                        @elseif($report->status == 1)
                                            <i title="Verified" class="text-green-400 fa-solid fa-circle-check"></i>
                                        @elseif($report->status == 2)
                                            <i title="Archived" class="text-red-400 fa-solid fa-circle-xmark"></i>
                                        @elseif($report->status == 3)
                                            <i title="Curse-check" class="text-blue-400 fa-solid fa-magnifying-glass-minus"></i>
                                        @else
                                            <i title="Forgiven later" class="text-gray-300 fa-solid fa-clock-rotate-left"></i>
                                        @endif
                                    </td>
                                    <td class="p-2 border border-gray-400">{{ $report->character_id }}</td>
                                    <td class="p-2 border border-gray-400">
                                        {{ $report->character_name }}
                                        @if($report->gender == 'male')
                                            <i title="Male" class="text-blue-500 ml-1 fa-solid fa-mars"></i>
                                        @else
                                            <i title="Female" class="text-pink-500 ml-1 fa-solid fa-venus"></i>
                                        @endif
                                    </td>
                                    <td class="p-2 border border-gray-400">{{ $report->curse_name }}</td>
                                    <td class="p-2 border border-gray-400">{{ $report->age }}</td>
                                    <td class="p-2 border border-gray-400">{{ $report->died_to }}</td>
                                    <td class="p-2 border border-gray-400">{{ date('Y-m-d H:i:s', $report->timestamp) }}</td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                @else
                    <div class="px-2 text-center italic">No reports submitted by verified users.. Yet...</div>
                @endif
            </div>
            @php
                $time_end = microtime(true);
        
                $end = $time_end - $time;
            @endphp
            <div class="text-center text-sm mt-2 font-gray-400">Page load time: {{ round($end, 3) }}s</div>
        </div>
    </div>
</x-app-layout>