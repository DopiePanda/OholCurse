<div>
    @auth
        @if(Auth::user()->id == 1)
            @if(session()->get('showAdminMenu'))
                <div id="adminMenu" class="w-full mt-2 mx-auto inset-x-0 z-10 rounded-lg bg-gray-300 lg:text-center py-4">
                    <div class="pl-4 flex flex-col lg:flex-row lg:justify-center">
                        <a class="p-2 border-r border-gray-400" href="/admin/logs">
                            <div class="mx-2">
                                <i class="w-6 h-6 fas fa-file-medical-alt  mr-1"></i>
                                <span>Web Logs</span>
                            </div>
                        </a>
                        <!-- <div class="px-4 border-x border-gray-400">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </div> -->
                        <a class="p-2 border-r border-gray-400" href="{{ route('admin.leaderboards.index') }}">
                            <div class="mx-2">
                                <i class="w-6 h-6 fas fa-trophy mr-1"></i>
                                <span>Leaderboards</span>
                            </div>
                        </a>
                        <a class="p-2 border-r border-gray-400" href="{{ route('admin.search.map.index') }}">
                            <div class="mx-2">
                                <i class="w-6 h-6 fas fa-map-marked-alt  mr-1"></i>
                                <span>Map Search</span>
                            </div>
                        </a>
                        <a class="p-2 border-r border-gray-400" href="{{ route('admin.search.map.area') }}">
                            <div class="mx-2">
                                <i class="w-6 h-6 fas fa-map-marked-alt  mr-1"></i>
                                <span>Map Area Seach</span>
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        @endif
    @endauth
</div>