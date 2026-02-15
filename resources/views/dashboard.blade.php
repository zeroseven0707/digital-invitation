<x-user-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">Dashboard</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item active">Dashboard</li>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $statistics['total_invitations'] }}</h3>
                    <p>Total Undangan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $statistics['total_guests'] }}</h3>
                    <p>Total Tamu</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $statistics['total_views'] }}</h3>
                    <p>Total Views</p>
                </div>
                <div class="icon">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Invitations List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Undangan Saya</h3>
                    <div class="card-tools">
                        <a href="{{ route('invitations.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Buat Undangan Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($invitations->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-envelope fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada undangan</h5>
                            <p class="text-muted">Mulai dengan membuat undangan pertama Anda.</p>
                            <a href="{{ route('invitations.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus"></i> Buat Undangan Baru
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Undangan</th>
                                        <th>Template</th>
                                        <th>Status</th>
                                        <th>Pembayaran</th>
                                        <th>Tamu</th>
                                        <th>Views</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invitations as $invitation)
                                        <tr>
                                            <td>
                                                <strong>{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</strong><br>
                                                <small class="text-muted">{{ $invitation->akad_date?->format('d M Y') }}</small>
                                            </td>
                                            <td>{{ $invitation->template->name }}</td>
                                            <td>
                                                @if($invitation->status === 'published')
                                                    <span class="badge badge-success">Published</span>
                                                @elseif($invitation->status === 'draft')
                                                    <span class="badge badge-warning">Draft</span>
                                                @else
                                                    <span class="badge badge-secondary">Unpublished</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($invitation->is_paid)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle"></i> Lunas
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times-circle"></i> Belum Bayar
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $invitation->guests->count() }}</td>
                                            <td>{{ $invitation->views->count() }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('invitations.show', $invitation->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('invitations.edit', $invitation->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('guests.index', $invitation->id) }}" class="btn btn-sm btn-success" title="Tamu">
                                                        <i class="fas fa-users"></i>
                                                    </a>
                                                    <a href="{{ route('invitations.preview', $invitation->id) }}" target="_blank" class="btn btn-sm btn-warning" title="Preview">
                                                        <i class="fas fa-search"></i>
                                                    </a>
                                                    @if(!$invitation->is_paid)
                                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#paymentModal{{ $invitation->id }}" title="Bayar">
                                                            <i class="fas fa-credit-card"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modals -->
    @foreach($invitations as $invitation)
        @if(!$invitation->is_paid)
        <div class="modal fade" id="paymentModal{{ $invitation->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title">
                            <i class="fas fa-credit-card"></i> Pembayaran Undangan
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold mb-3">Detail Undangan</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%">Mempelai</td>
                                        <td><strong>{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Template</td>
                                        <td>{{ $invitation->template->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Acara</td>
                                        <td>{{ $invitation->akad_date?->format('d M Y') }}</td>
                                    </tr>
                                </table>

                                <div class="alert alert-info mt-3">
                                    <h6 class="font-weight-bold mb-2">
                                        <i class="fas fa-info-circle"></i> Harga Paket
                                    </h6>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Undangan Digital Premium</span>
                                        <h4 class="mb-0 text-primary">Rp 50.000</h4>
                                    </div>
                                    <div class="alert alert-success mb-2" style="padding: 8px 12px;">
                                        <small class="font-weight-bold">
                                            <i class="fas fa-heart"></i> Lebih dari Rp 50.000? <strong>Se-ikhlas-nya!</strong>
                                        </small>
                                    </div>
                                    <small class="text-muted text-white">
                                        <i class="fas fa-check"></i> Unlimited Tamu<br>
                                        <i class="fas fa-check"></i> Galeri Foto<br>
                                        <i class="fas fa-check"></i> RSVP & Ucapan<br>
                                        <i class="fas fa-check"></i> Statistik Views<br>
                                        {{-- <i class="fas fa-check"></i> Custom Domain --}}
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6 text-center">
                                <h6 class="font-weight-bold mb-3">Scan QRIS untuk Pembayaran</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=00020101021226670016COM.NOBUBANK.WWW01189360050300000898740214545400009999990303UME51440014ID.CO.QRIS.WWW0215ID10200000000150303UME5204481253033605802ID5920Nikahin Digital6007Jakarta61051234062070703A0163044C4D"
                                             alt="QRIS Code"
                                             class="img-fluid mb-3"
                                             style="max-width: 300px; border: 3px solid #007bff; border-radius: 10px; padding: 10px;">
                                        <p class="text-muted mb-0">
                                            <small>Scan dengan aplikasi mobile banking atau e-wallet Anda</small>
                                        </p>
                                    </div>
                                </div>

                                <div class="alert alert-warning mt-3">
                                    <small>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Setelah melakukan pembayaran, klik tombol <strong>"Konfirmasi Pembayaran"</strong> di bawah untuk menghubungi admin.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                        <a href="https://wa.me/6281394454900?text=Halo%20Admin%2C%20saya%20sudah%20melakukan%20pembayaran%20untuk%20undangan%20*{{ urlencode($invitation->bride_name . ' & ' . $invitation->groom_name) }}*%20dengan%20ID%20*{{ $invitation->id }}*.%20Mohon%20untuk%20diaktifkan.%20Terima%20kasih."
                           target="_blank"
                           class="btn btn-success">
                            <i class="fab fa-whatsapp"></i> Konfirmasi Pembayaran via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
</x-user-layout>
