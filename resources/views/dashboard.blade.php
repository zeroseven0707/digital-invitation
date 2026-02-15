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
                <a href="{{ route('invitations.create') }}" class="small-box-footer">
                    Buat Undangan <i class="fas fa-arrow-circle-right"></i>
                </a>
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
                <a href="#" class="small-box-footer">
                    Info Lengkap <i class="fas fa-arrow-circle-right"></i>
                </a>
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
                <a href="#" class="small-box-footer">
                    Info Lengkap <i class="fas fa-arrow-circle-right"></i>
                </a>
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
</x-user-layout>
