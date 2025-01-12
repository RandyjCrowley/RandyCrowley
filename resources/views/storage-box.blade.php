<x-app-layout>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Storage Box Contents</h1>
            </div>

            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-4">
                    <div class="flex">
                        <div class="ml-3">
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Add Storage Box</h2>
                        <div class="rounded-full bg-blue-100 p-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('storage-boxes.store') }}" class="space-y-6"
                        id="storage-box-form">
                        @csrf

                        <!-- Box Information -->
                        <div>
                            <label for="box_name" class="block text-sm font-medium text-gray-700">Box Name/Label</label>
                            <input type="text" name="box_name" id="box_name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-2 border">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Contents
                                Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-2 border"></textarea>
                        </div>

                        <!-- Dropzone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Box Contents Photos</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg relative cursor-pointer hover:border-blue-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label
                                            class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                            <span>Upload photos</span>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                </div>
                                <!-- Dropzone Area -->
                                <div id="file-dropzone" class="absolute inset-0 opacity-0"></div>
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" id="submit-button"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Save Storage Box
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="preview-container">
                        <!-- Previews will be inserted here -->
                    </div>


                </div>
            </div>
        </div>
    </div>
    <script>
        Dropzone.autoDiscover = false;

        // Create preview template
        const previewTemplate = `
            <div class="relative group">
                <img data-dz-thumbnail class="w-full h-40 object-cover rounded-lg border-2 border-gray-200"/>
                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                    <button type="button" data-dz-remove class="text-white hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 truncate rounded-b-lg">
                    <span data-dz-name></span>
                </div>
            </div>
        `;

        const myDropzone = new Dropzone("#file-dropzone", {
            url: "{{ route('storage-boxes.store') }}",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFilesize: 10,
            maxFiles: 10,
            paramName: "box_photos",
            acceptedFiles: "image/*",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            previewTemplate: previewTemplate,
            previewsContainer: "#preview-container",
            clickable: "#file-dropzone"
        });

        // Handle form submission
        const form = document.getElementById('storage-box-form');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (myDropzone.getQueuedFiles().length === 0) {
                form.submit();
                return;
            }

            const formData = new FormData(form);
            myDropzone.options.params = {};
            for (let [key, value] of formData.entries()) {
                myDropzone.options.params[key] = value;
            }

            myDropzone.processQueue();
        });

        // Visual feedback for drag and drop
        const uploadArea = document.querySelector('.border-dashed');

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, function(e) {
                e.preventDefault();
                this.classList.add('border-blue-500');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, function(e) {
                e.preventDefault();
                this.classList.remove('border-blue-500');
            });
        });

        // Update UI text when files are added
        const textElement = uploadArea.querySelector('.text-gray-600');

        myDropzone.on("addedfiles", function(files) {
            const fileCount = this.files.length;
            const fileWord = fileCount === 1 ? 'file' : 'files';
            textElement.innerHTML = `<span class="text-blue-600">${fileCount} ${fileWord} selected</span>`;
        });



        // Reset UI text when all files are removed
        myDropzone.on("reset", function() {
            textElement.innerHTML = `
                <label class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                    <span>Upload photos</span>
                </label>
                <p class="pl-1">or drag and drop</p>
            `;
        });

        // Reset UI text when all files are removed
        myDropzone.on("removedfile", function(file) {
            const fileCount = $("#preview-container")[0].childElementCount;
            if (fileCount === 0) return;
            const fileWord = fileCount === 1 ? 'file' : 'files';
            textElement.innerHTML = `<span class="text-blue-600">${fileCount} ${fileWord} selected</span>`;
        });


        // Handle success
        myDropzone.on("successmultiple", function(files, response) {
            console.log("Upload successful", response);
            if (response.redirect) {
                window.location.href = response.redirect;
            } else {
                window.location.reload();
            }
        });

        // Handle errors
        myDropzone.on("errormultiple", function(files, response) {
            console.error("Upload failed", response);
            alert('Upload failed. Please try again.');
        });

        // Show error messages for individual files
        myDropzone.on("error", function(file, errorMessage) {
            const preview = file.previewElement;
            const errorElement = document.createElement('div');
            errorElement.className =
                'absolute bottom-0 left-0 right-0 bg-red-500 text-white text-xs p-1 text-center';
            errorElement.textContent = typeof errorMessage === 'string' ? errorMessage : 'Upload failed';
            preview.appendChild(errorElement);
        });
    </script>

</x-app-layout>
