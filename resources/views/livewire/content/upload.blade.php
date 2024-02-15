<div class="w-full md:w-3/4 lg:w-2/5">
    @section("page-title")
    - Upload OHOL related content
    @endsection

    <div class="grow w-full max-w-7xl flex flex-col">
        <div class="w-full rounded-lg mt-6 bg-skin-fill-wrapper dark:bg-skin-fill-wrapper-dark p-2 lg:p-6">
            <div class="p-4 mb-4 text-center">
                <div class="text-skin-base dark:text-skin-base-dark text-4xl">Share your OHOL content!</div>
            </div>
            <div class="p-4 mb-4 w-2/3 mx-auto">
                <form>
                    <div class="mt-2">
                        <label class="block text-skin-base dark:text-skin-base-dark" for="title">Title</label>
                        <input class="mt-2 w-full" type="text" placeholder="My amazing content!" />
                    </div>
                    <div class="mt-4">
                        <label class="block text-skin-base dark:text-skin-base-dark" for="description">Description</label>
                        <textarea class="mt-2 w-full" placeholder="This is where i describe what my content is all about."></textarea>
                    </div>
                    <div class="mt-4">
                        <label class="block text-skin-base dark:text-skin-base-dark" for="category">Category</label>
                        <select class="mt-2 w-full">
                            <option>Image</option>
                            <option>Video</option>
                            <option>Text</option>
                            <option>Audio</option>
                            <option>Mods</option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <label class="block text-skin-base dark:text-skin-base-dark" for="file">File</label>
                        <input class="mt-2 w-full p-2 border text-skin-base dark:text-skin-base-dark" type="file" />
                    </div>

                    <div class="mt-4 flex flex-row">
                        <div><input class="inline-block h-6 w-6 mt-2" type="checkbox" /></div>
                        <div><label class="ml-2 mt-2 inline-block text-skin-base dark:text-skin-base-dark" for="nsfw">NSFW</label></div>
                    </div>

                    <div class="mt-4">
                        <button class="mt-2 w-full py-2 text-white bg-skin-fill dark:bg-skin-fill-dark" type="submit">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
