<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview - {{ $invitation->bride_name }} & {{ $invitation->groom_name }}</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .preview-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #1f2937;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .preview-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .preview-header h1 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }
        .preview-header .badge {
            background: #3b82f6;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .preview-header .badge.draft {
            background: #6b7280;
        }
        .preview-header .badge.published {
            background: #10b981;
        }
        .preview-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }
        .btn {
            padding: 0.5rem 1.25rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-block;
        }
        .btn-secondary {
            background: #374151;
            color: white;
        }
        .btn-secondary:hover {
            background: #4b5563;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        .btn-primary:hover {
            background: #2563eb;
        }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-success:hover {
            background: #059669;
        }
        .preview-content {
            margin-top: 60px;
        }
        @media (max-width: 768px) {
            .preview-header {
                padding: 0.75rem 1rem;
                flex-direction: column;
                gap: 0.75rem;
                align-items: stretch;
            }
            .preview-header-left {
                justify-content: space-between;
            }
            .preview-header h1 {
                font-size: 1rem;
            }
            .preview-actions {
                justify-content: stretch;
                flex-direction: column;
            }
            .btn {
                text-align: center;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="preview-header">
        <div class="preview-header-left">
            <h1>Preview Mode</h1>
            <span class="badge {{ $invitation->status }}">{{ ucfirst($invitation->status) }}</span>
        </div>
        <div class="preview-actions">
            <a href="{{ route('invitations.edit', $invitation->id) }}" class="btn btn-secondary">
                Kembali ke Edit
            </a>
            @if($invitation->status === 'draft')
                <form action="{{ route('invitations.publish', $invitation->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        Publikasikan
                    </button>
                </form>
            @elseif($invitation->status === 'published')
                <form action="{{ route('invitations.unpublish', $invitation->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        Unpublish
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="preview-content">
        {!! $renderedTemplate !!}
    </div>
</body>
</html>
