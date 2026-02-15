<x-user-layout>
    <x-slot name="title">Detail Undangan</x-slot>
    <x-slot name="header">Detail Undangan</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Detail Undangan</li>
    </x-slot>

    @if(session('unique_url'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5><i class="icon fas fa-check"></i> Selamat!</h5>
        Undangan Anda telah dipublikasikan. Scroll ke bawah untuk melihat URL dan membagikannya.
    </div>
    @endif

    <div class="row">
        <!-- Main Info Card -->
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-heart"></i>
                        {{ $invitation->bride_name }} & {{ $invitation->groom_name }}
                    </h3>
                    <div class="card-tools">
                        @if($invitation->status === 'published')
                            <span class="badge badge-success">Published</span>
                        @elseif($invitation->status === 'draft')
                            <span class="badge badge-warning">Draft</span>
                        @else
                            <span class="badge badge-secondary">Unpublished</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Couple Info -->
                        <div class="col-md-6">
                            <h5 class="text-primary"><i class="fas fa-user-friends"></i> Informasi Mempelai</h5>
                            <dl class="row">
                                <dt class="col-sm-5">Mempelai Wanita</dt>
                                <dd class="col-sm-7">{{ $invitation->bride_name }}</dd>
                                @if($invitation->bride_father_name || $invitation->bride_mother_name)
                                    <dt class="col-sm-5">Orang Tua</dt>
                                    <dd class="col-sm-7">{{ $invitation->bride_father_name }} & {{ $invitation->bride_mother_name }}</dd>
                                @endif
                                <dt class="col-sm-5">Mempelai Pria</dt>
                                <dd class="col-sm-7">{{ $invitation->groom_name }}</dd>
                                @if($invitation->groom_father_name || $invitation->groom_mother_name)
                                    <dt class="col-sm-5">Orang Tua</dt>
                                    <dd class="col-sm-7">{{ $invitation->groom_father_name }} & {{ $invitation->groom_mother_name }}</dd>
                                @endif
                            </dl>
                        </div>

                        <!-- Event Info -->
                        <div class="col-md-6">
                            <h5 class="text-primary"><i class="fas fa-calendar-alt"></i> Informasi Acara</h5>
                            <dl class="row">
                                <dt class="col-sm-4">Akad Nikah</dt>
                                <dd class="col-sm-8">
                                    {{ $invitation->akad_date->format('d F Y') }}<br>
                                    {{ $invitation->akad_time_start }} - {{ $invitation->akad_time_end }}<br>
                                    <small class="text-muted">{{ $invitation->akad_location }}</small>
                                </dd>
                                <dt class="col-sm-4">Resepsi</dt>
                                <dd class="col-sm-8">
                                    {{ $invitation->reception_date->format('d F Y') }}<br>
                                    {{ $invitation->reception_time_start }} - {{ $invitation->reception_time_end }}<br>
                                    <small class="text-muted">{{ $invitation->reception_location }}</small>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <hr>

                    <!-- Location -->
                    <h5 class="text-primary"><i class="fas fa-map-marker-alt"></i> Lokasi</h5>
                    <p>{{ $invitation->full_address }}</p>
                    @if($invitation->google_maps_url)
                        <a href="{{ $invitation->google_maps_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-map"></i> Lihat di Google Maps
                        </a>
                    @endif
                </div>
            </div>

            <!-- Published URL Card -->
            @if($invitation->status === 'published' && $invitation->unique_url)
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-link"></i> URL Undangan</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">Undangan Anda telah dipublikasikan. Bagikan URL berikut kepada tamu:</p>
                    <div class="input-group mb-3">
                        <input type="text" id="invitation-url" value="{{ url('/i/' . $invitation->unique_url) }}" class="form-control" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" onclick="copyToClipboard()">
                                <i class="fas fa-copy"></i> <span id="copy-text">Salin</span>
                            </button>
                        </div>
                    </div>
                    <div class="btn-group">
                        <a href="https://wa.me/?text={{ urlencode('Anda diundang ke pernikahan kami! ' . url('/i/' . $invitation->unique_url)) }}" target="_blank" class="btn btn-success">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="mailto:?subject={{ urlencode('Undangan Pernikahan ' . $invitation->bride_name . ' & ' . $invitation->groom_name) }}&body={{ urlencode('Anda diundang ke pernikahan kami! Lihat undangan di: ' . url('/i/' . $invitation->unique_url)) }}" class="btn btn-secondary">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions Sidebar -->
        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Aksi</h3>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('invitations.edit', $invitation->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-edit text-primary"></i> Edit Undangan
                        </a>
                        <a href="{{ route('invitations.preview', $invitation->id) }}" target="_blank" class="list-group-item list-group-item-action">
                            <i class="fas fa-eye text-info"></i> Preview
                        </a>
                        <a href="{{ route('guests.index', $invitation->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-users text-success"></i> Kelola Tamu
                        </a>
                        <a href="{{ route('rsvps.index', $invitation->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-clipboard-check text-purple"></i> Daftar RSVP
                            @if($invitation->rsvps->count() > 0)
                                <span class="badge badge-primary float-right">{{ $invitation->rsvps->count() }}</span>
                            @endif
                        </a>
                        <a href="{{ route('statistics.show', $invitation->id) }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar text-warning"></i> Statistik
                        </a>
                    </div>
                </div>
                <div class="card-footer">
                    @if($invitation->status === 'draft')
                        <form action="{{ route('invitations.publish', $invitation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-paper-plane"></i> Publikasikan
                            </button>
                        </form>
                    @elseif($invitation->status === 'published')
                        <form action="{{ route('invitations.unpublish', $invitation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-undo"></i> Unpublish
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('invitations.destroy', $invitation->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus undangan ini?')" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Statistik</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-muted small">Tamu</div>
                            <h4>{{ $invitation->guests->count() }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">RSVP</div>
                            <h4>{{ $invitation->rsvps->count() }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Views</div>
                            <h4>{{ $invitation->views->count() }}</h4>
                        </div>
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

            urlInput.select();
            urlInput.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(urlInput.value).then(() => {
                copyText.textContent = 'Tersalin!';
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
</x-user-layout>
