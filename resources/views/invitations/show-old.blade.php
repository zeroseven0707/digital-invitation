<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Undangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('unique_url'))
                <div class="mb-4 bg-indigo-100 border border-indigo-400 text-indigo-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Selamat!</strong>
                    <span class="block sm:inline">Undangan Anda telah dipublikasikan. Scroll ke bawah untuk melihat URL dan membagikannya.</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold mb-2">{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($invitation->status === 'published') bg-green-100 text-green-800
                            @elseif($invitation->status === 'draft') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($invitation->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Couple Information -->
                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold mb-3">Informasi Mempelai</h4>
                            <dl class="space-y-2 text-sm">
                                <div>
                                    <dt class="font-medium text-gray-500">Mempelai Wanita</dt>
                                    <dd>{{ $invitation->bride_name }}</dd>
                                </div>
                                @if($invitation->bride_father_name || $invitation->bride_mother_name)
                                    <div>
                                        <dt class="font-medium text-gray-500">Orang Tua</dt>
                                        <dd>{{ $invitation->bride_father_name }} & {{ $invitation->bride_mother_name }}</dd>
                                    </div>
                                @endif
                                <div class="pt-2">
                                    <dt class="font-medium text-gray-500">Mempelai Pria</dt>
                                    <dd>{{ $invitation->groom_name }}</dd>
                                </div>
                                @if($invitation->groom_father_name || $invitation->groom_mother_name)
                                    <div>
                                        <dt class="font-medium text-gray-500">Orang Tua</dt>
                                        <dd>{{ $invitation->groom_father_name }} & {{ $invitation->groom_mother_name }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Event Information -->
                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold mb-3">Informasi Acara</h4>
                            <dl class="space-y-2 text-sm">
                                <div>
                                    <dt class="font-medium text-gray-500">Akad Nikah</dt>
                                    <dd>{{ $invitation->akad_date->format('d F Y') }}</dd>
                                    <dd>{{ $invitation->akad_time_start }} - {{ $invitation->akad_time_end }}</dd>
                                    <dd>{{ $invitation->akad_location }}</dd>
                                </div>
                                <div class="pt-2">
                                    <dt class="font-medium text-gray-500">Resepsi</dt>
                                    <dd>{{ $invitation->reception_date->format('d F Y') }}</dd>
                                    <dd>{{ $invitation->reception_time_start }} - {{ $invitation->reception_time_end }}</dd>
                                    <dd>{{ $invitation->reception_location }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Location Information -->
                        <div class="border rounded-lg p-4 md:col-span-2">
                            <h4 class="font-semibold mb-3">Lokasi</h4>
                            <p class="text-sm mb-2">{{ $invitation->full_address }}</p>
                            @if($invitation->google_maps_url)
                                <a href="{{ $invitation->google_maps_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                    Lihat di Google Maps →
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Published URL Display -->
                    @if($invitation->status === 'published' && $invitation->unique_url)
                        <div class="mt-6 border-t pt-6">
                            <h4 class="font-semibold mb-3">URL Undangan</h4>
                            <p class="text-sm text-gray-600 mb-3">Undangan Anda telah dipublikasikan. Bagikan URL berikut kepada tamu undangan:</p>

                            <div class="flex items-center gap-2 mb-4">
                                <input
                                    type="text"
                                    id="invitation-url"
                                    value="{{ url('/i/' . $invitation->unique_url) }}"
                                    readonly
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm"
                                >
                                <button
                                    onclick="copyToClipboard()"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <span id="copy-text">Salin</span>
                                </button>
                            </div>

                            <div class="flex gap-3">
                                <a
                                    href="https://wa.me/?text={{ urlencode('Anda diundang ke pernikahan kami! ' . url('/i/' . $invitation->unique_url)) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                    </svg>
                                    Bagikan via WhatsApp
                                </a>
                                <a
                                    href="mailto:?subject={{ urlencode('Undangan Pernikahan ' . $invitation->bride_name . ' & ' . $invitation->groom_name) }}&body={{ urlencode('Anda diundang ke pernikahan kami! Lihat undangan di: ' . url('/i/' . $invitation->unique_url)) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Bagikan via Email
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 flex flex-wrap gap-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">
                            ← Kembali ke Dashboard
                        </a>
                        <a href="{{ route('invitations.edit', $invitation->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Undangan
                        </a>
                        <a href="{{ route('invitations.preview', $invitation->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Preview
                        </a>
                        <a href="{{ route('guests.index', $invitation->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kelola Tamu
                        </a>
                        <a href="{{ route('statistics.show', $invitation->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Statistik
                        </a>
                        @if($invitation->status === 'draft')
                            <form action="{{ route('invitations.publish', $invitation->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Publikasikan
                                </button>
                            </form>
                        @elseif($invitation->status === 'published')
                            <form action="{{ route('invitations.unpublish', $invitation->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Unpublish
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('invitations.destroy', $invitation->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus undangan ini?')" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard() {
            const urlInput = document.getElementById('invitation-url');
            const copyText = document.getElementById('copy-text');

            // Select and copy the text
            urlInput.select();
            urlInput.setSelectionRange(0, 99999); // For mobile devices

            navigator.clipboard.writeText(urlInput.value).then(() => {
                // Change button text temporarily
                copyText.textContent = 'Tersalin!';

                // Reset after 2 seconds
                setTimeout(() => {
                    copyText.textContent = 'Salin';
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Gagal menyalin URL. Silakan salin secara manual.');
            });
        }
    </script>
    @endpush
</x-app-layout>
