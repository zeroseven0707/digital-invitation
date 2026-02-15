@extends('layouts.admin')

@section('page-title', 'Manajemen Template')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Manajemen Template</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-palette mr-2"></i>Semua Template
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.templates.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Template Baru
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($templates as $template)
                    <div class="col-md-4">
                        <div class="card card-outline {{ $template->is_active ? 'card-success' : 'card-secondary' }}">
                            <div class="card-header">
                                <h3 class="card-title">{{ $template->name }}</h3>
                                <div class="card-tools">
                                    @if($template->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                @if($template->thumbnail_path && Storage::exists($template->thumbnail_path))
                                    <img src="{{ Storage::url($template->thumbnail_path) }}"
                                         alt="{{ $template->name }}"
                                         class="img-fluid mb-2"
                                         style="max-height: 200px; width: 100%; object-fit: cover;">
                                @else
                                    <div class="bg-light text-center p-5 mb-2">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                        <p class="text-muted mt-2">No thumbnail</p>
                                    </div>
                                @endif

                                <p class="text-muted">{{ Str::limit($template->description, 100) }}</p>

                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-envelope"></i>
                                            {{ $template->invitations_count }} invitations
                                        </small>
                                    </div>
                                    <div class="col-6 text-right">
                                        <small class="text-muted">
                                            {{ $template->created_at->format('d M Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <form action="{{ route('admin.templates.destroy', $template) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus template ini? Template digunakan oleh {{ $template->invitations_count }} undangan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            {{ $template->invitations_count > 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>

                                @if($template->invitations_count > 0)
                                    <small class="text-muted ml-2">
                                        Tidak bisa dihapus (sedang digunakan)
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($templates->isEmpty())
                <p class="text-center text-muted">Belum ada template.</p>
            @endif
        </div>
    </div>
@endsection
