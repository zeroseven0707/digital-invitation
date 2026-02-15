@extends('layouts.admin')

@section('page-title', 'Daftar Undangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Daftar Undangan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Semua Undangan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <form method="GET" action="{{ route('admin.invitations.index') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status Pembayaran</label>
                                <select name="payment_status" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status Publish</label>
                                <select name="status" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cari</label>
                                <input type="text" name="search" class="form-control" placeholder="Judul undangan atau nama user..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Statistics Cards -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-envelope"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Undangan</span>
                                <span class="info-box-number">{{ \App\Models\Invitation::count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Sudah Bayar</span>
                                <span class="info-box-number">{{ \App\Models\Invitation::where('is_paid', true)->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Belum Bayar</span>
                                <span class="info-box-number">{{ \App\Models\Invitation::where('is_paid', false)->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-globe"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Published</span>
                                <span class="info-box-number">{{ \App\Models\Invitation::where('status', 'published')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="20%">Judul Undangan</th>
                                <th width="15%">User</th>
                                <th width="12%">Template</th>
                                <th width="10%">Status</th>
                                <th width="10%">Pembayaran</th>
                                <th width="8%">Views</th>
                                <th width="8%">Guests</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invitations as $invitation)
                            <tr>
                                <td>{{ $invitation->id }}</td>
                                <td>
                                    <strong>{{ $invitation->title }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="far fa-calendar"></i> {{ $invitation->event_date ? $invitation->event_date->format('d M Y') : '-' }}
                                    </small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $invitation->user_id) }}" class="text-primary">
                                        {{ $invitation->user->name }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $invitation->user->email }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $invitation->template->name }}</span>
                                </td>
                                <td>
                                    @if($invitation->status === 'published')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Published
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-file"></i> Draft
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($invitation->is_paid)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Lunas
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $invitation->paid_at ? $invitation->paid_at->format('d M Y') : '-' }}</small>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> Belum Bayar
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <i class="fas fa-eye"></i> {{ $invitation->views_count }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-primary">
                                        <i class="fas fa-users"></i> {{ $invitation->guests_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($invitation->status === 'published')
                                            <a href="{{ route('public.invitation', $invitation->unique_url) }}"
                                               class="btn btn-info"
                                               target="_blank"
                                               title="Lihat Undangan">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif

                                        @if($invitation->is_paid)
                                            <form action="{{ route('admin.invitations.deactivate-payment', $invitation->id) }}"
                                                  method="POST"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Yakin ingin menonaktifkan pembayaran undangan ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-warning" title="Nonaktifkan Pembayaran">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.invitations.activate-payment', $invitation->id) }}"
                                                  method="POST"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Yakin ingin mengaktifkan pembayaran undangan ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-success" title="Aktifkan Pembayaran">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Tidak ada undangan ditemukan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $invitations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .info-box {
        min-height: 80px;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush
