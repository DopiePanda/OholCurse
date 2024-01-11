<div class="fixed bottom-0 right-0 flex flex-col items-end">
    @auth
    <div id="conversationsWrapper" class="hidden">
        <!-- Chat wrapper -->
        <div class="fixed flex flex-col text-left bottom-0 pb-16 right-0 w-96 h-5/6 border border-skin-base dark:border-skin-base-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
            
            <!-- Chat navigation / settings -->
            <div class="flex flex-row p-4 border border-skin-base dark:border-skin-base-dark bg-skin-fill dark:bg-skin-fill-dark">
                <div class="shrink self-center h-10 w-10 text-center rounded-full bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                    <div class="mt-1 text-2xl font-black text-white">D</div>
                </div>
                <div class="grow self-center pl-4 text-md text-white">
                    <div class="">DopiePanda</div>
                </div>
                <div class="shrink self-center text-right pl-4 text-lg">
                    <div class="text-white">
                        <i class="fa-solid fa-users"></i>
                        <i class="ml-2 fa-solid fa-gear"></i>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="flex flex-row p-4 border-b border-skin-base dark:border-skin-base-dark bg-skin-fill-muted dark:bg-skin-fill-muted-dark">
                <div class="grow">
                    <input type="text" class="w-full rounded-lg" placeholder="Search conversations">
                </div>
            </div>

            <!-- Friends -->
            <div class="flex flex-row p-4 border-b border-skin-base dark:border-skin-base-dark bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
                <div class="shrink self-center h-12 w-12 text-center rounded-full bg-skin-fill dark:bg-skin-fill-dark">
                    <div class="mt-2 text-2xl font-black text-white">H</div>
                </div>
                <div class="grow self-center pl-4 text-md text-white">
                    <div class="">My Friend</div>
                </div>
                <div class="shrink self-center text-right pl-4 text-lg text-skin-base dark:text-skin-base-dark">
                    <div class="">
                        <i class="fa-solid fa-envelope text-skin-base dark:text-skin-base-dark"></i>
                    </div>
                </div>
            </div>

            <div class="flex flex-row p-4 border-b border-skin-base dark:border-skin-base-dark bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark">
                <div class="shrink self-center h-12 w-12 text-center rounded-full bg-skin-fill dark:bg-skin-fill-dark">
                    <div class="mt-2 text-2xl font-black text-white">G</div>
                </div>
                <div class="grow self-center pl-4 text-md text-white">
                    <div class="text-md">My Friend #2</div>
                    <div class="text-xs text-gray-400 italics">Hey, were you Joe Moma?</div>
                </div>
                <div class="shrink self-center text-right pl-4 text-lg text-skin-base dark:text-skin-base-dark">
                        <i class="fa-solid fa-envelope-open text-skin-muted dark:text-skin-muted-dark"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat menu -->
        <div class="z-30 pb-4 pr-4 grow border-gray-200">
            <i id="conversationToggle" class="fa-solid fa-comments fa-2x text-skin-base dark:text-skin-base-dark"></i>
        </div>
    </div>
    @endauth
</div>
