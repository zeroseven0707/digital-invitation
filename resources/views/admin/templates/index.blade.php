@extends('layouts.admin')

@section('page-title', 'Manajemen Template')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Manajemen Template</li>
@endsection

@section('content')
    <div class="card card-outline card-primary shadow-sm" style="border-radius: 15px; border-top: 3px solid #6b4ce6;">
        <div class="card-header bg-white d-flex align-items-center justify-content-between py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <h3 class="card-title m-0" style="font-weight: 700; color: #1a1a2e; font-size: 1.25rem;">
                <i class="fas fa-palette mr-2" style="color: #6b4ce6;"></i>Semua Koleksi Template
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.templates.create') }}" class="btn btn-primary px-3 py-2" style="border-radius: 10px; font-weight: 600; background-color: #6b4ce6; border-color: #6b4ce6;">
                    <i class="fas fa-plus mr-1"></i> Tambah Template Baru
                </a>
            </div>
        </div>
        <div class="card-body bg-light" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; padding: 40px 20px;">
            <div class="admin-templates-grid">
                @forelse($templates as $template)
                    <div class="admin-template-card">
                        <div class="admin-template-phone">
                            <div class="admin-template-screen">
                                @if($template->thumbnail_path && Storage::disk('public')->exists($template->thumbnail_path))
                                    <img src="{{ Storage::disk('public')->url($template->thumbnail_path) }}"
                                         alt="{{ $template->name }}">
                                @else
                                    <div class="admin-template-screen-placeholder">
                                        <i class="fas fa-image fa-2x text-white-50 mb-2"></i>
                                        <span class="text-white-50 text-xs">Belum Ada Preview</span>
                                    </div>
                                @endif
                                <div class="admin-template-overlay">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('public.templates.preview', $template->id) }}" target="_blank" class="btn btn-light btn-sm font-weight-bold shadow-sm px-3 mb-2" style="border-radius: 50px; color: #6b4ce6;">
                                            <i class="fas fa-eye mr-1"></i> Preview
                                        </a>
                                        <form action="{{ route('admin.templates.destroy', $template) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus template ini? Template digunakan oleh {{ $template->invitations_count }} undangan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger btn-sm font-weight-bold shadow-sm px-3"
                                                    style="border-radius: 50px;"
                                                    {{ $template->invitations_count > 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-template-footer">
                            <h4 class="admin-template-name">{{ $template->name }}</h4>
                            <div class="d-flex justify-content-center align-items-center gap-2 mt-1 flex-wrap">
                                @if($template->is_active)
                                    <span class="badge badge-success px-2 py-1" style="font-size: 0.7rem; border-radius: 50px;">Aktif</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1" style="font-size: 0.7rem; border-radius: 50px;">Nonaktif</span>
                                @endif
                                <span class="badge badge-info px-2 py-1" style="font-size: 0.7rem; border-radius: 50px; background-color: #6b4ce6 !important;">
                                    <i class="fas fa-envelope mr-1"></i>{{ $template->invitations_count }} Digunakan
                                </span>
                            </div>
                            <p class="admin-template-desc text-muted mt-2 text-xs px-2" style="line-height: 1.4;">
                                {{ Str::limit($template->description, 60) }}
                            </p>
                            @if($template->invitations_count > 0)
                                <div class="text-center mt-2">
                                    <small class="text-danger font-weight-bold" style="font-size: 0.65rem;">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Sedang digunakan
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 w-100" style="grid-column: 1/-1;">
                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada template yang terdaftar.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .admin-templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 40px;
            justify-items: center;
        }

        .admin-template-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            cursor: pointer;
            transition: transform 0.3s ease;
            width: 100%;
            max-width: 220px;
        }

        .admin-template-card:hover {
            transform: translateY(-8px);
        }

        .admin-template-phone {
            position: relative;
            width: 170px;
            background: #1a1a2e;
            border-radius: 28px;
            padding: 10px 8px;
            box-shadow:
                0 0 0 2px #2d2d44,
                0 20px 40px rgba(107, 76, 230, 0.15),
                0 8px 20px rgba(0,0,0,0.25);
            transition: box-shadow 0.3s ease;
        }

        .admin-template-card:hover .admin-template-phone {
            box-shadow:
                0 0 0 2px #6b4ce6,
                0 25px 50px rgba(107, 76, 230, 0.3),
                0 12px 30px rgba(0,0,0,0.35);
        }

        /* Phone Notch */
        .admin-template-phone::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 45px;
            height: 6px;
            background: #2d2d44;
            border-radius: 3px;
            z-index: 2;
        }

        /* Phone Side Buttons */
        .admin-template-phone::after {
            content: '';
            position: absolute;
            right: -3px;
            top: 60px;
            width: 3px;
            height: 30px;
            background: #2d2d44;
            border-radius: 0 2px 2px 0;
        }

        .admin-template-screen {
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 9 / 19;
            position: relative;
            background: linear-gradient(135deg, #6b4ce6, #16162a);
        }

        .admin-template-screen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .admin-template-card:hover .admin-template-screen img {
            transform: scale(1.04);
        }

        .admin-template-screen-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            text-align: center;
        }

        .admin-template-overlay {
            position: absolute;
            inset: 0;
            background: rgba(26, 26, 46, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: 20px;
        }

        .admin-template-card:hover .admin-template-overlay {
            opacity: 1;
        }

        .admin-template-footer {
            text-align: center;
            width: 100%;
        }

        .admin-template-name {
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
        }

        .admin-template-desc {
            font-size: 0.75rem;
            color: #666;
            margin: 4px 0 0;
        }
        
        .gap-2 {
            gap: 0.5rem !important;
        }
    </style>
@endsection
