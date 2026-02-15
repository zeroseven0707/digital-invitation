<x-user-layout>
    <x-slot name="title">Daftar Tamu</x-slot>
    <x-slot name="header">Daftar Tamu - {{ $invitation->bride_name }} & {{ $invitation->groom_name }}</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invitations.show', $invitation->id) }}">Detail Undangan</a></li>
        <li class="breadcrumb-item active">Daftar Tamu</li>
    </x-slot>

    @if(session('import_errors'))
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5><i class="icon fas fa-exclamation-triangle"></i> Detail Error Import:</h5>
        <ul>
            @foreach(session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($invitation->status !== 'published')
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5><i class="icon fas fa-info-circle"></i> Info</h5>
        Tombol kirim WhatsApp akan aktif setelah undangan dipublikasikan.
        <a href="{{ route('invitations.show', $invitation->id) }}" class="alert-link">Publikasikan undangan</a>
    </div>
    @endif

    <div class="row">
        <!-- Import/Export Card -->
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-file-import"></i> Import/Export Daftar Tamu</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Export</h5>
                            <p class="text-muted">Download daftar tamu dalam format CSV</p>
                            <a href="{{ route('guests.export', $invitation->id) }}" class="btn btn-success">
                                <i class="fas fa-download"></i> Export ke CSV
                            </a>
                        </div>
                        <div class="col-md-6">
                            <h5>Import</h5>
                            <p class="text-muted">Upload file CSV dengan kolom: name, category</p>
                            <form method="POST" action="{{ route('guests.import', $invitation->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="file" class="custom-file-input" id="csvFile" accept=".csv" required>
                                        <label class="custom-file-label" for="csvFile">Pilih file CSV</label>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload"></i> Import
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Guest Card -->
        <div class="col-md-12">
            <div class="card card-success card-outline collapsed-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-plus"></i> Tambah Tamu Baru</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('guests.store', $invitation->id) }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Nama Tamu <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Nama Tamu" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="whatsapp_number">No. WhatsApp</label>
                                    <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control" placeholder="628123456789">
                                    <small class="text-muted">Format: 628xxx (tanpa +)</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="category">Kategori <span class="text-danger">*</span></label>
                                    <select name="category" id="category" class="form-control" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="family">Keluarga</option>
                                        <option value="friend">Teman</option>
                                        <option value="colleague">Kolega</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Guest List Card -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> Daftar Tamu ({{ $guests->count() }})</h3>
                    <div class="card-tools">
                        <form method="GET" action="{{ route('guests.index', $invitation->id) }}" class="form-inline">
                            <div class="input-group input-group-sm">
                                <select name="category" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    <option value="family" {{ $selectedCategory === 'family' ? 'selected' : '' }}>Keluarga</option>
                                    <option value="friend" {{ $selectedCategory === 'friend' ? 'selected' : '' }}>Teman</option>
                                    <option value="colleague" {{ $selectedCategory === 'colleague' ? 'selected' : '' }}>Kolega</option>
                                </select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Nama</th>
                                <th width="15%">No. WhatsApp</th>
                                <th width="15%">Kategori</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guests as $index => $guest)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $guest->name }}</td>
                                    <td>
                                        @if($guest->whatsapp_number)
                                            <small class="text-muted">{{ $guest->whatsapp_number }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($guest->category === 'family')
                                            <span class="badge badge-primary">Keluarga</span>
                                        @elseif($guest->category === 'friend')
                                            <span class="badge badge-success">Teman</span>
                                        @else
                                            <span class="badge badge-info">Kolega</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($guest->whatsapp_number && $invitation->status === 'published' && $invitation->unique_url)
                                            @php
                                                $waMessage = "Assalamu'alaikum Warahmatullahi Wabarakatuh\n\n";
                                                $waMessage .= "Dengan penuh rasa hormat, kami mengundang Bapak/Ibu/Saudara/i untuk berkenan hadir dan memberikan doa restu pada acara pernikahan kami.\n\n";
                                                $waMessage .= "Informasi lengkap dapat diakses melalui tautan berikut:\n";
                                                $waMessage .= "👉 " . url('/i/' . $invitation->unique_url . '?to=' . urlencode($guest->name)) . "\n\n";
                                                $waMessage .= "Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir.\n\n";
                                                $waMessage .= "Wassalamu'alaikum Warahmatullahi Wabarakatuh\n\n";
                                                $waMessage .= "Terima kasih.";
                                            @endphp
                                            <a href="https://wa.me/{{ $guest->whatsapp_number }}?text={{ urlencode($waMessage) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-success"
                                               title="Kirim via WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                        @elseif($guest->whatsapp_number && $invitation->status !== 'published')
                                            <button type="button" class="btn btn-sm btn-secondary" disabled title="Undangan belum dipublikasikan">
                                                <i class="fab fa-whatsapp"></i>
                                            </button>
                                        @elseif(!$guest->whatsapp_number)
                                            <button type="button" class="btn btn-sm btn-secondary" disabled title="Nomor WhatsApp belum diisi">
                                                <i class="fab fa-whatsapp"></i>
                                            </button>
                                        @endif
                                        <button onclick="editGuest({{ $guest->id }}, '{{ addslashes($guest->name) }}', '{{ $guest->whatsapp_number }}', '{{ $guest->category }}')" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('guests.destroy', [$invitation->id, $guest->id]) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tamu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="fas fa-users fa-3x mb-3 d-block"></i>
                                        Belum ada tamu yang ditambahkan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Tamu</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editName">Nama</label>
                            <input type="text" id="editName" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editWhatsapp">No. WhatsApp</label>
                            <input type="text" id="editWhatsapp" name="whatsapp_number" class="form-control" placeholder="628123456789">
                            <small class="text-muted">Format: 628xxx (tanpa +)</small>
                        </div>
                        <div class="form-group">
                            <label for="editCategory">Kategori</label>
                            <select id="editCategory" name="category" class="form-control" required>
                                <option value="family">Keluarga</option>
                                <option value="friend">Teman</option>
                                <option value="colleague">Kolega</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Custom file input label
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        function editGuest(id, name, whatsapp, category) {
            document.getElementById('editForm').action = "{{ route('guests.update', [$invitation->id, ':id']) }}".replace(':id', id);
            document.getElementById('editName').value = name;
            document.getElementById('editWhatsapp').value = whatsapp || '';
            document.getElementById('editCategory').value = category;
            $('#editModal').modal('show');
        }
    </script>
    @endpush
</x-user-layout>
