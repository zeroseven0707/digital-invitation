<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Undangan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">Pilih Template</h3>

                    @if($templates->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500">Belum ada template tersedia.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                            @foreach($templates as $template)
                                <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
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
                                                class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition-colors">
                                            Pilih Template
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Invitation Form (hidden initially) -->
                    <div id="invitation-form" class="hidden">
                        <h3 class="text-lg font-semibold mb-6 border-t pt-6">Data Undangan</h3>

                        <form method="POST" action="{{ route('invitations.store') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="template_id" id="template_id">

                            <!-- Couple Information -->
                            <div class="border-b pb-6">
                                <h4 class="font-semibold mb-4">Informasi Mempelai</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="bride_name" :value="__('Nama Mempelai Wanita *')" />
                                        <x-text-input id="bride_name" name="bride_name" type="text" class="mt-1 block w-full" :value="old('bride_name')" required />
                                        <x-input-error :messages="$errors->get('bride_name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="groom_name" :value="__('Nama Mempelai Pria *')" />
                                        <x-text-input id="groom_name" name="groom_name" type="text" class="mt-1 block w-full" :value="old('groom_name')" required />
                                        <x-input-error :messages="$errors->get('groom_name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="bride_father_name" :value="__('Nama Ayah Mempelai Wanita')" />
                                        <x-text-input id="bride_father_name" name="bride_father_name" type="text" class="mt-1 block w-full" :value="old('bride_father_name')" />
                                        <x-input-error :messages="$errors->get('bride_father_name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="groom_father_name" :value="__('Nama Ayah Mempelai Pria')" />
                                        <x-text-input id="groom_father_name" name="groom_father_name" type="text" class="mt-1 block w-full" :value="old('groom_father_name')" />
                                        <x-input-error :messages="$errors->get('groom_father_name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="bride_mother_name" :value="__('Nama Ibu Mempelai Wanita')" />
                                        <x-text-input id="bride_mother_name" name="bride_mother_name" type="text" class="mt-1 block w-full" :value="old('bride_mother_name')" />
                                        <x-input-error :messages="$errors->get('bride_mother_name')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="groom_mother_name" :value="__('Nama Ibu Mempelai Pria')" />
                                        <x-text-input id="groom_mother_name" name="groom_mother_name" type="text" class="mt-1 block w-full" :value="old('groom_mother_name')" />
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
                                        <x-text-input id="akad_date" name="akad_date" type="date" class="mt-1 block w-full" :value="old('akad_date')" required />
                                        <x-input-error :messages="$errors->get('akad_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="akad_location" :value="__('Lokasi Akad *')" />
                                        <x-text-input id="akad_location" name="akad_location" type="text" class="mt-1 block w-full" :value="old('akad_location')" required />
                                        <x-input-error :messages="$errors->get('akad_location')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="akad_time_start" :value="__('Waktu Mulai *')" />
                                        <x-text-input id="akad_time_start" name="akad_time_start" type="time" class="mt-1 block w-full" :value="old('akad_time_start')" required />
                                        <x-input-error :messages="$errors->get('akad_time_start')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="akad_time_end" :value="__('Waktu Selesai *')" />
                                        <x-text-input id="akad_time_end" name="akad_time_end" type="time" class="mt-1 block w-full" :value="old('akad_time_end')" required />
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
                                        <x-text-input id="reception_date" name="reception_date" type="date" class="mt-1 block w-full" :value="old('reception_date')" required />
                                        <x-input-error :messages="$errors->get('reception_date')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="reception_location" :value="__('Lokasi Resepsi *')" />
                                        <x-text-input id="reception_location" name="reception_location" type="text" class="mt-1 block w-full" :value="old('reception_location')" required />
                                        <x-input-error :messages="$errors->get('reception_location')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="reception_time_start" :value="__('Waktu Mulai *')" />
                                        <x-text-input id="reception_time_start" name="reception_time_start" type="time" class="mt-1 block w-full" :value="old('reception_time_start')" required />
                                        <x-input-error :messages="$errors->get('reception_time_start')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="reception_time_end" :value="__('Waktu Selesai *')" />
                                        <x-text-input id="reception_time_end" name="reception_time_end" type="time" class="mt-1 block w-full" :value="old('reception_time_end')" required />
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
                                        <textarea id="full_address" name="full_address" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('full_address') }}</textarea>
                                        <x-input-error :messages="$errors->get('full_address')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="google_maps_url" :value="__('URL Google Maps')" />
                                        <x-text-input id="google_maps_url" name="google_maps_url" type="url" class="mt-1 block w-full" :value="old('google_maps_url')" />
                                        <x-input-error :messages="$errors->get('google_maps_url')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="music_url" :value="__('URL Musik Latar')" />
                                        <x-text-input id="music_url" name="music_url" type="url" class="mt-1 block w-full" :value="old('music_url')" />
                                        <x-input-error :messages="$errors->get('music_url')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">
                                    Batal
                                </a>
                                <x-primary-button>
                                    {{ __('Simpan sebagai Draft') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectTemplate(templateId) {
            document.getElementById('template_id').value = templateId;
            document.getElementById('invitation-form').classList.remove('hidden');

            // Scroll to form
            document.getElementById('invitation-form').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        // Show form if there are validation errors
        @if($errors->any())
            document.getElementById('invitation-form').classList.remove('hidden');
        @endif
    </script>
</x-app-layout>
