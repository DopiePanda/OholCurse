<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-400">
            {{ __('Create A New Guide') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-slate-700">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white border-b border-gray-200 dark:bg-slate-700 dark:border-gray-900">

                    <form action="{{ route('guides.store') }}" method="POST">
                        @csrf

                        <div>
                            <x-label class="text-gray-800 dark:text-gray-400" for="title" :value="__('Title')" />

                            <x-input id="title" class="block mt-1 w-full dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" type="text" name="title" :value="old('title')" />
                            @error('title')
                                <span class="text-sm text-red-600 mb-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <x-label class="text-gray-800 dark:text-gray-400" for="content" :value="__('Content')" />

                            <textarea id="content" rows="16" class="hidden block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-slate-800 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" name="content">{{ old('content') }}</textarea>
                            <trix-editor style="min-height:400px;" class="trix-editor dark:bg-slate-800 min-h-96 dark:text-gray-200 dark:placeholder:text-gray-700 dark:border-gray-600" input="content"></trix-editor>
                            @error('content')
                                <span class="text-sm text-red-600 mb-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <x-button class="mt-4">
                            {{ __('Submit') }}
                        </x-button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>

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
</x-app-layout>