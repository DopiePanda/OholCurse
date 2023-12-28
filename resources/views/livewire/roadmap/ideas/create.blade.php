<div class="p-4 dark:bg-slate-700">
    <div class="text-lg text-gray-400 dark:text-gray-200 text-center">Submit a new feature you would like to see on Oholcurse</div>
    <div class="mt-8">
        <form wire:submit.prevent='create'>
            @csrf
            <div class="w-full grid grid-rows-1">
                <div class="p-2">
                    <label class="block dark:text-gray-400">Title of your idea:</label>
                    <input wire:model="title" class="mt-2 w-full rounded-md border border-blue-400 dark:bg-slate-700 dark:text-gray-200 dark:placeholder:text-gray-500 dark:border-gray-200 dark:focus:bg-slate-600" type='text' name='title' placeholder="Enter a title here" />
                    @error('title') <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> @enderror
                    <small class="text-gray-600 dark:text-gray-400">Enter a short title for you idea</small>
                </div>
                <div class="p-2 mt-4">
                    <label class="block mb-2 dark:text-gray-400">Descripe the feature you want:</label>

                    <input id="{{ $trix_id }}" type="hidden" name="description" value="{{ $value }}">
                    <trix-editor input="{{ $trix_id }}"></trix-editor>

                    @error('description')
                        <div class="mt-2 text-red-400 font-bold italic">{{ $message }}</div> 
                    @enderror
                </div>
                <div class="p-2">
                    <button type="submit" class="p-4 w-full bg-blue-400 text-white rounded-lg dark:bg-red-500 dark:text-gray-200">
                        Submit new feature request
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>

        <script>
            var trixEditor = document.getElementById("{{ $trix_id }}")
        
            addEventListener("trix-blur", function(event) {
                @this.set('value', trixEditor.getAttribute('value'))
            })
        </script>

        <script>
            addEventListener("trix-attachment-add", function (event) {
                if (event.attachment.file) {
                    uploadFileAttachment(event.attachment)
                }
            })
            function uploadFileAttachment(attachment) {
                uploadFile(attachment.file, setProgress, setAttributes)
                function setProgress(progress) {
                    attachment.setUploadProgress(progress)
                }
                function setAttributes(attributes) {
                    attachment.setAttributes(attributes)
                }
            }
            function uploadFile(data, progressCallback, successCallback) {
                var formData = createFormData(data);
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "{{ route('upload') }}", true);
                xhr.setRequestHeader("X-CSRF-Token", '{{ csrf_token() }}');
                xhr.upload.addEventListener("progress", function (event) {
                    var progress = (event.loaded / event.total) * 100;
                    progressCallback(progress);
                });
                xhr.addEventListener("load", function (event) {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        var response = JSON.parse(xhr.response);
                        successCallback({
                            url: response.url,
                            href: response.url
                        })
                    }
                });
                xhr.send(formData);
            }
            function createFormData(key) {
                var data = new FormData()
                data.append("Content-Type", key.type);
                data.append("file", key);
                return data
            }
        </script>
    @endpush
</div>

