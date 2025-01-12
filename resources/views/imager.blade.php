<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">File Upload</h1>
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

            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                {{ session('error') }}
                            </p>
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
                        <h2 class="text-xl font-semibold text-gray-900">Upload Files</h2>
                        <div class="rounded-full bg-blue-100 p-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    <form id="uploadForm" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- File Drop Zone -->
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg relative cursor-pointer hover:border-blue-500 transition-colors"
                            id="dropzone">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="fileInput"
                                        class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500">
                                        <span>Upload files</span>
                                        <input id="fileInput" type="file" class="sr-only" multiple>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>

                        <!-- Upload Progress Container -->
                        <div id="uploadProgress" class="mt-6 space-y-4 hidden">
                            <!-- Progress items will be inserted here dynamically -->
                        </div>
                    </form>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg mt-6">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">File History</h2>
                        <ul class="space-y-2">
                            @forelse (auth()->user()->files as $file)
                                <li class="flex justify-between items-center p-4 bg-gray-100 rounded-lg">
                                    <div>
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12H9m12-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-gray-800">{{ $file->original_filename }}</span>
                                        </div>
                                        <div class="mt-2 text-sm text-gray-600">
                                            <p><strong>Type:</strong> {{ $file->mime_type }}</p>
                                            <p><strong>Size:</strong> {{ formatBytes($file->file_size) }}
                                            </p>
                                            <p><strong>Status:</strong>
                                                @if ($file->status === 'completed')
                                                    <span class="text-green-600">Completed</span>
                                                @elseif ($file->status === 'uploading')
                                                    <span class="text-yellow-600">Uploading</span>
                                                @elseif ($file->status === 'cancelled')
                                                    <span class="text-red-600">Cancelled</span>
                                                @else
                                                    <span class="text-gray-600">Unknown</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <p class="text-gray-500 text-sm">No files uploaded yet.</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Progress Item Template -->
    <template id="progressTemplate">
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="filename text-sm font-medium text-gray-900"></span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="percentage text-sm text-gray-500"></span>
                    <span class="cancel-upload rounded-full p-1 hover:bg-gray-200 transition-colors duration-150"
                        title="Cancel upload">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-gray-500 hover:text-gray-700">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="progress-bar bg-blue-600 h-2 rounded-full transition-all duration-300"></div>
            </div>
        </div>
    </template>
    @routes
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dropzone = document.getElementById('dropzone');
                const fileInput = document.getElementById('fileInput');
                const uploadProgress = document.getElementById('uploadProgress');
                const progressTemplate = document.getElementById('progressTemplate');
                const CHUNK_SIZE = 5 * 1024 * 1024; // 5MB chunks
                const activeUploads = new Map(); // Track active uploads

                // Prevent default drag behaviors
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, preventDefaults, false);
                    document.body.addEventListener(eventName, preventDefaults, false);
                });

                // Highlight drop zone when dragging over it
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropzone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, unhighlight, false);
                });

                // Handle dropped files and clicks
                dropzone.addEventListener('drop', handleDrop, false);
                dropzone.addEventListener('click', () => fileInput.click(), false);
                fileInput.addEventListener('change', handleFiles, false);

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                function highlight(e) {
                    dropzone.classList.add('border-blue-500');
                }

                function unhighlight(e) {
                    dropzone.classList.remove('border-blue-500');
                }

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    handleFiles({
                        target: {
                            files
                        }
                    });
                }

                function handleFiles(e) {
                    const files = Array.from(e.target.files);
                    uploadProgress.classList.remove('hidden');

                    // Process each file concurrently
                    files.forEach(file => {
                        processFile(file);
                    });
                }

                async function processFile(file) {
                    // Create progress element
                    const progressElement = progressTemplate.content.cloneNode(true);
                    const progressBar = progressElement.querySelector('.progress-bar');
                    const filename = progressElement.querySelector('.filename');
                    const percentage = progressElement.querySelector('.percentage');
                    const cancelButton = progressElement.querySelector('.cancel-upload');

                    filename.textContent = file.name;
                    uploadProgress.appendChild(progressElement);

                    try {
                        // Initialize upload
                        const initResponse = await fetch(route('imager-upload-init'), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                filename: file.name,
                                fileSize: file.size,
                                mimeType: file.type
                            })
                        });

                        if (!initResponse.ok) throw new Error('Failed to initialize upload');
                        const {
                            uploadId
                        } = await initResponse.json();

                        // Store upload information with uploadId
                        activeUploads.set(uploadId, {
                            file,
                            progressBar,
                            percentage,
                            status: 'uploading',
                            cancel: false
                        });

                        // Create cancel handler with proper closure over uploadId
                        const handleCancel = async () => {
                            const upload = activeUploads.get(uploadId);
                            if (upload && upload.status !== 'cancelled') {
                                upload.cancel = true;
                                upload.status = 'cancelled';
                                progressBar.classList.remove('bg-blue-600');
                                progressBar.classList.add('bg-gray-500');
                                percentage.textContent = 'Cancelled';

                                // Remove the event listener
                            }
                        };

                        // Add cancel button handler
                        cancelButton.addEventListener('click', handleCancel);

                        // Calculate total chunks
                        const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
                        let uploadedChunks = 0;

                        // Upload chunks
                        for (let chunkStart = 0; chunkStart < file.size; chunkStart += CHUNK_SIZE) {
                            // Check if upload was cancelled
                            const upload = activeUploads.get(uploadId);
                            if (upload.cancel) {
                                await cancelUpload(uploadId);
                                break;
                            }

                            const chunkEnd = Math.min(chunkStart + CHUNK_SIZE, file.size);
                            const chunk = file.slice(chunkStart, chunkEnd);
                            const formData = new FormData();

                            formData.append('uploadId', uploadId);
                            formData.append('chunkNumber', Math.floor(chunkStart / CHUNK_SIZE));
                            formData.append('totalChunks', totalChunks);
                            formData.append('chunkSize', chunk.size);
                            formData.append('chunk', chunk);

                            const response = await fetch(route('imager-upload-chunk'), {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: formData
                            });

                            if (!response.ok) throw new Error('Failed to upload chunk');

                            if (percentage.textContent !== 'Cancelled') {

                                uploadedChunks++;
                                const progress = (uploadedChunks / totalChunks) * 100;
                                progressBar.style.width = progress + '%';
                                percentage.textContent = Math.round(progress) + '%';
                            }
                        }

                        // Only finalize if not cancelled
                        const upload = activeUploads.get(uploadId);
                        if (!upload.cancel) {
                            const finalizeResponse = await fetch(route('imager-upload-finalize'), {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: JSON.stringify({
                                    uploadId
                                })
                            });

                            if (!finalizeResponse.ok) throw new Error('Failed to finalize upload');

                            progressBar.classList.remove('bg-blue-600');
                            progressBar.classList.add('bg-green-500');
                            upload.status = 'completed';
                        }

                    } catch (error) {
                        console.error('Upload failed:', error);
                        progressBar.classList.remove('bg-blue-600');
                        progressBar.classList.add('bg-red-500');
                        percentage.textContent = 'Failed';
                    }
                }

                async function cancelUpload(uploadId) {
                    try {
                        await fetch(route('imager-upload-cancel'), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                uploadId
                            })
                        });
                    } catch (error) {
                        console.error('Failed to cancel upload:', error);
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
