<x-user-layout>
    <x-slot name="title">Edit Undangan</x-slot>
    <x-slot name="header">Edit Undangan</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invitations.show', $invitation->id) }}">Detail</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </x-slot>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="info-tab" data-toggle="pill" href="#info" role="tab">
                                <i class="fas fa-info-circle"></i> Informasi Undangan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="gallery-tab" data-toggle="pill" href="#gallery" role="tab">
                                <i class="fas fa-images"></i> Galeri Foto
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-content">
                        <!-- Info Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <form method="POST" action="{{ route('invitations.update', $invitation->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="template_id" value="{{ old('template_id', $invitation->template_id) }}">

                                <!-- Template Info -->
                                <div class="alert alert-info">
                                    <strong>Template Saat Ini:</strong> {{ $invitation->template->name }}
                                </div>

                                <!-- Couple Information -->
                                <h5 class="text-primary"><i class="fas fa-user-friends"></i> Informasi Mempelai</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Nama Mempelai Wanita" name="bride_name" :value="$invitation->bride_name" :required="true" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Nama Mempelai Pria" name="groom_name" :value="$invitation->groom_name" :required="true" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Nama Ayah Mempelai Wanita" name="bride_father_name" :value="$invitation->bride_father_name" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Nama Ayah Mempelai Pria" name="groom_father_name" :value="$invitation->groom_father_name" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Nama Ibu Mempelai Wanita" name="bride_mother_name" :value="$invitation->bride_mother_name" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Nama Ibu Mempelai Pria" name="groom_mother_name" :value="$invitation->groom_mother_name" />
                                    </div>
                                </div>

                                <hr>

                                <!-- Akad Information -->
                                <h5 class="text-primary"><i class="fas fa-calendar-alt"></i> Informasi Akad</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Tanggal Akad" name="akad_date" type="date" :value="$invitation->akad_date?->format('Y-m-d')" :required="true" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Lokasi Akad" name="akad_location" :value="$invitation->akad_location" :required="true" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Waktu Mulai" name="akad_time_start" type="time" :value="$invitation->akad_time_start" :required="true" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Waktu Selesai" name="akad_time_end" type="time" :value="$invitation->akad_time_end" :required="true" />
                                    </div>
                                </div>

                                <hr>

                                <!-- Reception Information -->
                                <h5 class="text-primary"><i class="fas fa-glass-cheers"></i> Informasi Resepsi</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Tanggal Resepsi" name="reception_date" type="date" :value="$invitation->reception_date?->format('Y-m-d')" :required="true" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Lokasi Resepsi" name="reception_location" :value="$invitation->reception_location" :required="true" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Waktu Mulai" name="reception_time_start" type="time" :value="$invitation->reception_time_start" :required="true" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Waktu Selesai" name="reception_time_end" type="time" :value="$invitation->reception_time_end" :required="true" />
                                    </div>
                                </div>

                                <hr>

                                <!-- Location Information -->
                                <h5 class="text-primary"><i class="fas fa-map-marker-alt"></i> Informasi Lokasi</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-adminlte-textarea label="Alamat Lengkap" name="full_address" :value="$invitation->full_address" :required="true" />
                                    </div>
                                </div>

                                <!-- Map Picker -->
                                <div class="form-group">
                                    <label>Pilih Lokasi di Peta <small class="text-muted">(Klik pada peta untuk memilih lokasi)</small></label>
                                    <div id="map" style="height: 400px; border-radius: 8px; border: 1px solid #ddd;"></div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> Klik pada peta untuk menandai lokasi acara.
                                    </small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Latitude" name="latitude" type="text" id="latitude" :value="$invitation->latitude" readonly />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input label="Longitude" name="longitude" type="text" id="longitude" :value="$invitation->longitude" readonly />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="music_file">Musik Latar (MP3) <small class="text-muted">(Opsional, Max: 5MB)</small></label>

                                            @if($invitation->music_path && Storage::disk('public')->exists($invitation->music_path))
                                                <div class="alert alert-info">
                                                    <i class="fas fa-music"></i> Musik saat ini:
                                                    <audio controls style="max-width: 100%; margin-top: 10px;">
                                                        <source src="{{ Storage::disk('public')->url($invitation->music_path) }}" type="audio/mpeg">
                                                        Browser Anda tidak mendukung audio player.
                                                    </audio>
                                                    <div class="mt-2">
                                                        <label>
                                                            <input type="checkbox" name="remove_music" value="1"> Hapus musik
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="music_file" name="music_file" accept=".mp3,audio/mpeg">
                                                <label class="custom-file-label" for="music_file">Pilih file MP3 baru</label>
                                            </div>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle"></i> Upload file musik MP3 untuk latar belakang undangan (maksimal 5MB)
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('invitations.show', $invitation->id) }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Gallery Tab -->
                        <div class="tab-pane fade" id="gallery" role="tabpanel">
                            <h5 class="text-primary"><i class="fas fa-images"></i> Galeri Foto</h5>

                            <!-- Upload Form -->
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Upload Foto Baru</h3>
                                </div>
                                <div class="card-body">
                                    <form id="upload-form" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label>Pilih Foto (JPEG/PNG, max 2MB)</label>
                                                    <div class="custom-file">
                                                        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg" class="custom-file-input">
                                                        <label class="custom-file-label" for="photo">Pilih file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="btn btn-success btn-block">
                                                    <i class="fas fa-upload"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div id="upload-message"></div>
                                </div>
                            </div>

                            <!-- Photo Grid -->
                            <div id="gallery-grid" class="row">
                                @forelse($invitation->galleries()->orderBy('order')->get() as $gallery)
                                    <div class="col-md-3 gallery-item" data-id="{{ $gallery->id }}" data-order="{{ $gallery->order }}">
                                        <div class="card">
                                            <img src="{{ asset('storage/' . $gallery->photo_path) }}" class="card-img-top" alt="Gallery photo">
                                            <div class="card-body p-2">
                                                <button onclick="deletePhoto({{ $gallery->id }})" class="btn btn-danger btn-sm btn-block">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted py-5">
                                        <i class="fas fa-images fa-4x mb-3"></i>
                                        <p>Belum ada foto. Upload foto pertama Anda!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const invitationId = {{ $invitation->id }};

        // Custom file input
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        // Photo Upload
        document.getElementById('upload-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('upload-message');
            const submitBtn = this.querySelector('button[type="submit"]');

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
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
                    messageDiv.className = 'alert alert-success mt-2';
                    messageDiv.textContent = data.message;
                    this.reset();
                    $('.custom-file-label').html('Pilih file');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    messageDiv.className = 'alert alert-danger mt-2';
                    messageDiv.textContent = data.message;
                }
            } catch (error) {
                messageDiv.className = 'alert alert-danger mt-2';
                messageDiv.textContent = 'Terjadi kesalahan saat upload foto';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-upload"></i> Upload';
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
                    location.reload();
                } else {
                    alert('Gagal menghapus foto');
                }
            } catch (error) {
                alert('Terjadi kesalahan saat menghapus foto');
            }
        }

        // Initialize map for edit
        let map, marker;

        // Get existing coordinates or use default
        const existingLat = {{ $invitation->latitude ?? -7.2575 }};
        const existingLng = {{ $invitation->longitude ?? 112.7521 }};

        // Initialize map
        map = L.map('map').setView([existingLat, existingLng], 13);

        // Add OpenStreetMap tiles (free!)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Add existing marker if coordinates exist
        @if($invitation->latitude && $invitation->longitude)
            marker = L.marker([existingLat, existingLng]).addTo(map);
        @endif

        // Add click event to map
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            // Update marker
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            // Update form fields
            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
        });
    </script>
    @endpush
</x-user-layout>
