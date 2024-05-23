<div class="text-center">
    <div class="mt-12 bg-gray-200 rounded-lg p-4">
        <div class="grid grid-cols-2 gap-2">
            @foreach ($ads as $ad)
                <div>
                    <img class="object-cover w-full h-full" src="{{ asset($ad->image_url) }}" />
                </div> 
            @endforeach
        </div>
        <div class="mt-4 text-4xl text-skin-base dark:text-skin-base-dark">Please wait while the application installs</div>

        <div class="mt-8 w-full bg-gray-200 rounded-full dark:bg-gray-700">
            <div id="progress" style="width: {{ $progress }}%" class="bg-skin-fill dark:bg-skin-fill-dark text-xs font-medium text-white text-center p-0.5 leading-none rounded-full">
                {{ $progress }}%
            </div>
        </div>
        <div class="mt-2 text-lg italic text-center text-gray-800">Installing MilkweedExtractorV203.c</div>
        <div class="py-4">
            <div class="mt-4 inline-block mx-auto p-2 text-white bg-skin-fill dark:bg-skin-fill-dark rounded-lg hover:hidden pointer-cursor">Cancel install</div>
        </div>
        
        @script
            <script>
                let minTime = 0;
                let maxTime = 3000;

                let time = 2000;

                function setRandomTime()
                {
                    time = Math.random(minTime, maxTime);
                }


                setInterval(() => {
                    $wire.$dispatch('increase-bar')
                    setRandomTime();
                }, time)
            </script>
        @endscript
    </div>
</div>