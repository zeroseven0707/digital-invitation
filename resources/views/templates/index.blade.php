<x-user-layout>
    <x-slot name="title">Template Undangan</x-slot>
    <x-slot name="header">Pilih Template Undangan</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Template</li>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary shadow-sm" style="border-radius: 15px; border-top: 3px solid #6b4ce6;">
                <div class="card-header bg-white py-3" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                    <h3 class="card-title m-0" style="font-weight: 700; color: #1a1a2e; font-size: 1.2rem;">
                        <i class="fas fa-palette mr-2" style="color: #6b4ce6;"></i>Koleksi Desain Terbaik Untuk Anda
                    </h3>
                </div>
                <div class="card-body bg-light" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; padding: 40px 20px;">
                    @if($templates->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-palette fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                            <h5 class="text-muted">Tidak ada template yang tersedia saat ini.</h5>
                        </div>
                    @else
                        <div class="user-templates-grid">
                            @foreach($templates as $template)
                                <div class="user-template-card">
                                    <div class="user-template-phone">
                                        <div class="user-template-screen">
                                            @if($template->thumbnail_path && Storage::disk('public')->exists($template->thumbnail_path))
                                                <img src="{{ Storage::disk('public')->url($template->thumbnail_path) }}"
                                                     alt="{{ $template->name }}">
                                            @else
                                                <div class="user-template-screen-placeholder">
                                                    <i class="fas fa-image fa-2x text-white-50 mb-2"></i>
                                                    <span class="text-white-50 text-xs">Belum Ada Preview</span>
                                                </div>
                                            @endif
                                            <div class="user-template-overlay">
                                                <div class="d-flex flex-column gap-2 px-3 w-100 text-center">
                                                    <a href="{{ route('public.templates.preview', $template->id) }}" target="_blank" class="btn btn-light btn-sm font-weight-bold shadow-sm mb-2" style="border-radius: 50px; color: #6b4ce6; width: 100%;">
                                                        <i class="fas fa-eye mr-1"></i> Preview
                                                    </a>
                                                    <a href="{{ route('invitations.create', ['template_id' => $template->id, 'template_name' => $template->name]) }}" class="btn btn-success btn-sm font-weight-bold shadow-sm" style="border-radius: 50px; background-color: #28a745; border-color: #28a745; width: 100%;">
                                                        <i class="fas fa-check mr-1"></i> Pilih Desain
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="user-template-footer">
                                        <h4 class="user-template-name">{{ $template->name }}</h4>
                                        <p class="user-template-desc text-muted mt-1 text-xs">
                                            {{ Str::limit($template->description, 50) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .user-templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 40px;
            justify-items: center;
        }

        .user-template-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            cursor: pointer;
            transition: transform 0.3s ease;
            width: 100%;
            max-width: 200px;
        }

        .user-template-card:hover {
            transform: translateY(-8px);
        }

        .user-template-phone {
            position: relative;
            width: 160px;
            background: #1a1a2e;
            border-radius: 28px;
            padding: 10px 8px;
            box-shadow:
                0 0 0 2px #2d2d44,
                0 20px 40px rgba(107, 76, 230, 0.12),
                0 8px 20px rgba(0,0,0,0.2);
            transition: box-shadow 0.3s ease;
        }

        .user-template-card:hover .user-template-phone {
            box-shadow:
                0 0 0 2px #6b4ce6,
                0 25px 50px rgba(107, 76, 230, 0.25),
                0 12px 30px rgba(0,0,0,0.3);
        }

        /* Phone Notch */
        .user-template-phone::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 42px;
            height: 6px;
            background: #2d2d44;
            border-radius: 3px;
            z-index: 2;
        }

        /* Phone Side Buttons */
        .user-template-phone::after {
            content: '';
            position: absolute;
            right: -3px;
            top: 60px;
            width: 3px;
            height: 30px;
            background: #2d2d44;
            border-radius: 0 2px 2px 0;
        }

        .user-template-screen {
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 9 / 19;
            position: relative;
            background: linear-gradient(135deg, #6b4ce6, #16162a);
        }

        .user-template-screen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .user-template-card:hover .user-template-screen img {
            transform: scale(1.04);
        }

        .user-template-screen-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            text-align: center;
        }

        .user-template-overlay {
            position: absolute;
            inset: 0;
            background: rgba(26, 26, 46, 0.75);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: 20px;
        }

        .user-template-card:hover .user-template-overlay {
            opacity: 1;
        }

        .user-template-footer {
            text-align: center;
            width: 100%;
        }

        .user-template-name {
            font-family: 'Inter', sans-serif;
            font-size: 0.92rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
        }

        .user-template-desc {
            font-size: 0.72rem;
            color: #666;
            margin: 4px 0 0;
        }
        
        .gap-2 {
            gap: 0.5rem !important;
        }
    </style>
</x-user-layout>
