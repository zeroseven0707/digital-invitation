<x-user-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">Dashboard</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item active">Dashboard</li>
    </x-slot>

    <!-- Welcome User Premium Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="user-welcome-hero p-4 text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center shadow-sm">
                <div>
                    <h3 class="font-weight-bold mb-1"><i class="fas fa-heart mr-2" style="color: #f0d060;"></i>Mulai Langkah Bahagia Anda</h3>
                    <p class="mb-0 text-white-50">Desain dan sebarkan undangan pernikahan digital impian Anda secara praktis dan elegan.</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('invitations.create') }}" class="btn btn-light font-weight-bold px-4" style="color: #6b4ce6; border-radius: 10px; box-shadow: 0 4px 12px rgba(255,255,255,0.15);">
                        <i class="fas fa-plus mr-1"></i> Buat Undangan Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics Cards -->
    <div class="row">
        <!-- Total Invitations -->
        <div class="col-lg-4 col-12 mb-3">
            <div class="small-box user-stat-box text-white" style="background: linear-gradient(135deg, #6b4ce6 0%, #462eb5 100%);">
                <div class="inner">
                    <h3>{{ $statistics['total_invitations'] }}</h3>
                    <p class="font-weight-bold">Total Undangan Anda</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope" style="opacity: 0.25;"></i>
                </div>
            </div>
        </div>

        <!-- Total Guests -->
        <div class="col-lg-4 col-6 mb-3">
            <div class="small-box user-stat-box text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="inner">
                    <h3>{{ $statistics['total_guests'] }}</h3>
                    <p class="font-weight-bold">Total Nama Tamu</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users" style="opacity: 0.25;"></i>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div class="col-lg-4 col-6 mb-3">
            <div class="small-box user-stat-box text-white" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                <div class="inner">
                    <h3>{{ $statistics['total_views'] }}</h3>
                    <p class="font-weight-bold">Total Undangan Dilihat</p>
                </div>
                <div class="icon">
                    <i class="fas fa-eye" style="opacity: 0.25;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Invitations List Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0 font-weight-bold text-dark" style="font-size: 1.1rem;">
                        <i class="fas fa-list-ul text-primary mr-2" style="color: #6b4ce6;"></i>Daftar Undangan Saya
                    </h3>
                </div>
                <div class="card-body p-0">
                    @if($invitations->isEmpty())
                        <div class="text-center py-5 px-3">
                            <div class="mb-4">
                                <i class="fas fa-envelope-open-text fa-5x" style="color: #cbd5e1;"></i>
                            </div>
                            <h5 class="text-dark font-weight-bold">Belum ada undangan yang dibuat</h5>
                            <p class="text-muted mb-4">Mari buat undangan digital pernikahan impian Anda sekarang juga!</p>
                            <a href="{{ route('invitations.create') }}" class="btn btn-primary px-4 py-2 font-weight-bold">
                                <i class="fas fa-magic mr-1"></i> Mulai Buat Undangan
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Undangan Mempelai</th>
                                        <th>Pilihan Template</th>
                                        <th>Status Publikasi</th>
                                        <th>Status Pembayaran</th>
                                        <th class="text-center">Total Tamu</th>
                                        <th class="text-center">Total Views</th>
                                        <th class="text-center">Aksi / Kelola</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invitations as $invitation)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-couple mr-3 text-white d-flex align-items-center justify-content-center shadow-sm">
                                                        <i class="fas fa-heart"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="text-dark d-block" style="font-size: 0.95rem;">{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</strong>
                                                        <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i>{{ $invitation->akad_date?->format('d M Y') ?? 'Belum ditentukan' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light px-3 py-2 font-weight-bold border" style="color: #4b5563; border-radius: 50px;">
                                                    <i class="fas fa-palette mr-1 text-muted"></i> {{ $invitation->template->name }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($invitation->status === 'published')
                                                    <span class="badge bg-success-light text-success font-weight-bold px-3 py-1.5" style="border-radius: 50px;">
                                                        <i class="fas fa-circle mr-1" style="font-size: 0.6rem;"></i> Published
                                                    </span>
                                                @elseif($invitation->status === 'draft')
                                                    <span class="badge bg-warning-light text-warning font-weight-bold px-3 py-1.5" style="border-radius: 50px;">
                                                        <i class="fas fa-circle mr-1" style="font-size: 0.6rem;"></i> Draft
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary-light text-secondary font-weight-bold px-3 py-1.5" style="border-radius: 50px;">
                                                        <i class="fas fa-circle mr-1" style="font-size: 0.6rem;"></i> Unpublished
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($invitation->is_paid)
                                                    <span class="badge bg-success text-white font-weight-bold px-3 py-1.5 shadow-sm" style="border-radius: 50px; background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;">
                                                        <i class="fas fa-check-circle mr-1"></i> Aktif / Lunas
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger text-white font-weight-bold px-3 py-1.5 shadow-sm" style="border-radius: 50px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;">
                                                        <i class="fas fa-times-circle mr-1"></i> Belum Aktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center font-weight-bold text-dark">{{ $invitation->guests->count() }}</td>
                                            <td class="text-center font-weight-bold text-dark">
                                                <span class="badge bg-info-light text-info px-2.5 py-1.5 font-weight-bold" style="border-radius: 8px;">
                                                    <i class="far fa-eye mr-1"></i> {{ $invitation->views->count() }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                                    <a href="{{ route('invitations.show', $invitation->id) }}" class="btn btn-sm btn-info px-3" title="Detail Undangan">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('invitations.edit', $invitation->id) }}" class="btn btn-sm btn-primary px-3" title="Edit Data">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('guests.index', $invitation->id) }}" class="btn btn-sm btn-success px-3" title="Kelola Buku Tamu">
                                                        <i class="fas fa-users"></i>
                                                    </a>
                                                    <a href="{{ route('invitations.preview', $invitation->id) }}" target="_blank" class="btn btn-sm btn-warning px-3 text-white" title="Pratinjau Undangan" style="background: #f59e0b !important; border-color: #f59e0b !important;">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                    @if(!$invitation->is_paid)
                                                        <button type="button" class="btn btn-sm btn-danger px-3" data-toggle="modal" data-target="#paymentModal{{ $invitation->id }}" title="Aktivasi Fitur Premium" style="background: #ef4444 !important; border-color: #ef4444 !important;">
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
        <div class="modal fade" id="paymentModal{{ $invitation->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.15);">
                    <div class="modal-header text-white py-3" style="background: linear-gradient(135deg, #6b4ce6 0%, #462eb5 100%); border-bottom: none;">
                        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">
                            <i class="fas fa-wallet mr-2" style="color: #f0d060;"></i>Aktivasi Layanan Undangan Premium
                        </h5>
                        <button type="button" class="close text-white opacity-80" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4" style="background-color: #f8fafc;">
                        <div class="row">
                            <!-- Left Column: Checkout Details -->
                            <div class="col-md-6 mb-4 mb-md-0">
                                <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-file-invoice mr-1 text-primary"></i> Detail Pesanan Anda</h6>
                                <div class="card border-0 shadow-sm p-3 mb-3" style="border-radius: 12px; background: white;">
                                    <table class="table table-borderless table-sm mb-0" style="font-size: 0.9rem;">
                                        <tr>
                                            <td class="text-muted" width="40%">Mempelai</td>
                                            <td><strong class="text-dark">{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Template</td>
                                            <td class="text-dark font-weight-bold">{{ $invitation->template->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Tanggal Acara</td>
                                            <td class="text-dark font-weight-bold">{{ $invitation->akad_date?->format('d M Y') ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="premium-activation-card p-3 shadow-sm text-white" style="border-radius: 12px; background: linear-gradient(135deg, #2d1b69 0%, #1a103c 100%);">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="font-weight-bold"><i class="fas fa-star text-warning mr-1"></i> Biaya Registrasi</span>
                                        <h3 class="mb-0 text-white font-weight-bold">Rp 35.000</h3>
                                    </div>
                                    <div class="alert alert-warning border-0 py-2 mb-3" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b; border-radius: 8px;">
                                        <small class="font-weight-bold">
                                            <i class="fas fa-heart text-danger mr-1"></i> Punya rezeki lebih? <strong>Bayar Se-ikhlas-nya!</strong>
                                        </small>
                                    </div>
                                    <div class="features-list" style="font-size: 0.85rem; line-height: 1.6;">
                                        <div><i class="fas fa-check text-success mr-2"></i> Kuota Kirim Tamu Tanpa Batas</div>
                                        <div><i class="fas fa-check text-success mr-2"></i> Album Foto Galeri Pernikahan</div>
                                        <div><i class="fas fa-check text-success mr-2"></i> Konfirmasi RSVP & Doa Tamu</div>
                                        <div><i class="fas fa-check text-success mr-2"></i> Backsound Musik Latar Belakang</div>
                                        <div><i class="fas fa-check text-success mr-2"></i> Akses Statistik Kunjungan</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Scan QRIS -->
                            <div class="col-md-6 text-center">
                                <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-qrcode mr-1 text-primary"></i> Pindai Kode QRIS Resmi</h6>
                                <div class="card border-0 shadow-sm p-3 bg-white mb-3" style="border-radius: 15px;">
                                    <img src="{{ asset('images/duniakaryastore.jpeg') }}"
                                         alt="QRIS Code Nikahin"
                                         class="img-fluid"
                                         style="max-width: 250px; margin: 0 auto; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 10px;">
                                    <p class="text-muted mt-2 mb-0" style="font-size: 0.8rem;">
                                        Mendukung pembayaran m-Banking (BCA, Mandiri, BRI, BNI) dan E-Wallet (Gopay, OVO, Dana, LinkAja, ShopeePay)
                                    </p>
                                </div>

                                <div class="alert bg-warning-light text-warning border-0 p-3 mb-0" style="border-radius: 10px; font-size: 0.85rem; line-height: 1.4;">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Harap simpan screenshot bukti pembayaran Anda sebelum menekan tombol konfirmasi.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-0 py-3 px-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-light px-4 py-2 font-weight-bold" data-dismiss="modal" style="border-radius: 10px;">
                            <i class="fas fa-times mr-1"></i> Batalkan
                        </button>
                        <a href="https://wa.me/6282342742787?text=Halo%20Admin%2C%20saya%20sudah%20melakukan%20pembayaran%20untuk%20undangan%20*{{ urlencode($invitation->bride_name . ' & ' . $invitation->groom_name) }}*%20dengan%20ID%20*{{ $invitation->id }}*.%20Berikut%20saya%20lampirkan%20bukti%20transfernya.%20Mohon%20untuk%20diaktifkan.%20Terima%20kasih."
                           target="_blank"
                           class="btn btn-success px-4 py-2 font-weight-bold"
                           style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; border-radius: 10px; box-shadow: 0 4px 12px rgba(16,185,129,0.2);">
                            <i class="fab fa-whatsapp mr-1" style="font-size: 1.15rem; vertical-align: middle;"></i> Konfirmasi Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach

    <style>
        /* Welcome Premium Header Hero Box */
        .user-welcome-hero {
            background: linear-gradient(135deg, #6b4ce6 0%, #462eb5 100%);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(107, 76, 230, 0.12);
        }
        
        .user-stat-box {
            border-radius: 16px !important;
            border: none !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .user-stat-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        }

        /* Couples Avatar bubble icon */
        .avatar-couple {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6b4ce6 0%, #5538d4 100%);
            font-size: 1.1rem;
        }

        /* Light badge soft colors */
        .bg-success-light {
            background-color: rgba(16, 185, 129, 0.12) !important;
        }
        .bg-warning-light {
            background-color: rgba(245, 158, 11, 0.12) !important;
        }
        .bg-secondary-light {
            background-color: rgba(100, 116, 139, 0.12) !important;
        }
        .bg-info-light {
            background-color: rgba(14, 165, 233, 0.12) !important;
        }
        
        .table td, .table th {
            vertical-align: middle !important;
        }

        /* Premium activation items checklist spacing */
        .features-list div {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
    </style>
</x-user-layout>
