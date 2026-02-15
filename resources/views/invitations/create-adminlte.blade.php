<x-user-layout>
    <x-slot name="title">Buat Undangan Baru</x-slot>
    <x-slot name="header">Buat Undangan Baru</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Buat Undangan</li>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <!-- Step 1: Template Selection -->
            <div class="card card-primary card-outline" id="template-selection">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-palette"></i> Langkah 1: Pilih Template</h3>
                </div>
                <div class="card-body">
                    @if($templates->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-palette fa-4x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada template tersedia.</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach($templates as $template)
                                <div class="col-md-4">
                                    <div class="card template-card" style="cursor: pointer;" onclick="selectTemplate({{ $template->id }}, '{{ $template->name }}')">
                                        <div class="card-body text-center">
                                            @if($template->thumbnail_path && file_exists(storage_path('app/' . $template->thumbnail_path)))
                                                <img src="{{ asset('storage/' . str_replace('public/', '', $template->thumbnail_path)) }}"
                                                     alt="{{ $template->name }}"
                                                     class="img-fluid mb-3"
                                                     style="max-height: 200px;">
                                            @else
                                                <i class="fas fa-image fa-5x text-muted mb-3"></i>
                                            @endif
                                            <h5>{{ $template->name }}</h5>
                                            <p class="text-muted small">{{ $template->description }}</p>
                                            <button type="button" class="btn btn-primary btn-sm">
                                                <i class="fas fa-check"></i> Pilih Template
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Step 2: Invitation Form -->
            <div class="card card-success card-outline" id="invitation-form" style="display: none;">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-edit"></i> Langkah 2: Isi Data Undangan</h3>
                    <div class="card-tools">
                        <span class="badge badge-primary" id="selected-template"></span>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('invitations.store') }}">
                        @csrf
                        <input type="hidden" name="template_id" id="template_id">

                        <!-- Couple Information -->
                        <h5 class="text-primary"><i class="fas fa-user-friends"></i> Informasi Mempelai</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <x-adminlte-input label="Nama Mempelai Wanita" name="bride_name" :required="true" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="Nama Mempelai Pria" name="groom_name" :required="true" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="Nama Ayah Mempelai Wanita" name="bride_father_name" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="Nama Ayah Mempelai Pria" name="groom_father_name" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="Nama Ibu Mempelai Wanita" name="bride_mother_name" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="Nama Ibu Mempelai Pria" name="groom_mother_name" />
                            </div>
                        </div>

                        <hr>

                        <!-- Akad Information -->
                        <h5 class="text-primary"><i class="fas fa-calendar-alt"></i> Informasi Akad</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <x-adminlte-input label="Tanggal Akad" name="akad_date" type="date" :required="true" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="Lokasi Akad" name="akad_location" :required="true" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="Waktu Mulai" name="akad_time_start" type="time" :required="true" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="Waktu Selesai" name="akad_time_end" type="time" :required="true" />
                            </div>
                        </div>

                        <hr>

                        <!-- Reception Information -->
                        <h5 class="text-primary"><i class="fas fa-glass-cheers"></i> Informasi Resepsi</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <x-adminlte-input label="Tanggal Resepsi" name="reception_date" type="date" :required="true" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="Lokasi Resepsi" name="reception_location" :required="true" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="Waktu Mulai" name="reception_time_start" type="time" :required="true" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="Waktu Selesai" name="reception_time_end" type="time" :required="true" />
                            </div>
                        </div>

                        <hr>

                        <!-- Location Information -->
                        <h5 class="text-primary"><i class="fas fa-map-marker-alt"></i> Informasi Lokasi</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <x-adminlte-textarea label="Alamat Lengkap" name="full_address" :required="true" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="URL Google Maps" name="google_maps_url" type="url" />
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input label="URL Musik Latar" name="music_url" type="url" />
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                                <button type="button" onclick="backToTemplate()" class="btn btn-warning">
                                    <i class="fas fa-arrow-left"></i> Ganti Template
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan sebagai Draft
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function selectTemplate(templateId, templateName) {
            document.getElementById('template_id').value = templateId;
            document.getElementById('selected-template').textContent = templateName;
            document.getElementById('template-selection').style.display = 'none';
            document.getElementById('invitation-form').style.display = 'block';

            // Scroll to form
            document.getElementById('invitation-form').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function backToTemplate() {
            document.getElementById('template-selection').style.display = 'block';
            document.getElementById('invitation-form').style.display = 'none';

            // Scroll to template selection
            document.getElementById('template-selection').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        // Show form if there are validation errors
        @if($errors->any())
            document.getElementById('template-selection').style.display = 'none';
            document.getElementById('invitation-form').style.display = 'block';
        @endif
    </script>
    @endpush
</x-user-layout>
