<div class="w-full rounded-xl py-12 mx-auto bg-gray-50 dark:bg-gray-900">
    
    @if($contacts)
        <div class="mt-8 p-4">
            
            <div class="text-center text-3xl">Profiles found</div>

            <div class="divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
                <table class="mt-8 w-full table-auto divide-y divide-gray-200 text-left dark:divide-white/5">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr class="text-sm">
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Owner</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Contact name</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">Leaderboard</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">PX hash</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">PX name</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">OLGC hash</th>
                            <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">OLGC name</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                    @forelse ($contacts as $contact)
                        <tr>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    <a href="https://onehouronelife.com/fitnessServer/server.php?action=leaderboard_detail&id={{ $contact->player->leaderboard_id }}" target="_blank">
                                        {{ $contact->user->username }}
                                    </a>
                                </div>
                            </td>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    <a href="{{ route('player.curses', ['hash' => $contact->hash ?? 'error']) }}" target="_blank">
                                        {{ $contact->nickname }}
                                    </a>
                                </div>
                            </td>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    {{ $contact->player->leaderboard_name }}
                                </div>
                            </td>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    {{ $contact->phex->px_hash ?? 'No PX Hash' }}
                                </div>
                            </td>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    <a href="{{ route('player.lives', ['hash' => $contact->hash ?? 'error']) }}" target="_blank">
                                        {{ $contact->phex->px_name ?? 'No PX Name' }}
                                    </a>
                                </div>
                            </td>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    {{ $contact->phex->olgc_hash ?? 'No OLGC Hash' }}
                                </div>
                            </td>
                            <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                <div class="text-sm leading-6 text-gray-950 dark:text-white gap-y-1 px-3 py-4">
                                    {{ $contact->phex->olgc_name ?? 'No OLGC Name' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <div class="text-center text-lg text-gray-400">No results</div>
                    @endforelse
                    </tbody>
                </table>
            </div>       
        </div>
    @endif
</div>
