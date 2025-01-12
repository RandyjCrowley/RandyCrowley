<!-- resources/views/storage-boxes/show.blade.php -->
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('storage-boxes') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Storage Boxes
                </a>
            </div>

            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Header Section -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">{{ $box->box_name }}</h1>
                            <p class="mt-2 text-gray-600">{{ $box->description }}</p>
                        </div>

                        <!-- QR Code -->

                    </div>

                    <!-- Photos Grid -->
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Box Photos</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @forelse ($box->photos as $photo)
                                <div class="relative group">
                                    <img src="{{ Storage::disk('local')->url($photo->photo_path) }}" alt="Box photo"
                                        class="w-full h-48 object-cover rounded-lg">
                                    @if ($photo->description)
                                        <div
                                            class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-2 rounded-b-lg">
                                            <p class="text-sm">{{ $photo->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500 col-span-full">No photos uploaded yet.</p>
                            @endforelse
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript for Modal -->
        <script>
            function showAddItemModal() {
                document.getElementById('addItemModal').classList.remove('hidden');
            }

            function hideAddItemModal() {
                document.getElementById('addItemModal').classList.add('hidden');
            }
        </script>
</x-app-layout><!-- resources/views/storage-boxes/show.blade.php -->
