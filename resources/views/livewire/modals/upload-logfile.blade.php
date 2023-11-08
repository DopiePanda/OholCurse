<div class="p-6">
    <form wire:submit.prevent="save">
        <div>
            <div class="text-xl font-bold">Upload your Yumlog-file</div>
            <div class="mt-3">You will find your "yumlog.txt"-file in the same folder you installed the YumLife* client.</div>
            <div class="mt-2 text-sm italic font-bold text-red-400">*You need to have the YumLife mod client installed to get the "yumlog.txt"-file.</div>
        </div>
        <div class="my-6 border border-blue-400 text-center">
            <div class="h-full px-1 py-1 text-center">
                <input class="block w-full h-full mx-auto" type="file" wire:model.live="log">
                <div wire:loading wire:target="log">Uploading...</div>
            </div>
        </div>
        <div class="text-sm italic">Wait untill the logfile has finished uploading before clicking "Process log"</div>
        
        <div class="mt-1 text-red-400 italic text-sm font-bold">
            @error('log') <span class="error">{{ $message }}</span> @enderror
        </div>
     
        <button class="mt-4 p-2 w-full bg-blue-400 text-white" type="submit">Process log</button>
    </form>
</div>
