@extends('layouts.admin')

@section('page-title', 'Detail User')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Manajemen User</a></li>
    <li class="breadcrumb-item active">Detail User</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>

                    <p class="text-muted text-center">
                        @if($user->is_admin)
                            <span class="badge badge-danger">Administrator</span>
                        @else
                            <span class="badge badge-primary">Regular User</span>
                        @endif
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Invitations</b> <a class="float-right">{{ $user->invitations->count() }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b>
                            <a class="float-right">
                                @if($user->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Registered</b> <a class="float-right">{{ $user->created_at->format('d M Y') }}</a>
                        </li>
                    </ul>

                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User's Invitations</h3>
                </div>
                <div class="card-body">
                    @if($user->invitations->isEmpty())
                        <p class="text-muted">This user hasn't created any invitations yet.</p>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Bride & Groom</th>
                                    <th>Template</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Guests</th>
                                    <th>Views</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->invitations as $invitation)
                                    <tr>
                                        <td>
                                            <strong>{{ $invitation->bride_name }}</strong> &
                                            <strong>{{ $invitation->groom_name }}</strong>
                                        </td>
                                        <td>{{ $invitation->template->name }}</td>
                                        <td>
                                            @if($invitation->status === 'published')
                                                <span class="badge badge-success">Published</span>
                                            @elseif($invitation->status === 'draft')
                                                <span class="badge badge-secondary">Draft</span>
                                            @else
                                                <span class="badge badge-warning">Unpublished</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($invitation->is_paid)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i> Paid
                                                </span>
                                                @if($invitation->paid_at)
                                                    <br><small class="text-muted">{{ $invitation->paid_at->format('d M Y H:i') }}</small>
                                                @endif
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle"></i> Unpaid
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $invitation->guests->count() }}</td>
                                        <td>{{ $invitation->views->count() }}</td>
                                        <td>{{ $invitation->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if($invitation->is_paid)
                                                <form action="{{ route('admin.users.invitations.deactivate-payment', [$user->id, $invitation->id]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Yakin ingin menonaktifkan pembayaran?')">
                                                        <i class="fas fa-ban"></i> Nonaktifkan
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.invitations.activate-payment', [$user->id, $invitation->id]) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i> Aktifkan
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
