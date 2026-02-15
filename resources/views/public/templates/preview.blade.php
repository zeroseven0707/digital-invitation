<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $template->name }} - NIKAHIN</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #1a1410;
        }

        .preview-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(26, 20, 16, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .preview-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .template-info h1 {
            color: #d4af37;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .template-info p {
            color: rgba(255,255,255,0.7);
            font-size: 14px;
        }

        .preview-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-back {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.2);
        }

        .btn-use {
            background: linear-gradient(135deg, #d4af37, #b8941e);
            color: white;
        }

        .btn-use:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }

        .preview-container {
            margin-top: 80px;
            min-height: calc(100vh - 80px);
        }

        .preview-frame {
            width: 100%;
            min-height: calc(100vh - 80px);
            border: none;
            background: white;
        }

        @media (max-width: 768px) {
            .template-info h1 {
                font-size: 16px;
            }

            .template-info p {
                display: none;
            }

            .btn {
                padding: 8px 15px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <!-- Preview Header -->
    <div class="preview-header">
        <div class="container">
            <div class="template-info">
                <h1>{{ $template->name }}</h1>
                <p>Preview Template Undangan</p>
            </div>
            <div class="preview-actions">
                <a href="{{ route('public.templates.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                @auth
                    <a href="{{ route('invitations.create', ['template' => $template->id]) }}" class="btn btn-use">
                        <i class="fas fa-check"></i> Gunakan Template
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-use">
                        <i class="fas fa-user-plus"></i> Daftar untuk Menggunakan
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Preview Container -->
    <div class="preview-container">
        {!! $renderedTemplate !!}
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
