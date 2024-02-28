<div class="p-6 dark:bg-slate-800">
    <form wire:submit="save">
        <div>
            <div class="text-xl font-bold dark:text-gray-200">Upload your Yumlog-file</div>
            <div class="mt-3 dark:text-gray-400">You will find your "yumlog.txt"-file in the same folder you installed the YumLife* client.</div>
            <div class="mt-2 text-sm italic font-bold text-red-400">*You need to have the YumLife mod client installed to get the "yumlog.txt"-file.</div>
        </div>
        <div class="mt-6 border border-blue-400 text-center dark:border-red-500">
            <div class="h-full px-1 py-1 text-center">
                <input class="block w-full h-full mx-auto dark:text-gray-400" type="file" wire:model.live="log">
                <div class="dark:text-gray-200" wire:loading wire:target="log">Uploading...</div>
            </div>
        </div>
        <div class="mt-1 text-red-400 italic text-sm font-bold">
            @error('log') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="mt-4 text-sm italic dark:text-gray-400">Wait untill the log file has finished uploading before clicking "Process log"</div>
     
        <div wire:loading class="w-full mx-auto text-center mt-12 text-skin-base dark:text-skin-base-dark">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            <div class="text-skin-muted dark:text-skin-muted-dark">Processing log file</div>
        </div>

        <button class="mt-4 p-2 w-full text-white bg-skin-fill dark:bg-skin-fill-dark" type="submit">Process log</button>
    </form>
</div>
