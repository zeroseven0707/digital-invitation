<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Undangan') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('guests.index', $invitation->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Kelola Tamu') }}
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Kembali ke Dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Template Selection (Optional) -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Template Saat Ini: {{ $invitation->template->name }}</h3>
                        <button onclick="toggleTemplateSelection()" class="text-indigo-600 hover:text-indigo-800 text-sm">
                            Ganti Template
                        </button>
                    </div>

                    <div id="template-selection" class="hidden mb-8">
                        <h4 class="font-semibold mb-4">Pilih Template Baru</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($templates as $template)
                                <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow {{ $template->id === $invitation->template_id ? 'ring-2 ring-indigo-600' : '' }}">
                                    <div class="aspect-video bg-gray-100 flex items-center justify-center">
                                        @if($template->thumbnail_path && file_exists(storage_path('app/' . $template->thumbnail_path)))
                                            <img src="{{ asset('storage/' . str_replace('public/', '', $template->thumbnail_path)) }}"
                                                 alt="{{ $template->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-semibold text-lg mb-2">{{ $template->name }}</h4>
                                        <p class="text-gray-600 text-sm mb-4">{{ $template->description }}</p>
                                        <button onclick="selectTemplate({{ $template->id }})"
                                                class="w-full {{ $template->id === $invitation->template_id ? 'bg-gray-400' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white px-4 py-2 rounded transition-colors"
                                                {{ $template->id === $invitation->template_id ? 'disabled' : '' }}>
                                            {{ $template->id === $invitation->template_id ? 'Template Aktif' : 'Pilih Template' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Invitation Form -->
                    <div>
                        <h3 class="text-lg font-semibold mb-6 border-t pt-6">Data Undangan</h3>

                        <form method="POST" action="{{ route('invitations.update', $invitation->id) }}" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="template_id" id="template_id" value="{{ old('template_id', $invitation->template_id) }}">

                            <!-- Couple Information -->
                            <div class="border-b pb-6">
                                <h4 class="font-semibold mb-4">Informasi Mempelai</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="bride_name" :value="__('Nama Mempelai Wanita *')" />
                                        <x-text-input id="bride_name" name="bride_name" type="text" class="mt-1 block w-full" :value="old('bride_name', $invitation->bride_name)" required />
                                        <x-input-error :messages="$errors->get('bride_name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="groom_name" :value="__('Nama Mempelai Pria *')" />
                                        <x-text-input id="groom_name" name="groom_name" type="text" class="mt-1 block w-full" :value="old('groom_name', $invitation->groom_name)" required />
                                        <x-input-error :messages="$errors->get('groom_name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="bride_father_name" :value="__('Nama Ayah Mempelai Wanita')" />
                                        <x-text-input id="bride_father_name" name="bride_father_name" type="text" class="mt-1 block w-full" :value="old('bride_father_name', $invitation->bride_father_name)" />
                                        <x-input-error :messages="$errors->get('bride_father_name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="groom_father_name" :value="__('Nama Ayah Mempelai Pria')" />
                                        <x-text-input id="groom_father_name" name="groom_father_name" type="text" class="mt-1 block w-full" :value="old('groom_father_name', $invitation->groom_father_name)" />
                                        <x-input-error :messages="$errors->get('groom_father_name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="bride_mother_name" :value="__('Nama Ibu Mempelai Wanita')" />
                                        <x-text-input id="bride_mother_name" name="bride_mother_name" type="text" class="mt-1 block w-full" :value="old('bride_mother_name', $invitation->bride_mother_name)" />
                                        <x-input-error :messages="$errors->get('bride_mother_name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="groom_mother_name" :value="__('Nama Ibu Mempelai Pria')" />
                                        <x-text-input id="groom_mother_name" name="groom_mother_name" type="text" class="mt-1 block w-full" :value="old('groom_mother_name', $invitation->groom_mother_name)" />
                                        <x-input-error :messages="$errors->get('groom_mother_name')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Akad Information -->
                            <div class="border-b pb-6">
                                <h4 class="font-semibold mb-4">Informasi Akad</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="akad_date" :value="__('Tanggal Akad *')" />
                                        <x-text-input id="akad_date" name="akad_date" type="date" class="mt-1 block w-full" :value="old('akad_date', $invitation->akad_date?->format('Y-m-d'))" required />
                                        <x-input-error :messages="$errors->get('akad_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="akad_location" :value="__('Lokasi Akad *')" />
                                        <x-text-input id="akad_location" name="akad_location" type="text" class="mt-1 block w-full" :value="old('akad_location', $invitation->akad_location)" required />
                                        <x-input-error :messages="$errors->get('akad_location')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="akad_time_start" :value="__('Waktu Mulai *')" />
                                        <x-text-input id="akad_time_start" name="akad_time_start" type="time" class="mt-1 block w-full" :value="old('akad_time_start', $invitation->akad_time_start)" required />
                                        <x-input-error :messages="$errors->get('akad_time_start')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="akad_time_end" :value="__('Waktu Selesai *')" />
                                        <x-text-input id="akad_time_end" name="akad_time_end" type="time" class="mt-1 block w-full" :value="old('akad_time_end', $invitation->akad_time_end)" required />
                                        <x-input-error :messages="$errors->get('akad_time_end')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Reception Information -->
                            <div class="border-b pb-6">
                                <h4 class="font-semibold mb-4">Informasi Resepsi</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="reception_date" :value="__('Tanggal Resepsi *')" />
                                        <x-text-input id="reception_date" name="reception_date" type="date" class="mt-1 block w-full" :value="old('reception_date', $invitation->reception_date?->format('Y-m-d'))" required />
                                        <x-input-error :messages="$errors->get('reception_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="reception_location" :value="__('Lokasi Resepsi *')" />
                                        <x-text-input id="reception_location" name="reception_location" type="text" class="mt-1 block w-full" :value="old('reception_location', $invitation->reception_location)" required />
                                        <x-input-error :messages="$errors->get('reception_location')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="reception_time_start" :value="__('Waktu Mulai *')" />
                                        <x-text-input id="reception_time_start" name="reception_time_start" type="time" class="mt-1 block w-full" :value="old('reception_time_start', $invitation->reception_time_start)" required />
                                        <x-input-error :messages="$errors->get('reception_time_start')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="reception_time_end" :value="__('Waktu Selesai *')" />
                                        <x-text-input id="reception_time_end" name="reception_time_end" type="time" class="mt-1 block w-full" :value="old('reception_time_end', $invitation->reception_time_end)" required />
                                        <x-input-error :messages="$errors->get('reception_time_end')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="border-b pb-6">
                                <h4 class="font-semibold mb-4">Informasi Lokasi</h4>
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="full_address" :value="__('Alamat Lengkap *')" />
                                        <textarea id="full_address" name="full_address" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('full_address', $invitation->full_address) }}</textarea>
                                        <x-input-error :messages="$errors->get('full_address')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="google_maps_url" :value="__('URL Google Maps')" />
                                        <x-text-input id="google_maps_url" name="google_maps_url" type="url" class="mt-1 block w-full" :value="old('google_maps_url', $invitation->google_maps_url)" />
                                        <x-input-error :messages="$errors->get('google_maps_url')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="music_url" :value="__('URL Musik Latar')" />
                                        <x-text-input id="music_url" name="music_url" type="url" class="mt-1 block w-full" :value="old('music_url', $invitation->music_url)" />
                                        <x-input-error :messages="$errors->get('music_url')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('invitations.show', $invitation->id) }}" class="text-gray-600 hover:text-gray-900">
                                    Batal
                                </a>
                                <x-primary-button>
                                    {{ __('Simpan Perubahan') }}
                                </x-primary-button>
                            </div>
                        </form>

                        <!-- Gallery Management Section -->
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-semibold mb-6">Galeri Foto</h3>

                            <!-- Upload Form -->
                            <div class="mb-6">
                                <form id="upload-form" enctype="multipart/form-data" class="flex items-end gap-4">
                                    @csrf
                                    <div class="flex-1">
                                        <x-input-label for="photo" :value="__('Upload Foto (JPEG/PNG, max 2MB)')" />
                                        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                    </div>
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded transition-colors">
                                        Upload
                                    </button>
                                </form>
                                <div id="upload-message" class="mt-2 text-sm"></div>
                            </div>

                            <!-- Photo Grid -->
                            <div id="gallery-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @forelse($invitation->galleries()->orderBy('order')->get() as $gallery)
                                    <div class="gallery-item relative group" data-id="{{ $gallery->id }}" data-order="{{ $gallery->order }}">
                                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                            <img src="{{ asset('storage/' . $gallery->photo_path) }}" alt="Gallery photo" class="w-full h-full object-cover">
                                        </div>
                                        <button onclick="deletePhoto({{ $gallery->id }})" class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <div class="absolute top-2 left-2 bg-gray-800 bg-opacity-75 text-white px-2 py-1 rounded text-xs">
                                            {{ $gallery->order }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full text-center py-8 text-gray-500">
                                        Belum ada foto. Upload foto pertama Anda!
                                    </div>
                                @endforelse
                            </div>

                            @if($invitation->galleries()->count() > 0)
                                <div class="mt-4 text-sm text-gray-600">
                                    <p>💡 Tip: Drag foto untuk mengubah urutan tampilan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTemplateSelection() {
            const templateSelection = document.getElementById('template-selection');
            templateSelection.classList.toggle('hidden');
        }

        function selectTemplate(templateId) {
            document.getElementById('template_id').value = templateId;
            document.getElementById('template-selection').classList.add('hidden');

            // Show confirmation
            alert('Template berhasil dipilih. Simpan perubahan untuk menerapkan template baru.');
        }

        // Gallery Management
        const invitationId = {{ $invitation->id }};

        // Photo Upload
        document.getElementById('upload-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const messageDiv = document.getElementById('upload-message');
            const submitBtn = this.querySelector('button[type="submit"]');

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Uploading...';
            messageDiv.textContent = '';

            try {
                const response = await fetch(`/invitations/${invitationId}/gallery`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const data = await response.json();

                if (data.success) {
                    messageDiv.className = 'mt-2 text-sm text-green-600';
                    messageDiv.textContent = data.message;

                    // Add photo to grid
                    addPhotoToGrid(data.data);

                    // Reset form
                    this.reset();

                    // Reload page to update gallery
                    setTimeout(() => location.reload(), 1000);
                } else {
                    messageDiv.className = 'mt-2 text-sm text-red-600';
                    messageDiv.textContent = data.message;
                }
            } catch (error) {
                messageDiv.className = 'mt-2 text-sm text-red-600';
                messageDiv.textContent = 'Terjadi kesalahan saat upload foto';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Upload';
            }
        });

        // Delete Photo
        async function deletePhoto(photoId) {
            if (!confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
                return;
            }

            try {
                const response = await fetch(`/invitations/${invitationId}/gallery/${photoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Remove photo from grid
                    document.querySelector(`[data-id="${photoId}"]`).remove();

                    // Show success message
                    const messageDiv = document.getElementById('upload-message');
                    messageDiv.className = 'mt-2 text-sm text-green-600';
                    messageDiv.textContent = data.message;

                    // Reload page to update gallery
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert('Gagal menghapus foto');
                }
            } catch (error) {
                alert('Terjadi kesalahan saat menghapus foto');
            }
        }

        function addPhotoToGrid(photo) {
            const grid = document.getElementById('gallery-grid');

            // Remove empty state if exists
            const emptyState = grid.querySelector('.col-span-full');
            if (emptyState) {
                emptyState.remove();
            }

            const photoHtml = `
                <div class="gallery-item relative group" data-id="${photo.id}" data-order="${photo.order}">
                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                        <img src="${photo.photo_url}" alt="Gallery photo" class="w-full h-full object-cover">
                    </div>
                    <button onclick="deletePhoto(${photo.id})" class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="absolute top-2 left-2 bg-gray-800 bg-opacity-75 text-white px-2 py-1 rounded text-xs">
                        ${photo.order}
                    </div>
                </div>
            `;

            grid.insertAdjacentHTML('beforeend', photoHtml);
        }

        // Drag and Drop for Reordering (Simple implementation)
        let draggedElement = null;

        document.addEventListener('DOMContentLoaded', function() {
            const galleryItems = document.querySelectorAll('.gallery-item');

            galleryItems.forEach(item => {
                item.draggable = true;

                item.addEventListener('dragstart', function(e) {
                    draggedElement = this;
                    this.style.opacity = '0.5';
                });

                item.addEventListener('dragend', function(e) {
                    this.style.opacity = '1';
                });

                item.addEventListener('dragover', function(e) {
                    e.preventDefault();
                });

                item.addEventListener('drop', function(e) {
                    e.preventDefault();
                    if (draggedElement !== this) {
                        // Swap elements
                        const grid = document.getElementById('gallery-grid');
                        const allItems = [...grid.querySelectorAll('.gallery-item')];
                        const draggedIndex = allItems.indexOf(draggedElement);
                        const targetIndex = allItems.indexOf(this);

                        if (draggedIndex < targetIndex) {
                            this.parentNode.insertBefore(draggedElement, this.nextSibling);
                        } else {
                            this.parentNode.insertBefore(draggedElement, this);
                        }

                        // Update order and save
                        updatePhotoOrder();
                    }
                });
            });
        });

        async function updatePhotoOrder() {
            const items = document.querySelectorAll('.gallery-item');
            const photos = [];

            items.forEach((item, index) => {
                photos.push({
                    id: parseInt(item.dataset.id),
                    order: index + 1
                });

                // Update visual order number
                const orderBadge = item.querySelector('.absolute.top-2.left-2');
                if (orderBadge) {
                    orderBadge.textContent = index + 1;
                }
            });

            try {
                const response = await fetch(`/invitations/${invitationId}/gallery/reorder`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ photos })
                });

                const data = await response.json();

                if (data.success) {
                    const messageDiv = document.getElementById('upload-message');
                    messageDiv.className = 'mt-2 text-sm text-green-600';
                    messageDiv.textContent = data.message;

                    setTimeout(() => {
                        messageDiv.textContent = '';
                    }, 3000);
                }
            } catch (error) {
                console.error('Error updating photo order:', error);
            }
        }
    </script>
</x-app-layout>
