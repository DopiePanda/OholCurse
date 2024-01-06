<div>
    <form wire:submit.prevent="process">
        <div
            x-data="{ uploading: false, progress: 0 }"
            x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false"
            x-on:livewire-upload-error="uploading = false"
            x-on:livewire-upload-progress="progress = $event.detail.progress"
        >
        <div class="mt-6 border border-blue-400 text-center dark:border-red-500">
            <div class="h-full px-1 py-1 text-center">
                <input class="block w-full h-full mx-auto dark:text-gray-400" type="file" wire:model.live="objects_file">
                <div class="dark:text-gray-200" wire:loading wire:target="objects_file">Uploading...</div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div x-show="uploading">
            <progress max="100" x-bind:value="progress"></progress>
        </div>

        <div class="mt-1 text-red-400 italic text-sm font-bold">
            @error('log') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button class="mt-4 p-2 w-full text-white bg-skin-fill dark:bg-skin-fill-dark" type="submit">
            Process log
        </button>
    </form>
</div>
