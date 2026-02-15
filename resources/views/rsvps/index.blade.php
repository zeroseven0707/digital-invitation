<x-user-layout>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar RSVP</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('invitations.show', $invitation) }}">Detail Undangan</a></li>
                        <li class="breadcrumb-item active">Daftar RSVP</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Invitation Info -->
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <h5 class="card-title">{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</h5>
                    <p class="card-text text-muted">{{ \Carbon\Carbon::parse($invitation->akad_date)->isoFormat('dddd, D MMMM YYYY') }}</p>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $rsvps->count() }}</h3>
                            <p>Total RSVP</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RSVP List -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Ucapan & Doa</h3>
                </div>
                <div class="card-body">
                    @if($rsvps->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada RSVP</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 20%">Nama</th>
                                        <th style="width: 60%">Ucapan & Doa</th>
                                        <th style="width: 20%">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rsvps as $rsvp)
                                    <tr>
                                        <td>
                                            <strong>{{ $rsvp->name }}</strong>
                                        </td>
                                        <td>
                                            <p class="mb-0">{{ $rsvp->message }}</p>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $rsvp->created_at->diffForHumans() }}
                                            </small>
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
