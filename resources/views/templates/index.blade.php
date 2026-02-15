<x-user-layout>
    <x-slot name="title">Template Undangan</x-slot>
    <x-slot name="header">Pilih Template Undangan</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Template</li>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Template</h3>
                </div>
                <div class="card-body">
                    @if($templates->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-palette fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada template yang tersedia saat ini.</h5>
                        </div>
                    @else
                        <div class="row">
                            @foreach($templates as $template)
                                <div class="col-md-4 col-sm-6">
                                    <div class="card card-outline card-primary">
                                        <div class="card-body box-profile">
                                            <div class="text-center mb-3">
                                                @if($template->thumbnail_path && Storage::disk('public')->exists($template->thumbnail_path))
                                                    <img src="{{ Storage::disk('public')->url($template->thumbnail_path) }}"
                                                         alt="{{ $template->name }}"
                                                         class="img-fluid"
                                                         style="max-height: 200px;">
                                                @else
                                                    <i class="fas fa-image fa-5x text-muted"></i>
                                                @endif
                                            </div>

                                            <h3 class="profile-username text-center">{{ $template->name }}</h3>
                                            <p class="text-muted text-center">{{ $template->description }}</p>

                                            <div class="btn-group btn-block">
                                                <a href="{{ route('templates.show', $template->id) }}" class="btn btn-primary">
                                                    <i class="fas fa-eye"></i> Preview
                                                </a>
                                                <a href="{{ route('invitations.create', ['template' => $template->id]) }}" class="btn btn-success">
                                                    <i class="fas fa-check"></i> Pilih
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-user-layout>
